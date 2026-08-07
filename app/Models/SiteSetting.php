<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $guarded = [];

    public $timestamps = true;

    /** Get a setting value by key, with optional default. */
    public static function get(string $key, $default = null)
    {
        try {
            $all = Cache::rememberForever('site_settings', function () {
                return static::pluck('value', 'key')->toArray();
            });

            return $all[$key] ?? $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }


    /** Set / update a setting value. */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('site_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site_settings'));
        static::deleted(fn () => Cache::forget('site_settings'));
    }
}
