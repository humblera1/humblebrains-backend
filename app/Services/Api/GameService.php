<?php

namespace App\Services\Api;

use App\Entities\game\GameResultDTO;
use App\Models\GamesHistory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class GameService
{
    public function getLevels(string $gameName): array
    {
        return DB::table('games as g')
            ->join(
                'game_level_properties as glp',
                'g.id',
                '=',
                'glp.game_id'
            )
            ->join(
                'properties as p',
                'p.id',
                '=',
                'glp.property_id'
            )
            ->select('glp.value', 'level', 'p.name', 'p.type')
            ->where('g.name', $gameName)
            ->orderBy('level')
            ->get()
            ->groupBy('level')
            ->map(function (Collection $items) {
                return $items->pluckWithCast('type', 'value', 'name');
            })
            ->toArray();
    }

    /**
     * @param GameResultDTO $gameResulDTO
     * @return void
     */
    public function saveGame(GameResultDTO $gameResulDTO): void
    {
        $user = Auth::user();

        // сохранение в games_history и получение идентификатора
        $gameHistory = GamesHistory::create([
            'user_id' => $user->id,
            'game_id' => $gameResulDTO->gameId,
            'level' => $gameResulDTO->finishedAtTheLevel,
            'score' => $gameResulDTO->score,
        ]);

        $gameHistoryId = $gameHistory->id;

        // update max level
        if (true) {

        }

        if ($gameResulDTO->withinSession) {
            // загружаем связи: program.session.game
//            $user->load();

            // сохранение в session_games

        }

    }

    public function generateAchievementsForLastGame()
    {

    }
}
