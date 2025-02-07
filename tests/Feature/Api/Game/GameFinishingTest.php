<?php

namespace Api\Game;

use App\Models\Game;
use App\Models\History;
use App\Models\SessionGame;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\Traits\Tests\WithHelpers;
use App\Models\User;
use App\Services\Api\ProgramService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class GameFinishingTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate, WithHelpers;

    protected User $user;

    protected Game $game;

    protected ProgramService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
        $this->game = Game::factory()->forCategory()->create();

        $this->service = app(ProgramService::class);
    }

    /**
     * Test that finishing a game does not complete the session
     * when there are more games to be played in the session.
     */
    public function test_finishing_the_game_does_not_finish_session()
    {
        $this->createPropertiesForGame($this->game);

        // mock the configuration value
        Config::set('global.games_in_session_amount', 2);
        Config::set('global.sessions_in_program_amount', 2);

        // first, we create program for current user
        $this->service->generateProgram($this->game->category_id);

        // now we finish one game within session
        $this->finishGame(['withinSession' => true]);

        // check that played game successfully saved in history
        $record = History::firstWhere([
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'within_session' => true,
        ]);

        $this->assertNotEmpty($record);

        // check that played game successfully saved in session
        $sessionGame = SessionGame::firstWhere([
            'played_game_id' => $record->id,
        ]);

        $this->assertNotEmpty($sessionGame);

        // check that session is not completed
        $this->assertDatabaseHas('program_sessions', [
            'id' => $sessionGame->program_session_id,
            'is_completed' => false,
        ]);

        // check there is no checkpoints generated
        $this->assertDatabaseMissing('checkpoints', ['user_id' => $this->user->id]);
    }

    /**
     * Test that finishing the game in a session
     * completes the session when it's the only one game in the session.
     */
    public function test_finishing_only_game_in_session_finish_session()
    {
        $this->createPropertiesForGame($this->game);

        // mock the configuration value
        Config::set('global.games_in_session_amount', 1);
        Config::set('global.sessions_in_program_amount', 2);

        // first, we create program for current user
        $this->service->generateProgram($this->game->category_id);

        // now we finish one game within session
        $this->finishGame(['withinSession' => true]);

        // check that played game successfully saved in history
        $record = History::firstWhere([
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'within_session' => true,
        ]);

        $this->assertNotEmpty($record);

        // check that played game successfully saved in session
        $sessionGame = SessionGame::firstWhere([
            'played_game_id' => $record->id,
        ]);

        $this->assertNotEmpty($sessionGame);

        // check that session is completed
        $this->assertDatabaseHas('program_sessions', [
            'id' => $sessionGame->program_session_id,
            'is_completed' => true,
        ]);

        // check there is no checkpoints generated
        $this->assertDatabaseMissing('checkpoints', ['user_id' => $this->user->id]);
    }

    /**
     * Test that finishing the only game in a session
     * completes both the session and the program when
     * there is only one session in the program.
     */
    public function test_finishing_only_game_in_session_finish_program_with_one_session()
    {
        $this->createPropertiesForGame($this->game);

        // mock the configuration values
        Config::set('global.games_in_session_amount', 1);
        Config::set('global.sessions_in_program_amount', 1);

        // first, we create program for current user
        $this->service->generateProgram($this->game->category_id);

        // now we finish one game within session
        $this->finishGame(['withinSession' => true]);

        // check that played game successfully saved in history
        $record = History::firstWhere([
            'user_id' => $this->user->id,
            'game_id' => $this->game->id,
            'within_session' => true,
        ]);

        $this->assertNotEmpty($record);

        // check that played game successfully saved in session
        $sessionGame = SessionGame::firstWhere([
            'played_game_id' => $record->id,
        ]);

        $this->assertNotEmpty($sessionGame);

        $sessionGame->program_session_id;

        // check that session is completed
        $this->assertDatabaseHas('program_sessions', [
            'id' => $sessionGame->program_session_id,
            'is_completed' => true,
        ]);

        // check there is new checkpoints generated
        $this->assertDatabaseHas('checkpoints', ['user_id' => $this->user->id]);
    }
}
