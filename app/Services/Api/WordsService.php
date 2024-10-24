<?php

namespace App\Services\Api;

use App\Models\Word;
use Illuminate\Support\Facades\App;

class WordsService
{
    public function getRandomWords(int $amount): array
    {
        $words = Word::inRandomOrder()->take($amount)->get();

        return $words->map(function (Word $word) {
            return $word->word;
        })->toArray();
    }
}
