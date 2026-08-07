<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        if (! $user->is_active || ! $user->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'You do not have access to the admin panel.',
            ]);
        }

        return $next($request);
    }
}
