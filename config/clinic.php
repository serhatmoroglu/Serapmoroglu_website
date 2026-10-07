<?php

/**
 * Admin panelden değiştirilebilen ayarların varsayılan değerleri.
 * Panelde bir değer girilmemişse buradaki kullanılır.
 */
return [
    'defaults' => [
        'site_name' => 'Dr. Serap Özdamar',
        'doctor_title' => 'Ağız, Diş ve Çene Cerrahisi Uzmanı',
        'tagline' => 'Sağlıklı gülüşler için',

        'phone' => '+90 533 798 53 88',
        'whatsapp' => '905535319030',
        'whatsapp_message' => 'Merhaba, randevu hakkında bilgi almak istiyorum.',
        'public_email' => '',
        'address' => 'Barbaros Mah., Dereboyu Cad., Akzambak Sk. Varyap Meridian Grand Tower A Blok, Kat 8, Daire 91, Ataşehir / İstanbul',
        'hours_weekday' => '09:00 – 19:00',
        'hours_saturday' => '09:00 – 15:00',
        'hours_sunday' => 'Kapalı',
        'map_embed' => 'https://www.google.com/maps?q=Varyap%20Meridian%20Grand%20Tower%20Ata%C5%9Fehir&output=embed',
        'map_link' => 'https://www.google.com/maps/search/?api=1&query=Varyap+Meridian+Grand+Tower+Ata%C5%9Fehir',

        'instagram' => 'https://www.instagram.com/dr.serapozdamar',
        'facebook' => 'https://www.facebook.com/Dr.SerapOzdamar',
        'youtube' => '',

        'hero_tag' => 'Ağız, Diş ve Çene Cerrahisi Uzmanı',
        'hero_title' => 'Kendini yenile. Gülüşün güvende.',
        'hero_lead' => "Ataşehir'de implant, gömük diş ve estetik tedavilerde uzman kadro. İlk muayenede size özel bir plan çıkarıyoruz.",
        'hero_image' => 'uploads/eski/IMG-20241207-WA0038.jpg',
        'strip_1_value' => '2006', 'strip_1_label' => 'İstanbul Üniv. Diş Hekimliği',
        'strip_2_value' => '2012', 'strip_2_label' => 'Marmara Üniv. uzmanlık',
        'strip_3_value' => 'Ataşehir', 'strip_3_label' => 'Varyap Meridian, kat 8',
        'strip_4_value' => 'Pzt–Cmt', 'strip_4_label' => "09:00'dan itibaren",
        'doctor_quote' => 'Her hastamı birinci derece yakınım olarak kabul ederim.',
        'doctor_text' => "Ağız, Diş ve Çene Cerrahisi Uzmanı. Uzun yıllar farklı kliniklerde çalıştıktan sonra 2024'te Ataşehir'de kendi muayenehanesini açtı. Tedavi planını fonksiyon, sağlık ve estetik üçlüsüne göre yapar.",
        'doctor_image' => 'uploads/eski/IMG-20241207-WA0038.jpg',
        'form_title' => 'Sizi arayalım',
        'form_lead' => 'Adınızı ve numaranızı bırakın, size dönüş yapalım.',
        'form_subjects' => "İmplant Tedavisi\nGömük Diş Çekimi\nGülüş Tasarımı\nZirkonyum Kaplama\nOrtodonti Tedavisi\nKanal Tedavisi\nÇene Eklemi Tedavisi\nFiyat Bilgisi\nDiğer",

        'about_title' => 'Hakkımda',
        'klinik_intro' => 'Ağız, diş ve çene cerrahisi alanında modern ekipman ve konforlu bir ortamda hizmet veriyoruz.',

        'seo_description' => "Ataşehir'de Dr. Serap Özdamar: implant, gömük diş çekimi, sinüs lifting, gülüş tasarımı ve ağız, diş ve çene cerrahisi. Randevu için arayın.",

        'notify_email' => 'serhatmorogluis@gmail.com',
        'smtp_host' => '', 'smtp_port' => '587', 'smtp_encryption' => 'tls',
        'smtp_user' => '', 'smtp_pass' => '',
        'mail_from_address' => '', 'mail_from_name' => 'Dr. Serap Özdamar',
        'ga_id' => '',
    ],

    // Eski WordPress adreslerinden (?page_id=N) yeni adreslere 301 yönlendirme
    'legacy_pages' => [
        10 => '/tedaviler', 1607 => '/tedaviler/implant-tedavisi', 1620 => '/tedaviler/sinus-lifting-ameliyati',
        1622 => '/tedaviler/ileri-implant-cerrahisi', 1613 => '/tedaviler/gomuk-dis-cekimi', 1626 => '/tedaviler/lazer-uygulamalari',
        1628 => '/tedaviler/cene-kistleri', 1630 => '/tedaviler/bruksizim-ve-cene-eklemi', 1632 => '/tedaviler/protez-oncesi-cerrahi',
        1634 => '/tedaviler/endodontik-cerrahi', 1636 => '/tedaviler/dis-protezleri', 1638 => '/tedaviler/laminate-veneer',
        1640 => '/tedaviler/porselen-zirkonyum', 1642 => '/tedaviler/endodonti', 1644 => '/tedaviler/ortodonti',
        1854 => '/yazilar', 1863 => '/basinda-biz', 2078 => '/basinda-biz#video', 2071 => '/basinda-biz#gazete',
        12 => '/klinik', 8 => '/hakkimizda', 14 => '/iletisim',
        1898 => '/yazilar/implant-tedavisine-yolculuk', 1882 => '/yazilar/gomulu-20-yas-disleri',
        1927 => '/yazilar/kalp-ve-dis-sagligi', 1997 => '/yazilar/cene-eklemi-rahatsizliklari', 2009 => '/yazilar/bruksizm',
        2021 => '/yazilar/emax-ve-zirkonyum-kaplamalar-arasindaki-fark', 2034 => '/yazilar/pembe-estetik-nedir',
        2043 => '/yazilar/dis-cekimi-sonrasi-kanama',
    ],
];
