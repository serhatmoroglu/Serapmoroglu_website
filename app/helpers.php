<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('wa_link')) {
    /** WhatsApp bağlantısı (numara ayarlardan, mesaj ön dolu). */
    function wa_link(?string $text = null): string
    {
        $num = preg_replace('/\D+/', '', (string) setting('whatsapp'));
        $text ??= (string) setting('whatsapp_message');
        return 'https://wa.me/'.$num.($text !== '' ? '?text='.rawurlencode($text) : '');
    }
}

if (! function_exists('tel_link')) {
    function tel_link(): string
    {
        return 'tel:+'.ltrim(preg_replace('/\D+/', '', (string) setting('phone')), '+');
    }
}

if (! function_exists('img')) {
    /** Yüklenen görselin tam yolu; boşsa null. */
    function img(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        return str_starts_with($path, 'http') || str_starts_with($path, '/') ? $path : '/'.$path;
    }
}
