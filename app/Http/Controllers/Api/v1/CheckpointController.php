<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\checkpoint\SaveStageRequest;
use App\Services\Api\CheckpointService;

class CheckpointController extends Controller
{
    public function __construct(
        protected CheckpointService $service,
    ) {}

    public function finishStage(SaveStageRequest $request): void
    {
        $this->service->saveStage($request->validated());
    }
}
