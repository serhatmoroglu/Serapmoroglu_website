<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Setting;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private function data(string $file): array
    {
        return json_decode(file_get_contents(database_path("seeders/data/$file.json")), true) ?: [];
    }

    public function run(): void
    {
        // 1) Ayarlar: varsayılanları veritabanına yaz ki panelde görünsün ve düzenlenebilsin
        foreach (config('clinic.defaults') as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
        $pages = $this->data('pages');
        $about = $pages['about'] ?? [];
        $img = fn (array $a) => json_encode(array_values(array_unique($a)), JSON_UNESCAPED_SLASHES);
        $strip = fn (string $s) => trim(preg_replace('/\s+/', ' ', $s));
        Setting::put('about_bio', '<p>'.e($strip($about['bio'] ?? '')).'</p>');
        Setting::put('about_comfort_title', $about['comfort_title'] ?? '');
        Setting::put('about_comfort', '<p>'.e($strip($about['comfort'] ?? '')).'</p>');
        Setting::put('about_images', $img($about['images'] ?? []));
        Setting::put('about_video', 'uploads/eski/hakkimizda-video.mp4');
        Setting::put('klinik_images', $img($pages['klinik']['images'] ?? []));
        Setting::put('home_images', $img($pages['home']['images'] ?? []));

        // 2) Tedaviler
        foreach ($this->data('treatments') as $t) {
            Treatment::updateOrCreate(['slug' => $t['slug']], [
                'title' => $t['title'], 'group' => $t['group'], 'summary' => $t['summary'],
                'body' => $t['body'], 'image' => $t['image'], 'sort' => $t['sort'], 'published' => true,
            ]);
        }

        // 3) Yazılar, gazete, videolar
        foreach (array_merge($this->data('posts'), $this->data('videos')) as $p) {
            if (! empty($p['body']) && $p['kind'] === 'yazi') {
                // Sayfa başlığı zaten h1; gövdedeki baştaki tekrarlayan h2'yi at, özeti paragraflardan üret
                $p['body'] = preg_replace('~^\s*<h2>.*?</h2>\s*~su', '', $p['body'], 1);
                preg_match_all('~<p>(.*?)</p>~su', $p['body'], $m);
                $txt = trim(preg_replace('/\s+/u', ' ', strip_tags(implode(' ', array_slice($m[1], 0, 3)))));
                $p['excerpt'] = Str::limit($txt, 200);
            }
            Post::updateOrCreate(['slug' => $p['slug']], [
                'kind' => $p['kind'], 'title' => $p['title'], 'excerpt' => $p['excerpt'] ?? null,
                'body' => $p['body'] ?? null, 'image' => $p['image'] ?? null,
                'external_url' => $p['external_url'] ?? null, 'video_url' => $p['video_url'] ?? null,
                'sort' => $p['sort'] ?? 0, 'published' => true, 'published_at' => null,
            ]);
        }

        // 4) Yönetici hesabı (ilk girişte şifre değiştirilir)
        if (! User::where('email', 'serhatmorogluis@gmail.com')->exists()) {
            $password = env('ADMIN_PASSWORD') ?: Str::password(12, symbols: false);
            User::create([
                'name' => 'Yönetici', 'email' => 'serhatmorogluis@gmail.com',
                'password' => $password, 'must_change_password' => true,
            ]);
            $this->command?->warn("Yönetici giriş: serhatmorogluis@gmail.com / $password");
        }
    }
}
