<?php

namespace Api\Game;

use App\Enums\PeriodEnum;
use App\Models\Game;
use App\Models\History;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\Traits\Tests\WithHelpers;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Auth;

class GameStatisticsTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate, WithHelpers;

    protected User $user;

    protected Game $game;

    protected string $endpoint = 'api.v1.games.statistics';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
        $this->game = Game::factory()->forCategory()->create();
    }

    /**
     * Test that the statistics endpoint returns a 200 OK status.
     */
    public function test_statistics_endpoint_returns_200()
    {
        $response = $this->getJson(route($this->endpoint, ['game' => $this->game->name]));

        $response->assertOk();
    }

    /**
     * Test that an unauthorized user cannot access the statistics endpoint.
     */
    public function test_unauthorized_user_cannot_access_statistics()
    {
        Auth::logout();

        $response = $this->getJson(route($this->endpoint, ['game' => $this->game->name]));

        $response->assertUnauthorized();
    }

    /**
     * Test that the statistics endpoint returns all types of statistics.
     */
    public function test_statistics_endpoint_returns_all_types_of_statistics()
    {
        $response = $this->getJson(route($this->endpoint, ['game' => $this->game->name]));

        $response->assertOk()
            ->assertJsonStructure(['data' => [
            'games',
            'scores',
            'accuracy',
        ]]);
    }

    /**
     * Test that the statistics endpoint returns the requested type of statistics.
     */
    public function test_statistics_endpoint_returns_requested_type_of_statistics()
    {
        $this->createPropertiesForGame($this->game);

        $this->finishGame();

        $response = $this->getJson(route($this->endpoint, [
            'game' => $this->game->name,
            'type' => 'accuracy',
        ]));

        $response->assertOk();

        $responseArray = $response->json();

        $this->assertNotEmpty($responseArray['data']['accuracy']);
        $this->assertEmpty($responseArray['data']['scores']);
    }

    /**
     * Test that the statistics endpoint returns statistics for the requested period.
     */
    public function test_statistics_endpoint_returns_statistics_for_requested_period()
    {
        $this->createPropertiesForGame($this->game);

        History::factory()
            ->for($this->game)
            ->for($this->user)
            ->create([
                'played_at' => date('Y-m-d H:i:s', strtotime('last month'))
            ]);

        $weekResponse = $this->getJson(route($this->endpoint, [
            'game' => $this->game->name,
            'period' => PeriodEnum::Week->value,
        ]));

        $yearResponse = $this->getJson(route($this->endpoint, [
            'game' => $this->game->name,
            'period' => PeriodEnum::Year->value,
        ]));

        $weekResponse->assertOk();
        $yearResponse->assertOk();

        $weekResponseArray = $weekResponse->json();
        $yearResponseArray = $yearResponse->json();

        $this->assertEmpty($weekResponseArray['data']['games']);
        $this->assertNotEmpty($yearResponseArray['data']['games']);
    }

    /**
     * Test that the statistics endpoint returns the correct amount of records.
     */
    public function test_statistics_endpoint_returns_correct_amount_of_records()
    {
        $times = 5;

        $this->createPropertiesForGame($this->game);

        for ($i = 0; $i < 5; $i++) {
            $this->finishGame();
        }

        $response = $this->getJson(route($this->endpoint, ['game' => $this->game->name]));

        $response->assertOk();

        $responseArray = $response->json();

        $this->assertCount($times, $responseArray['data']['games']);
    }

}
