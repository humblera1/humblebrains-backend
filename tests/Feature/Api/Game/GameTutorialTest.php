<?php

namespace Api\Game;

use App\Models\Game;
use App\Models\GameTutorial;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GameTutorialTest extends TestCase
{
    use DatabaseTransactions;

    public function test_tutorial_endpoint_returns_200()
    {
        Auth::logout();

        $game = Game::factory()->forCategory()->create();
        GameTutorial::factory()->for($game)->create();

        $response = $this->getJson(route('api.v1.games.tutorial', ['game' => $game->name]));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'game',
                'tutorial' => [
                    'content',
                ],
            ],
        ]);
    }
}
