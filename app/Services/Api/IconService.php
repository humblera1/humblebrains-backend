<?php

namespace App\Services\Api;

use App\Models\Icon;
use Illuminate\Support\Facades\Storage;

class IconService
{
    public function getRandomIconUrls(int $amount): array
    {
        $paths = Icon::inRandomOrder()->take($amount)->pluck('path');

        return $paths->map(function ($path) {
            return Storage::url($path);
        })->toArray();
    }
}
