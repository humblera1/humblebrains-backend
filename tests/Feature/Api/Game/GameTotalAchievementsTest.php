<?php

namespace Api\Game;

use App\Models\Game;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\Traits\Tests\WithHelpers;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GameTotalAchievementsTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate, WithHelpers;

    protected User $user;

    protected Game $game;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
        $this->game = Game::factory()->forCategory()->create();
    }

    /**
     * Test that the total achievements endpoint returns a 200 OK status.
     */
    public function test_total_achievements_endpoint_returns_200()
    {
        $response = $this->getJson(route('api.v1.games.total-achievements', ['game' => $this->game->name]));

        $response->assertOk();
    }

    /**
     * Test that an unauthorized user cannot access the total achievements endpoint.
     */
    public function test_unauthorized_user_cannot_access_total_achievements()
    {
        Auth::logout();

        $response = $this->getJson(route('api.v1.games.total-achievements', ['game' => $this->game->name]));

        $response->assertUnauthorized();
    }

    /**
     * Test that basic total achievements are returned.
     */
    public function test_basic_total_achievements_are_returned()
    {
        $this->createPropertiesForGame($this->game);

        $this->finishGame();

        $response = $this->getJson(route('api.v1.games.total-achievements', ['game' => $this->game->name]));

        $response->assertOk();

        $response->assertJsonFragment(['type' => 'games-played']);
        $response->assertJsonFragment(['type' => 'opened-level']);
    }

    /**
     * Test that the correct number of games played is returned in total achievements.
     */
    public function test_correct_games_played_total_achievement_returned()
    {
        $times = 3;

        $this->createPropertiesForGame($this->game);

        for ($i = 0; $i < $times; $i++) {
            $this->finishGame();
        }

        $response = $this->getJson(route('api.v1.games.total-achievements', ['game' => $this->game->name]));

        $response->assertOk();

        $dataArray = $response->json()['data'];

        // Extract the 'content' for 'type' => 'games-played'
        $gamesPlayedContent = null;
        foreach ($dataArray as $item) {
            if ($item['type'] === 'games-played') {
                $gamesPlayedContent = $item['content'];
                break;
            }
        }

        // Extract the integer from the content string
        preg_match('/\d+/', $gamesPlayedContent, $matches);
        $extractedNumber = (int)$matches[0];

        // Assert that the content is as expected
        $this->assertEquals($times, $extractedNumber);
    }

    /**
     * Test that the correct number of opened levels is returned in total achievements.
     */
    public function test_correct_opened_level_total_achievement_returned()
    {
        $maxUnlockedLevel = 10;

        $this->createPropertiesForGame($this->game);

        $this->finishGame(['max_unlocked_level' => $maxUnlockedLevel]);

        $response = $this->getJson(route('api.v1.games.total-achievements', ['game' => $this->game->name]));

        $response->assertOk();

        $dataArray = $response->json()['data'];

        // Extract the 'content' for 'type' => 'opened-level'
        $gamesPlayedContent = null;
        foreach ($dataArray as $item) {
            if ($item['type'] === 'opened-level') {
                $gamesPlayedContent = $item['content'];
                break;
            }
        }

        // Extract the integer from the content string
        preg_match('/\d+/', $gamesPlayedContent, $matches);
        $extractedNumber = (int)$matches[0];

        // Assert that the content is as expected
        $this->assertEquals($maxUnlockedLevel, $extractedNumber);
    }
}
