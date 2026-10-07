<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Tek seferlik kurulum: tabloları oluşturur ve içerikleri yükler.
 * Çalışması için .env içinde INSTALL_TOKEN tanımlı olmalı. Başarılı kurulumdan sonra kendini kilitler.
 */
class InstallController extends Controller
{
    public function run(Request $request)
    {
        $lock = storage_path('app/installed.lock');
        $token = (string) env('INSTALL_TOKEN');
        abort_if(file_exists($lock) || $token === '' || ! hash_equals($token, (string) $request->query('token')), 404);

        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            return $this->page('Veritabanına bağlanılamadı', 'Lütfen laravel/.env dosyasındaki DB_DATABASE, DB_USERNAME ve DB_PASSWORD değerlerini kontrol edin.<br><small>'.e(mb_substr($e->getMessage(), 0, 220)).'</small>', false);
        }

        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
            $out = Artisan::output();
        } catch (\Throwable $e) {
            return $this->page('Kurulum sırasında hata oluştu', e(mb_substr($e->getMessage(), 0, 400)), false);
        }

        file_put_contents($lock, date('c'));
        preg_match('~Yönetici giriş: (\S+) / (\S+)~u', $out, $m);

        $creds = $m ? '<p><b>Yönetici girişi</b><br>E-posta: <code>'.e($m[1]).'</code><br>Geçici şifre: <code>'.e($m[2]).'</code></p><p>Bu şifreyi şimdi bir yere not edin, bir daha gösterilmeyecek. İlk girişte yeni şifre belirlemeniz istenecek.</p>'
            : '<p>Yönetici hesabı zaten vardı, mevcut şifrenizle giriş yapın.</p>';
        return $this->page('Kurulum tamamlandı', $creds.'<p><a href="/yonetim">Yönetim paneline git →</a></p><p><small>Güvenlik için laravel/.env içindeki INSTALL_TOKEN satırını silebilirsiniz. Bu sayfa artık kapalıdır.</small></p>', true);
    }

    private function page(string $title, string $body, bool $ok)
    {
        return response('<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex"><title>'.e($title).'</title>'
            .'<body style="font:16px/1.6 system-ui;max-width:560px;margin:8vh auto;padding:0 16px;color:#16204a"><h1 style="color:'.($ok ? '#137a4d' : '#b3261e').'">'.e($title).'</h1>'.$body.'</body>', $ok ? 200 : 500);
    }
}
