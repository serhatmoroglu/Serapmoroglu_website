<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $guarded = [];
    protected $casts = ['mail_sent' => 'boolean'];

    public const TYPES = ['arayin' => 'Beni arayın', 'randevu' => 'Randevu', 'iletisim' => 'İletişim formu'];
    public const STATUSES = ['yeni' => 'Yeni', 'arandi' => 'Arandı', 'kapandi' => 'Kapandı'];
}
