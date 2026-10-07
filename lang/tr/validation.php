<?php

return [
    'required' => ':attribute alanı zorunludur.',
    'email' => ':attribute geçerli bir e-posta adresi olmalıdır.',
    'string' => ':attribute metin olmalıdır.',
    'max' => ['string' => ':attribute en fazla :max karakter olabilir.', 'file' => ':attribute en fazla :max KB olabilir.', 'numeric' => ':attribute en fazla :max olabilir.'],
    'min' => ['string' => ':attribute en az :min karakter olmalıdır.', 'numeric' => ':attribute en az :min olmalıdır.'],
    'unique' => ':attribute zaten kullanılıyor.',
    'confirmed' => ':attribute tekrarı eşleşmiyor.',
    'regex' => ':attribute biçimi geçersiz.',
    'image' => ':attribute bir görsel olmalıdır.',
    'mimes' => ':attribute şu türlerden biri olmalıdır: :values.',
    'integer' => ':attribute tam sayı olmalıdır.',
    'url' => ':attribute geçerli bir bağlantı olmalıdır.',
    'in' => 'Seçilen :attribute geçersiz.',
    'current_password' => 'Mevcut şifre yanlış.',
    'attributes' => [
        'name' => 'Ad', 'email' => 'E-posta', 'password' => 'Şifre', 'title' => 'Başlık', 'slug' => 'Adres',
        'phone' => 'Telefon', 'message' => 'Mesaj', 'subject' => 'Konu', 'image_file' => 'Görsel', 'body' => 'İçerik',
        'current_password' => 'Mevcut şifre',
    ],
];
