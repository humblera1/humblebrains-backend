<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\RandomAmountRequest;
use App\Services\Api\IconService;

class IconController extends Controller
{
    public function __construct(
        protected IconService $service,
    ) {}

    public function getIcons(RandomAmountRequest $request): array
    {
        return $this->service->getRandomIconUrls($request->get('amount'));
    }
}
