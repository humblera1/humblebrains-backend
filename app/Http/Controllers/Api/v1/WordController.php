<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\RandomAmountRequest;
use App\Services\Api\WordsService;

class WordController extends Controller
{
    public function __construct(
        protected WordsService $service,
    ) {}

    public function getWords(RandomAmountRequest $request): array
    {
        return $this->service->getRandomWords($request->get('amount'));
    }
}
