<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleMiddleware
{
    const DEFAULT_LOCALE = 'en';

    const ALLOWED_LOCALES = [
        'ru',
        'en',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Получаем значение заголовка 'X-App-Locale'
        $locale = $request->header('X-App-Locale', self::DEFAULT_LOCALE);

        // Если заголовок передан и значение разрешено, устанавливаем локаль приложения.
        if ($locale && in_array($locale, self::ALLOWED_LOCALES)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
