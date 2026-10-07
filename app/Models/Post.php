<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];
    protected $casts = ['published' => 'boolean', 'published_at' => 'date'];

    public const KINDS = ['yazi' => 'Yazı', 'gazete' => 'Gazete / Basın', 'video' => 'Video'];

    public function scopePublished($q)
    {
        return $q->where('published', true);
    }

    public function scopeKind($q, string $kind)
    {
        return $q->where('kind', $kind);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** YouTube bağlantısından yerleştirme adresi üretir. */
    public function getEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }
        if (preg_match('~(?:youtu\.be/|v=|embed/)([\w-]{11})~', $this->video_url, $m)) {
            return 'https://www.youtube-nocookie.com/embed/'.$m[1];
        }
        return null;
    }
}
