<?php

namespace Services\Api;

use App\Models\Category;
use App\Models\Game;
use App\Models\Program;
use App\Models\ProgramSession;
use App\Models\SessionGame;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\User;
use App\Services\Api\ProgramService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramServiceTest extends TestCase
{
    use RefreshDatabase, WithAuthenticate;

    protected User $user;

    protected ProgramService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
        $this->service = new ProgramService();
    }

    /**
     * Testing game creation for existing sessions.
     *
     * @return void
     */
    public function test_insert_games_for_each_session()
    {
        $category = Category::factory()->create();
        $program = Program::factory()->for($this->user)->create(['priority_category_id' => $category->id]);
        $sessions = ProgramSession::factory()->for($program)->count(3)->create();

        Game::factory()->count(5)->for($category)->create();

        $this->service->insertSessionGamesForProvidedProgram($program);

        $this->assertCount(5, SessionGame::where('session_id', $sessions->random()->id)->get());
        $this->assertDatabaseCount('session_games', 15);
    }

    /**
     * Testing sessions & games creation for existing program.
     *
     * @return void
     */
    public function test_insert_sessions_for_program()
    {
        $category = Category::factory()->create();
        $program = Program::factory()->for($this->user)->create(['priority_category_id' => $category->id]);

        Game::factory()->count(5)->for($category)->create();

        $programService = new ProgramService();
        $programService->insertSessionsForProvidedProgram($program);

        $this->assertCount(3, ProgramSession::where('program_id', $program->id)->get());

        $this->assertDatabaseHas( 'program_sessions', [
            'program_id' => $program->id,
        ]);

        $this->assertDatabaseCount('program_sessions', 3);
        $this->assertDatabaseCount('session_games', 15);
    }

    /**
     * Testing program creation.
     *
     * @return void
     */
    public function test_generate_program_with_correct_user_and_category_id()
    {

        $category = Category::factory()->create();
        Game::factory()->count(5)->for($category)->create();

        $this->service->generateProgram($category->id);

        $this->assertDatabaseHas('programs', [
            'user_id' => $this->user->id,
            'priority_category_id' => $category->id,
        ]);

        $this->assertDatabaseCount('programs', 1);
        $this->assertDatabaseCount('program_sessions', 3);
        $this->assertDatabaseCount('session_games', 15);
    }

    /**
     * Testing games creation for a session when there are no games of the required category in the database.
     *
     * @return void
     */
    public function test_generate_games_when_no_games_with_provided_category_exists()
    {
        $categories = Category::factory(2)->create();

        $firstCategory = $categories->first();
        $lastCategory = $categories->last();

        Game::factory()->count(5)->for($firstCategory)->create();

        $this->service->generateProgram($lastCategory->id);

        $this->assertDatabaseHas('programs', [
            'user_id' => $this->user->id,
            'priority_category_id' => $lastCategory->id,
        ]);

        $this->assertDatabaseCount('session_games', 15);
    }
}
