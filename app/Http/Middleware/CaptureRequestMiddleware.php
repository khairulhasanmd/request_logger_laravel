<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RequestLog;

class CaptureRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip capturing for the report page itself to avoid recursive logging
        if (!str_starts_with($request->path(), 'report')) {
            RequestLog::create([
                'method' => $request->method(),
                'url' => $request->url(),
                'full_url' => $request->fullUrl(),
                'path' => $request->path(),
                'query_params' => $request->query(),
                'post_data' => $request->post(),
                'headers' => $request->headers->all(),
                'raw_body' => $request->getContent(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'server_vars' => [
                    'REMOTE_ADDR' => $request->server('REMOTE_ADDR'),
                    'HTTP_HOST' => $request->server('HTTP_HOST'),
                    'HTTP_REFERER' => $request->server('HTTP_REFERER'),
                    'HTTP_ACCEPT_LANGUAGE' => $request->server('HTTP_ACCEPT_LANGUAGE'),
                    'HTTP_ACCEPT_ENCODING' => $request->server('HTTP_ACCEPT_ENCODING'),
                    'REQUEST_METHOD' => $request->server('REQUEST_METHOD'),
                    'REQUEST_URI' => $request->server('REQUEST_URI'),
                    'QUERY_STRING' => $request->server('QUERY_STRING'),
                ],
            ]);
        }
        
        return $next($request);
    }
}
