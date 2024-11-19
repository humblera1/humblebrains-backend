<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\checkpoint\FinishCheckpointRequest;
use App\Http\Requests\Api\v1\checkpoint\FinishStageRequest;
use App\Http\Resources\Api\v1\CheckpointStageResource;
use App\Http\Resources\Api\v1\ProgramResource;
use App\Models\Category;
use App\Services\Api\CheckpointService;
use App\Services\Api\ProgramService;
use Illuminate\Support\Facades\Auth;

class CheckpointController extends Controller
{
    public function __construct(
        protected CheckpointService $service,
        protected ProgramService $programService,
    ) {}

    public function finishStage(FinishStageRequest $request)
    {
        return new CheckpointStageResource($this->service->saveStage($request->getDTO()));
    }

    public function finishCheckpoint(FinishCheckpointRequest $request)
    {
        $this->service->finishCheckpoint();
        $this->programService->generateProgram(Category::where('name', $request->post('category'))->value('id'));

        Auth::user()->loadProgramRelations();

        return new ProgramResource(Auth::user()->latestProgram);
    }
}
