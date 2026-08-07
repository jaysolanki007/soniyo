<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Resolve an image value from a request: a newly uploaded file
     * (stored on the public disk) takes priority, otherwise a pasted URL.
     * Returns the stored path/URL string, or the existing value if neither.
     */
    protected function resolveImage(Request $request, string $fileField, string $urlField, ?string $existing = null): ?string
    {
        if ($request->hasFile($fileField)) {
            return $request->file($fileField)->store('uploads', 'public');
        }

        $url = $request->input($urlField);
        if (! empty($url)) {
            return $url;
        }

        return $existing;
    }
}
