<?php

namespace Api\Icon;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IconsTest extends TestCase
{
    public function test_icon_endpoint_returns_correct_number_of_icons()
    {
        $numberOfIcons = 10;

        $response = $this->getJson(route('api.v1.icons.get-icons', ['amount' => $numberOfIcons]));

        $response->assertOk();

        $response->assertJsonCount($numberOfIcons);

        foreach ($response->json() as $icon) {
            $this->assertArrayHasKey('src', $icon);
            $this->assertArrayHasKey('name', $icon);

            // Check if the icon URI is accessible
//            $iconResponse = $this->get($icon['src']);
//            $iconResponse->assertOk();
        }
    }
}
