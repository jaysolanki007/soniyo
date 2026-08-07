<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ModuleAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $name = $request->route()?->getName();

        // Only guard named admin.* routes
        if ($name && str_starts_with($name, 'admin.')) {
            $module = explode('.', substr($name, strlen('admin.')))[0];

            if (! Auth::user()->canAccess($module)) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'You do not have permission to access that module.');
            }
        }

        return $next($request);
    }
}
