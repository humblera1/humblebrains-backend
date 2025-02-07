<?php

namespace Api\Game;

use App\Models\Game;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\Traits\Tests\WithHelpers;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GameAchievementsTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate, WithHelpers;

    protected User $user;

    protected Game $game;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
        $this->game = Game::factory()->forCategory()->create();

        $this->createPropertiesForGame($this->game);

    }

    /**
     * Test that the achievements endpoint returns a 200 status code.
     */
    public function test_achievements_endpoint_returns_200()
    {
        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertStatus(200);
    }

    /**
     * Test that the response contains the "games-played" achievement type.
     */
    public function test_check_basic_achievements()
    {
        $this->finishGame();

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        // Assert that the response contains a "type":"games-played" entry
        $response->assertOk()
            ->assertJsonFragment(['type' => 'games-played']);
    }

    /**
     * Test that the "new-level-unlocked" achievement is missing when no new level is unlocked.
     */
    public function test_new_level_unlocked_achievement_is_missing()
    {
        // first play
        $this->finishGame(['max_unlocked_level' => 1]);

        // second play WITHOUT new level unlocking
        $this->finishGame(['max_unlocked_level' => 1]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonMissing(['type' => 'new-level-unlocked']);
    }

    /**
     * Test that the "new-level-unlocked" achievement is present when a new level is unlocked.
     */
    public function test_new_level_unlocked_achievement()
    {
        // first play
        $this->finishGame(['max_unlocked_level' => 1]);

        // second play WITHOUT new level unlocking
        $this->finishGame(['max_unlocked_level' => 2]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonFragment(['type' => 'new-level-unlocked']);
    }

    /**
     * Test that the "new-record" achievement is missing when no new record is set.
     */
    public function test_new_record_achievement_is_missing()
    {
        // first play
        $this->finishGame(['score' => 98]);

        // second play WITHOUT new record
        $this->finishGame(['score' => 98]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonMissing(['type' => 'new-record']);
    }

    /**
     * Test that the "new-record" achievement is present when a new record is set.
     */
    public function test_new_record_achievement()
    {
        // first play
        $this->finishGame(['score' => 98]);

        // second play WITH new record
        $this->finishGame(['score' => 99]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonFragment(['type' => 'new-record']);
    }

    /**
     * Test that the "no-mistakes" achievement is missing when mistakes are made.
     */
    public function test_no_mistakes_achievement_is_missing()
    {
        $this->finishGame(['accuracy' => number_format(1.0, 1)]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonMissing(['type' => 'no-mistakes']);
    }

    /**
     * Test that the "no-mistakes" achievement is present when no mistakes are made.
     */
    public function test_no_mistakes_achievement()
    {
        $this->finishGame(['accuracy' => number_format(100.0, 1)]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonFragment(['type' => 'no-mistakes']);
    }

    /**
     * Test that the "target-completed" achievement is missing when the target is not completed.
     */
    public function test_target_completed_achievement_is_missing()
    {
        $this->finishGame(['is_target_completed' => false]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonMissing(['type' => 'target-completed']);
    }

    /**
     * Test that the "target-completed" achievement is present when the target is completed.
     */
    public function test_target_completed_achievement()
    {
        $this->finishGame(['is_target_completed' => true]);

        $response = $this->getJson(route('api.v1.games.achievements', ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonFragment(['type' => 'target-completed']);
    }

    /**
     * Helper method to simulate finishing a game.
     */
    protected function finishGame(array $overrides = [])
    {

        $data = array_merge([
            'game' => $this->game->name,
            'score' => 1,
            'started_from_level' => 1,
            'finished_at_level' => 1,
            'max_unlocked_level' => 1,
            'within_session' => true,
            'mean_reaction_time' => 1.23,
            'accuracy' => number_format(1.0, 1),
            'correct_answers_amount' => 1,
            'is_target_completed' => false,
        ], $overrides);

        $this->postJson(route('api.v1.games.finish-game'), $data)->assertOk();
    }
}
