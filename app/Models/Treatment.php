<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $guarded = [];
    protected $casts = ['published' => 'boolean'];

    public const GROUPS = [
        'cerrahi' => 'Cerrahi',
        'protez' => 'Estetik ve protez',
        'tedavi' => 'Tedavi ve koruma',
    ];

    public function scopePublished($q)
    {
        return $q->where('published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
