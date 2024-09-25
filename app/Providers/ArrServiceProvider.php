<?php

namespace App\Providers;

use App\Services\Api\TypeService;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class ArrServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Arr::macro('pluckWithCast', static function (iterable $array, string|int|array|null $value, string|null $key, string $type): array {
            $results = [];

            [$value, $key] = static::explodePluckParameters($value, $key);

            foreach ($array as $item) {
                $itemValue = data_get($item, $value);
                $itemType = data_get($item, $type);

                app(TypeService::class)->convertStringToType($itemValue, $itemType);

                if (is_null($key)) {
                    $results[] = $itemValue;
                } else {
                    $itemKey = data_get($item, $key);

                    if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                        $itemKey = (string) $itemKey;
                    }

                    $results[$itemKey] = $itemValue;
                }
            }

            return $results;
        });
    }
}
