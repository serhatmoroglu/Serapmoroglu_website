# Dr. Serap Özdamar — web sitesi

Laravel 13 (PHP 8.3) · MySQL · yönetim paneli dahil, derleme adımı gerektirmez.

- Herkese açık site: `routes/web.php`, `resources/views/pages`, stil `public/css/site.css`
- Yönetim paneli: `/yonetim` (`app/Http/Controllers/Admin`)
- Admin'den düzenlenen ayarlar: `config/clinic.php` (varsayılanlar) + `settings` tablosu
- Eski WordPress içeriği: `_kaynak/` (ham sayfalar) ve `_kaynak/extract.py` (içerik çıkarma)
- Yayın paketi: `tools/build-release.sh <site-adresi>` → `release/serapozdamar-site.zip`
- Yayına alma: [DEPLOY.md](DEPLOY.md)

Yerel geliştirme: `composer install && cp .env.example .env && php artisan key:generate && php artisan migrate --seed && php artisan serve`
