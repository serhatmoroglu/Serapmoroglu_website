<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    protected $guarded = [];

    /** Tüm ayarları tek sorguda yükler (istek başına bir kez). */
    public static function all_(): array
    {
        static $cache = null;
        if ($cache === null) {
            try {
                $cache = Schema::hasTable('settings') ? static::query()->pluck('value', 'key')->all() : [];
            } catch (\Throwable) {
                $cache = [];
            }
        }
        return $cache;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::all_();
        if (array_key_exists($key, $all) && $all[$key] !== null && $all[$key] !== '') {
            return $all[$key];
        }
        return $default ?? config("clinic.defaults.$key");
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
