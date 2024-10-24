<?php

namespace App\Services\Api;

use App\Models\Icon;
use Illuminate\Support\Facades\Storage;

class IconService
{
    public function getRandomIconUrls(int $amount): array
    {
        $icons = Icon::inRandomOrder()->take($amount)->get();

        return $icons->map(function ($icon) {
            return Storage::url($icon->path);
        });
    }
}
