<?php

namespace App\Listeners\history;

use App\Events\HistorySaved;
use App\Events\ProgramCompleted;

class UpdateProgram
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HistorySaved $event): void
    {
        $history = $event->history;
        $user = \Auth::user();

        if ($history->within_session) {
            $user->loadProgramRelations();

            $program = $user->latestProgram;

            if (!$program) {
                return; // no uncompleted programs
            }

            // get first uncompleted session of the program
            $session = $program->sessions
                ->sortBy('id')
                ->where('is_completed', false)
                ->first();

            if (!$session) {
                return; // no uncompleted sessions
            }

            // get first uncompleted game of the session
            $game = $session->games
                ->sortBy('id')
                ->where('played_game_id', null)
                ->first();

            if (!$game) {
                return; // no uncompleted games
            }

            $game->game_id = $history->game_id; // If user played another game within session
            $game->played_game_id = $history->id;

            // complete the session if all games of this session completed
            if ($session->games->every(fn($game) => $game->played_game_id !== null)) {
                $session->is_completed = true;
            }

            // create checkpoint if all sessions of this program completed
            if ($program->sessions->every(fn($session) => $session->is_completed)) {
                event(new ProgramCompleted());
            }

            $program->push();
        }
    }
}
