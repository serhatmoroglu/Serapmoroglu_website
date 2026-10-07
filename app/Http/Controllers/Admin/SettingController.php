<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Notifier;
use App\Support\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SettingController extends Controller
{
    /** Panelde düzenlenebilen tüm ayarlar. type: text|textarea|rich|image|images|video|email|password|select */
    public const SECTIONS = [
        'genel' => ['Genel', [
            'site_name' => ['Site / doktor adı', 'text'],
            'doctor_title' => ['Unvan', 'text'],
            'tagline' => ['Slogan', 'text'],
            'seo_description' => ['Arama motoru açıklaması (SEO)', 'textarea', 'Google sonuçlarında görünen kısa tanıtım, yaklaşık 150 karakter.'],
            'ga_id' => ['Google Analytics kimliği', 'text', 'Örnek: G-XXXXXXXXXX. Boş bırakılırsa ölçüm yapılmaz.'],
        ]],
        'iletisim' => ['İletişim ve WhatsApp', [
            'phone' => ['Telefon (sitede görünen)', 'text', 'Örnek: +90 533 798 53 88'],
            'whatsapp' => ['WhatsApp numarası', 'text', 'Ülke koduyla, sadece rakam: 905535319030'],
            'whatsapp_message' => ['WhatsApp hazır mesajı', 'textarea', 'Ziyaretçi WhatsApp butonuna basınca bu mesaj hazır gelir.'],
            'address' => ['Adres', 'textarea'],
            'hours_weekday' => ['Hafta içi saatleri', 'text'],
            'hours_saturday' => ['Cumartesi saatleri', 'text'],
            'hours_sunday' => ['Pazar', 'text'],
            'map_embed' => ['Harita (yerleştirme adresi)', 'text', 'Google Haritalar → Paylaş → Harita yerleştir bölümündeki iframe içindeki src adresi.'],
            'map_link' => ['Yol tarifi bağlantısı', 'text'],
        ]],
        'sosyal' => ['Sosyal medya', [
            'instagram' => ['Instagram bağlantısı', 'text'],
            'facebook' => ['Facebook bağlantısı', 'text'],
            'youtube' => ['YouTube bağlantısı', 'text'],
        ]],
        'anasayfa' => ['Ana sayfa', [
            'hero_tag' => ['Üst etiket', 'text'],
            'hero_title' => ['Ana başlık', 'text'],
            'hero_lead' => ['Başlık altı yazı', 'textarea'],
            'hero_image' => ['Ana görsel (dikey fotoğraf önerilir)', 'image'],
            'strip_1_value' => ['Şerit 1: büyük yazı', 'text'], 'strip_1_label' => ['Şerit 1: açıklama', 'text'],
            'strip_2_value' => ['Şerit 2: büyük yazı', 'text'], 'strip_2_label' => ['Şerit 2: açıklama', 'text'],
            'strip_3_value' => ['Şerit 3: büyük yazı', 'text'], 'strip_3_label' => ['Şerit 3: açıklama', 'text'],
            'strip_4_value' => ['Şerit 4: büyük yazı', 'text'], 'strip_4_label' => ['Şerit 4: açıklama', 'text'],
            'doctor_quote' => ['Doktor sözü', 'text'],
            'doctor_text' => ['Doktor kısa tanıtım', 'textarea'],
            'doctor_image' => ['Doktor fotoğrafı', 'image'],
            'form_title' => ['Form başlığı', 'text'],
            'form_lead' => ['Form açıklaması', 'text'],
            'form_subjects' => ['Form konu listesi', 'textarea', 'Her satıra bir konu yazın.'],
        ]],
        'hakkimda' => ['Hakkımızda sayfası', [
            'about_title' => ['Başlık', 'text'],
            'about_bio' => ['Hakkımda metni', 'rich'],
            'about_video' => ['Video (mp4)', 'video'],
            'about_comfort_title' => ['İkinci bölüm başlığı', 'text'],
            'about_comfort' => ['İkinci bölüm metni', 'rich'],
            'about_images' => ['Fotoğraf galerisi', 'images'],
        ]],
        'klinik' => ['Kliniğimiz sayfası', [
            'klinik_intro' => ['Giriş yazısı', 'textarea'],
            'klinik_images' => ['Klinik fotoğrafları', 'images'],
        ]],
        'eposta' => ['Bildirim ve e-posta (SMTP)', [
            'notify_email' => ['Taleplerin gideceği e-posta', 'email', 'Formdan gelen her talep bu adrese e-posta olarak iletilir.'],
            'smtp_host' => ['SMTP sunucusu', 'text', 'Gmail için: smtp.gmail.com'],
            'smtp_port' => ['Port', 'text', 'Gmail için 587 (TLS) ya da 465 (SSL).'],
            'smtp_encryption' => ['Şifreleme', 'select', '', ['tls' => 'TLS (587)', 'ssl' => 'SSL (465)']],
            'smtp_user' => ['SMTP kullanıcı adı', 'text', 'Gmail adresiniz.'],
            'smtp_pass' => ['SMTP şifresi (uygulama şifresi)', 'password', 'Gmail şifreniz değil, Google hesabından oluşturduğunuz 16 haneli uygulama şifresi. Boş bırakırsanız mevcut şifre korunur.'],
            'mail_from_address' => ['Gönderen adresi', 'email', 'Boşsa kullanıcı adı kullanılır.'],
            'mail_from_name' => ['Gönderen adı', 'text'],
        ]],
    ];

    public function edit(?string $section = null)
    {
        $section ??= 'genel';
        abort_unless(isset(self::SECTIONS[$section]), 404);
        return view('admin.settings', ['section' => $section, 'sections' => self::SECTIONS]);
    }

    public function update(Request $request, string $section)
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);
        $fields = self::SECTIONS[$section][1];

        $rules = [];
        foreach ($fields as $key => $f) {
            $rules[$key] = match ($f[1]) {
                'email' => ['nullable', 'email', 'max:160'],
                'image' => ['nullable'],
                default => ['nullable', 'string'],
            };
        }
        $request->validate($rules, [], collect($fields)->map(fn ($f) => $f[0])->all());
        $request->validate([
            'upload.*' => ['nullable'],
            'upload.*.*' => ['nullable', 'file', 'max:20480'],
        ], [], ['upload.*.*' => 'Yüklenen dosya']);
        foreach ($fields as $key => $f) {
            if (in_array($f[1], ['image', 'video']) && ($file = $request->file("upload.$key")) && ! $file->isValid()) {
                return back()->withErrors(["upload.$key" => 'Dosya yüklenemedi (boyutu sunucu sınırını aşıyor olabilir).']);
            }
        }

        foreach ($fields as $key => $f) {
            $type = $f[1];
            switch ($type) {
                case 'image':
                case 'video':
                    $cur = (string) Setting::get($key);
                    if ($file = $request->file("upload.$key")) {
                        abort_unless($type === 'video' ? str_starts_with((string) $file->getMimeType(), 'video/') : str_starts_with((string) $file->getMimeType(), 'image/'), 422, 'Dosya türü geçersiz.');
                        Uploads::forget($cur);
                        Setting::put($key, $type === 'video' ? Uploads::video($file) : Uploads::image($file));
                    } elseif ($request->boolean("remove.$key")) {
                        Uploads::forget($cur);
                        Setting::put($key, '');
                    }
                    break;
                case 'images':
                    $current = json_decode((string) Setting::get($key, '[]'), true) ?: [];
                    $keep = array_values(array_intersect($current, (array) $request->input("keep.$key", [])));
                    foreach ($current as $c) {
                        if (! in_array($c, $keep, true)) {
                            Uploads::forget($c);
                        }
                    }
                    foreach ((array) $request->file("upload.$key", []) as $file) {
                        if ($file && str_starts_with((string) $file->getMimeType(), 'image/')) {
                            $keep[] = Uploads::image($file);
                        }
                    }
                    Setting::put($key, json_encode($keep, JSON_UNESCAPED_SLASHES));
                    break;
                case 'password':
                    if (filled($request->input($key))) {
                        Setting::put($key, Crypt::encryptString($request->input($key)));
                    }
                    break;
                case 'rich':
                    Setting::put($key, (string) $request->input($key));
                    break;
                default:
                    $v = (string) $request->input($key);
                    if ($key === 'whatsapp') {
                        $v = preg_replace('/\D+/', '', $v);
                    }
                    Setting::put($key, trim($v));
            }
        }
        return redirect()->route('admin.settings', $section)->with('ok', 'Ayarlar kaydedildi.');
    }

    public function testMail(Request $request)
    {
        $to = trim((string) setting('notify_email'));
        if ($to === '') {
            return back()->with('err', 'Önce taleplerin gideceği e-posta adresini girip kaydedin.');
        }
        try {
            Notifier::send($to, 'Test e-postası: '.setting('site_name'), "Bu bir test mesajıdır.\nE-posta ayarlarınız çalışıyor.\n".now()->format('d.m.Y H:i'));
            $note = Notifier::mailer() === 'dynamic' ? '' : ' (SMTP girilmediği için gönderim kaydı sadece günlüğe yazıldı)';
            return back()->with('ok', "Test e-postası $to adresine gönderildi$note.");
        } catch (\Throwable $e) {
            return back()->with('err', 'E-posta gönderilemedi: '.\Illuminate\Support\Str::limit($e->getMessage(), 300));
        }
    }
}
