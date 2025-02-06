<?php

namespace Api\Category;

use App\Enums\Game\CategoryEnum;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    public function test_index_returns_all_categories()
    {
        $expectedCategories = array_map(fn($category) => [
            'name' => $category->value,
            'label' => $category->name,
        ], CategoryEnum::cases());

        $response = $this->getJson(route('api.v1.categories.index'));

        $response->assertStatus(200);

        $response->assertJsonCount(count($expectedCategories), 'data');

        foreach ($expectedCategories as $expectedCategory) {
            $response->assertJsonFragment($expectedCategory);
        }
    }
}
