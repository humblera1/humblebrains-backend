<?php

namespace Api\Word;

use Tests\TestCase;

class WordsTest extends TestCase
{
    public function test_words_endpoint_returns_correct_number_of_words()
    {
        $response5 = $this->getJson(route('api.v1.words.get-words', ['amount' => 5]));
        $response55 = $this->getJson(route('api.v1.words.get-words', ['amount' => 55]));
        $response105 = $this->getJson(route('api.v1.words.get-words', ['amount' => 105]));

        $response5->assertJsonCount(5);
        $response55->assertJsonCount(55);
        $response105->assertJsonCount(105);
    }
}
