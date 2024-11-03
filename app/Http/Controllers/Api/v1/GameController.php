<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\game\FinishGameRequest;
use App\Http\Requests\Api\v1\GameRequest;
use App\Http\Resources\Api\v1\GameDetailResource;
use App\Models\Game;
use App\Services\Api\GameService;

class GameController extends Controller
{
    public function __construct(
        protected GameService $service,
    ) {}

    public function show(Game $game): GameDetailResource
    {
        return new GameDetailResource($game);
    }

    public function levels(GameRequest $request): array
    {
        return $this->service->getLevels($request->get('game'));
    }

    public function finishGame(FinishGameRequest $request)
    {
        $this->service->saveGame($request->getDTO());
    }
}
