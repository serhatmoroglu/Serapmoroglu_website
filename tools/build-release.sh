#!/usr/bin/env bash
# İHS paylaşımlı hosting için yüklenebilir paket üretir: release/serapozdamar-site.zip
# Kullanım: tools/build-release.sh https://drserapozdamar.com
set -euo pipefail
cd "$(dirname "$0")/.."
URL="${1:-https://drserapozdamar.com}"
OUT=release/site
rm -rf release && mkdir -p "$OUT/laravel"

# 1) Uygulama dosyaları (geliştirme dosyaları ve kaynak yedekleri hariç)
rsync -a --exclude='.git' --exclude='.github' --exclude='node_modules' --exclude='vendor' --exclude='tests' \
  --exclude='_kaynak' --exclude='release' --exclude='public' --exclude='storage' --exclude='.env*' \
  --exclude='database/*.sqlite*' --exclude='*.md' --exclude='phpunit.xml' --exclude='package.json' --exclude='vite.config.js' \
  --exclude='.styleci.yml' --exclude='.editorconfig' --exclude='.gitattributes' --exclude='.npmrc' --exclude='tools' --exclude='bootstrap/cache/*.php' ./ "$OUT/laravel/"
mkdir -p "$OUT/laravel/storage/"{app/public,framework/{cache/data,sessions,views},logs} "$OUT/laravel/bootstrap/cache"
# Bazı zip açıcılar boş klasörleri atlar; her klasöre yer tutucu dosya koy
for d in "$OUT/laravel/storage/app/public" "$OUT/laravel/storage/framework/cache/data" "$OUT/laravel/storage/framework/sessions" "$OUT/laravel/storage/framework/views" "$OUT/laravel/storage/logs" "$OUT/laravel/bootstrap/cache"; do echo "yer tutucu" > "$d/bos.txt"; done

# 2) Üretim bağımlılıkları (geliştirme paketleri olmadan)
cp composer.json composer.lock "$OUT/laravel/"
(cd "$OUT/laravel" && composer install --no-dev --optimize-autoloader --no-interaction --no-scripts -q)
# Paket boyutunu küçült: git geçmişi, testler, dokümanlar yüklenmez
find "$OUT/laravel/vendor" -type d -name .git -prune -exec rm -rf {} +
find "$OUT/laravel/vendor" -maxdepth 3 -type d \( -name .github -o -name tests -o -name docs -o -name examples \) -prune -exec rm -rf {} +
find "$OUT/laravel/vendor" -maxdepth 3 -type f \( -iname 'README*' -o -iname 'CHANGELOG*' -o -iname 'UPGRADE*' -o -name '*.md' \) -delete
(cd "$OUT/laravel" && php artisan package:discover -q && echo 'paket keşfi tamam')
php -r 'require "'"$OUT"'/laravel/vendor/autoload.php"; echo "autoload ok\n";' 

# 3) Web kökü dosyaları (public/ içeriği)
rsync -a public/ "$OUT/" --exclude='uploads/20*' --exclude='index.php' --exclude='hot' --exclude='storage'
cat > "$OUT/index.php" <<'PHP'
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Güvenli düzen: laravel klasörü web kökünün bir üstündeyse onu kullan; yoksa yanındakini
$base = is_dir(dirname(__DIR__).'/laravel') ? dirname(__DIR__).'/laravel' : __DIR__.'/laravel';

if (file_exists($maintenance = $base.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $base.'/vendor/autoload.php';

$app = require_once $base.'/bootstrap/app.php';
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
PHP
# .htaccess: HTTPS'e yönlendir + Laravel yönlendirmeleri + laravel/ klasörünü dışarıya kapat
{
cat <<'HT'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteCond %{HTTP:X-Forwarded-Proto} !https
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    RewriteRule ^laravel/ - [F,L]
</IfModule>
HT
cat public/.htaccess
cat <<'HT'

<FilesMatch "^\.">
    Require all denied
</FilesMatch>
<IfModule mod_headers.c>
    <FilesMatch "\.(css|js|jpg|jpeg|png|webp|gif|svg|woff2|mp4)$">
        Header set Cache-Control "public, max-age=2592000"
    </FilesMatch>
</IfModule>
HT
} > "$OUT/.htaccess"
printf '<IfModule mod_authz_core.c>\n    Require all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\n    Deny from all\n</IfModule>\n' > "$OUT/laravel/.htaccess"
mkdir -p "$OUT/uploads"

# 4) .env şablonu (anahtarlar üretilir)
KEY=$(php artisan key:generate --show)
TOKEN=$(php -r 'echo bin2hex(random_bytes(16));')
sed -e "s|__APP_KEY__|$KEY|" -e "s|__APP_URL__|$URL|" -e "s|__INSTALL_TOKEN__|$TOKEN|" .env.production.example > "$OUT/laravel/.env"
echo "$TOKEN" > release/KURULUM_ANAHTARI.txt

# 5) Zip
(cd "$OUT" && zip -qr ../serapozdamar-site.zip . -x '*.DS_Store')
ls -la release/serapozdamar-site.zip
echo "Kurulum adresi: $URL/kur?token=$TOKEN"
