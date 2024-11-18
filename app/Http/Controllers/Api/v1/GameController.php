<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\game\FinishGameRequest;
use App\Http\Requests\Api\v1\game\GamesListRequest;
use App\Http\Requests\StatisticsRequest;
use App\Http\Resources\Api\v1\GameDetailResource;
use App\Http\Resources\Api\v1\GamePreviewResource;
use App\Http\Resources\Api\v1\GameTutorialResource;
use App\Http\Resources\Api\v1\UserSessionProgramResource;
use App\Models\Game;
use App\Services\Api\AchievementService;
use App\Services\Api\GameService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GameController extends Controller
{
    public function __construct(
        protected GameService $service,
    ) {}

    public function show(Game $game): GameDetailResource
    {
        return new GameDetailResource($game->loadUserStatistics());
    }

    public function index(GamesListRequest $request): AnonymousResourceCollection
    {
        return GamePreviewResource::collection($this->service->getGamesList($request->get('category_ids')));
    }

    public function totalAchievements(Game $game): array
    {
        return $this->formatResponse(app(AchievementService::class, ['game' => $game])->getTotalAchievements());
    }

    public function achievements(Game $game): array
    {
        return $this->formatResponse(app(AchievementService::class, ['game' => $game])->getAchievements());
    }

    public function statistics(StatisticsRequest $request, Game $game): array
    {
        return $this->formatResponse($this->service->getUserStatistics($game, $request->getType(), $request->getPeriod()));
    }

    public function tutorial(Game $game): GameTutorialResource
    {
        return GameTutorialResource::make($game);
    }

    public function levels(Game $game): array
    {
        return $this->formatResponse($this->service->getGameLevelsForCurrentUser($game));
    }

    public function finishGame(FinishGameRequest $request)
    {
        $this->service->saveGame($request->getDTO());

        return UserSessionProgramResource::make(\Auth::user());
    }
}
