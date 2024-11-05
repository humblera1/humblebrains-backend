<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\game\FinishGameRequest;
use App\Http\Requests\Api\v1\game\GamesListRequest;
use App\Http\Requests\Api\v1\GameRequest;
use App\Http\Resources\Api\v1\GameDetailResource;
use App\Http\Resources\Api\v1\GamePreviewResource;
use App\Models\Game;
use App\Services\Api\AchievementService;
use App\Services\Api\GameService;

class GameController extends Controller
{
    public function __construct(
        protected GameService $service,
    ) {}

    public function show(Game $game): GameDetailResource
    {
        return new GameDetailResource($game->loadUserStatistics());
    }

    public function index(GamesListRequest $request)
    {
        return GamePreviewResource::collection($this->service->getGamesList($request->get('category_id')));
    }

    public function totalAchievements(Game $game): array
    {
        return [
            'achievements' => app(AchievementService::class, ['game' => $game])->getTotalAchievements(),
        ];
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
