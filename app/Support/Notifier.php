<?php

namespace App\Support;

use App\Models\Lead;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/** Gelen taleplerde yönetici e-postasına bildirim gönderir. SMTP bilgisi panelden okunur. */
class Notifier
{
    /** Panelde SMTP girilmişse çalışma anında bir "dynamic" mailer tanımlar. */
    public static function mailer(): string
    {
        $host = trim((string) setting('smtp_host'));
        if ($host === '') {
            return config('mail.default');
        }
        $pass = '';
        try {
            $pass = ($raw = (string) setting('smtp_pass')) !== '' ? Crypt::decryptString($raw) : '';
        } catch (\Throwable) {
        }
        $enc = setting('smtp_encryption');
        Config::set('mail.mailers.dynamic', [
            'transport' => 'smtp',
            'host' => $host,
            'port' => (int) setting('smtp_port', 587),
            'scheme' => $enc === 'ssl' ? 'smtps' : 'smtp',
            'username' => setting('smtp_user') ?: null,
            'password' => $pass ?: null,
            'timeout' => 15,
        ]);
        $from = setting('mail_from_address') ?: setting('smtp_user');
        if ($from) {
            Config::set('mail.from', ['address' => $from, 'name' => setting('mail_from_name') ?: setting('site_name')]);
        }
        return 'dynamic';
    }

    public static function send(string $to, string $subject, string $body): void
    {
        Mail::mailer(self::mailer())->raw($body, fn ($m) => $m->to($to)->subject($subject));
    }

    public static function leadReceived(Lead $lead): bool
    {
        $to = trim((string) setting('notify_email'));
        if ($to === '') {
            return false;
        }
        $type = Lead::TYPES[$lead->type] ?? $lead->type;
        $lines = array_filter([
            "Tür: $type",
            "Ad soyad: {$lead->name}",
            $lead->phone ? "Telefon: {$lead->phone}" : null,
            $lead->email ? "E-posta: {$lead->email}" : null,
            $lead->subject ? "Konu: {$lead->subject}" : null,
            $lead->message ? "Mesaj:\n{$lead->message}" : null,
            '',
            'Gelen sayfa: '.($lead->page ?: '-'),
            'Tarih: '.$lead->created_at->format('d.m.Y H:i'),
            'Panel: '.url('/yonetim/talepler/'.$lead->id),
        ], fn ($l) => $l !== null);
        try {
            self::send($to, "Yeni $type talebi: {$lead->name}", implode("\n", $lines));
            return true;
        } catch (\Throwable $e) {
            Log::warning('Talep bildirimi gönderilemedi: '.$e->getMessage());
            return false;
        }
    }
}
