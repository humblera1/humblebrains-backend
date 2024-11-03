<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\checkpoint\FinishStageRequest;
use App\Http\Resources\Api\v1\CheckpointStageResource;
use App\Http\Resources\Api\v1\UserResource;
use App\Services\Api\CheckpointService;
use Illuminate\Support\Facades\Auth;

class CheckpointController extends Controller
{
    public function __construct(
        protected CheckpointService $service,
    ) {}

    public function finishStage(FinishStageRequest $request)
    {
        return new CheckpointStageResource($this->service->saveStage($request->getDTO()));
    }
}
