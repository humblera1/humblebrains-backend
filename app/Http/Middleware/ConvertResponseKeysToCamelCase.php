<?php

namespace App\Http\Middleware;

use App\Services\Api\CaseService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertResponseKeysToCamelCase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $response->setData(
                app(CaseService::class)->convertKeysToCamelCase(
                    json_decode($response->content(), true)
                )
            );
        }

        return $response;
    }
}
