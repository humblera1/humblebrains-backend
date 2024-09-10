<?php

namespace App\Http\Middleware;

use App\Services\Api\CaseService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertRequestKeysToSnakeCase
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->replace(app(CaseService::class)->convertKeysToSnakeCase($request->all()));

        return $next($request);
    }
}
