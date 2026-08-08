<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Makes a public page cacheable by Vercel's edge CDN.
 *
 * Vercel refuses to cache any response carrying a Set-Cookie header,
 * and Laravel attaches a session cookie to every response. For fully
 * public pages (same HTML for every visitor) we strip cookies and set
 * s-maxage so the CDN serves the page instantly without invoking PHP.
 */
class EdgeCacheResponse
{
    public function handle(Request $request, Closure $next, int $seconds = 300): Response
    {
        $response = $next($request);

        // Only cache successful GET responses
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $response->headers->remove('Set-Cookie');
            $response->headers->set(
                'Cache-Control',
                "public, s-maxage={$seconds}, stale-while-revalidate=86400"
            );
        }

        return $response;
    }
}
