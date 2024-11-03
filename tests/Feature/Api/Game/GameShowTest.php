<?php

namespace Tests\Feature\Api\Game;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class GameShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that a game with a specific name can be retrieved successfully.
     * This test ensures that the API returns a 200 OK status when a game exists.
     */
    public function test_basic()
    {
        Game::factory()->forCategory()->create(['name' => 'test']);

        $response = $this->getJson(route('api.v1.games.show', ['game' => 'test']));

        $response->assertOk();
    }

    /**
     * Test that a 404 Not Found response is returned when a game does not exist.
     * This test ensures that the API correctly handles requests for non-existent games.
     */
    public function test_404_response()
    {
        $response = $this->getJson(route('api.v1.games.show', ['game' => 'test']));

        $response->assertNotFound();
    }

    /**
     * Test that a game with a category, tutorial, and tags is returned correctly.
     * This test checks that the API response includes the expected structure and non-empty data for category, tutorial, and tags.
     */
     public function test_show_game_with_category_tutorial_and_tags()
     {
         Game::factory()->forCategory()->hasTutorial()->hasTags()->create(['name' => 'test']);

         $response = $this->getJson(route('api.v1.games.show', ['game' => 'test']));

         $response
             ->assertOk()
             ->assertJsonStructure([
                 'data' => [
                     'category',
                     'tutorial',
                     'tags',
                 ],
             ])
             ->assertJson(function (AssertableJson $json) {
                 $json->where('data.category', function ($category) {
                     return $category->isNotEmpty();
                 })
                     ->where('data.tutorial', function ($tutorial) {
                         return $tutorial->isNotEmpty();
                     })
                     ->where('data.tags', function ($tags) {
                         return $tags->isNotEmpty();
                     });
             });
     }

    /**
     * Test that a game with a category but no tutorial and no tags is returned correctly.
     * This test checks that the API response includes the expected structure and that the tutorial is null and tags are empty.
     */
    public function test_show_game_with_category_no_tutorial_and_no_tags()
    {
        Game::factory()->forCategory()->create(['name' => 'test']);

        $response = $this->getJson(route('api.v1.games.show', ['game' => 'test']));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'category',
                    'tutorial',
                    'tags',
                ],
            ])
            ->assertJson(function (AssertableJson $json) {
                $json->where('data.category', function ($category) {
                    return $category->isNotEmpty();
                })
                    ->where('data.tutorial', function ($tutorial) {
                        return is_null($tutorial);
                    })
                    ->where('data.tags', function ($tags) {
                        return $tags->isEmpty();
                    });
            });
    }
}
