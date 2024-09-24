<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\GameRequest;
use App\Services\Api\GameService;

class GameController extends Controller
{
    public function __construct(
        protected GameService $service,
    ) {}

    public function levels(GameRequest $request): array
    {
        return $this->service->getLevels($request->get('game'));
    }
}
