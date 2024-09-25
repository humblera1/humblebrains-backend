<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class CollectionServiceProvider extends ServiceProvider
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
        Collection::macro('pluckWithCast', function (string $type, string|int|array|null $value, string|null $key = null) {
            return new Collection(Arr::pluckWithCast($this->items, $value, $key, $type));
        });
    }
}
