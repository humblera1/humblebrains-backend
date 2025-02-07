<?php

namespace Api\Game;

use App\Models\Game;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GamesTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate;

    protected User $user;

    protected Game $game;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->authenticateUser();
        $this->game = Game::factory()->forCategory()->create();
    }

    public function test_index_endpoint_returns_200()
    {
        Auth::logout();

        $response = $this->getJson(route('api.v1.games.index'));

        $response->assertStatus(200);
    }
}
