<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AccessLog
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $user = $request->user();
            Log::info('Access', [
                'route' => optional($request->route())->getName(),
                'path' => $request->path(),
                'method' => $request->method(),
                'status' => $response->getStatusCode(),
                'user_id' => $user?->id,
                'roles' => $user?->getRoleNames()?->toArray(),
                'ip' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            // avoid breaking request on logging error
        }

        return $response;
    }
}

