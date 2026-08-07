<?php

namespace App\Support;

use Illuminate\Support\Str;

class Img
{
    /**
     * Normalise an image value to a usable URL.
     * Accepts a full external URL (http...) or a path stored on the
     * public disk (e.g. "uploads/abc.jpg" -> /storage/uploads/abc.jpg).
     */
    public static function url(?string $value, ?string $fallback = null): ?string
    {
        if (empty($value)) {
            return $fallback;
        }

        if (Str::startsWith($value, ['http://', 'https://', '//', '/'])) {
            return $value;
        }

        return asset('storage/'.$value);
    }
}
