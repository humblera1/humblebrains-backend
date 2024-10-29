<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\checkpoint\SaveStageRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Services\Api\CheckpointService;
use Illuminate\Support\Facades\Auth;

class CheckpointController extends Controller
{
    public function __construct(
        protected CheckpointService $service,
    ) {}

    public function finishStage(SaveStageRequest $request): UserResource
    {
        $user = Auth::user();
        $user->loadLatestCheckpointRelations();

        $this->service->saveStage($request->validated());

        return new UserResource($user);
    }
}
