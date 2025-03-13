<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class VoyatekMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (HttpResponse) $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if ($request->header('voyatek-header') != config('voyatek.header')) {
            return response()->json([
                'message' => 'Sorry you are not allowed to carry out this operation',
                'success' => false
            ], HttpResponse::HTTP_NOT_ACCEPTABLE);
        }
        return $next($request);
    }
}
