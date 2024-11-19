<?php

namespace App\Services\Api;

use App\Models\Game;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgramService
{
    public function generateProgram(int $categoryId): void
    {
        DB::transaction(function () use ($categoryId) {
            $program = new Program([
                'user_id' => Auth::id(),
                'priority_category_id' => $categoryId,
            ]);

            $program->save();

            $this->insertSessionsForProvidedProgram($program);
        });
    }

    public function insertSessionsForProvidedProgram(Program $program): void
    {
        // todo: from global configs
        $sessionsInProgramAmount = 3;

        $sessions = [];

        for ($i = 0; $i < $sessionsInProgramAmount; $i++) {
            $sessions[] = [
                'program_id' => $program->id,
            ];
        }

        DB::table('program_sessions')->insert($sessions);

        $this->insertSessionGamesForProvidedProgram($program);
    }

    public function insertSessionGamesForProvidedProgram(Program $program): void
    {
        // todo: from global configs
        $gamesInSessionAmount = 5;

        $availableGameIds = Game::where('category_id', $program->priority_category_id)->pluck('id')->flip()->toArray();

        // there is no games by provided category
        if (empty($availableGameIds)) {
            $availableGameIds = Game::pluck('id')->flip()->toArray();
        }

        $sessionIds = $program->sessions()->pluck('id')->toArray();

        if (empty($sessionIds) || empty($availableGameIds)) {
            return; // no games or sessions
        }

        $sessionsAmount = count($sessionIds);

        $games = [];

        for ($i = 0; $i < $sessionsAmount; $i++) {
            for ($j = 0; $j < $gamesInSessionAmount; $j++) {
                $games[] = [
                    'program_session_id' => $sessionIds[$i],
                    'game_id' => array_rand($availableGameIds),
                ];
            }
        }

        DB::table('session_games')->insert($games);
    }
}
