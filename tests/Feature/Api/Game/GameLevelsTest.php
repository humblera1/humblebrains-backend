<?php

namespace Api\Game;

use App\Models\Game;
use App\Models\GameLevelProperty;
use App\Models\Property;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\Traits\Tests\WithHelpers;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GameLevelsTest extends TestCase
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
     * Test that the levels endpoint returns a 200 OK status.
     */
    public function test_levels_endpoint_returns_200()
    {
        $response = $this->getJson(route('api.v1.games.levels', ['game' => $this->game->name]));

        $response->assertOk();
    }

    /**
     * Test that the levels endpoint returns the correct number of levels.
     */
    public function test_correct_amount_of_levels_returned()
    {
        $amount = 10;

        $this->createPropertiesForGame($this->game);

        $response = $this->getJson(route('api.v1.games.levels', ['game' => $this->game->name]));

        $response->assertOk();

        $responseData = $response->json();

        // Assert that the number of levels matches the expected amount
        $this->assertCount($amount, $responseData['data']['levels']);
    }

    /**
     * Test that the levels endpoint returns the correct user levels.
     */
    public function test_user_levels()
    {
        $amount = 10;
        $finishedAtLevel = 5;
        $maxUnlockedLevel = 10;

        $property = Property::factory()->create();

        GameLevelProperty::factory()
            ->for($this->game)
            ->for($property)
            ->createMany($amount);

        $this->finishGame([
            'finished_at_level' => $finishedAtLevel,
            'max_unlocked_level' => $maxUnlockedLevel,
        ]);

        $response = $this->getJson(route('api.v1.games.levels', ['game' => $this->game->name]));

        $response->assertOk()->assertJson([
            'data' => [
                'lastUserLevel' => $finishedAtLevel,
                'maxUserLevel' => $maxUnlockedLevel,
            ],
        ]);
    }
}
