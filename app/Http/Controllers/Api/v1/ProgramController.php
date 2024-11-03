<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\program\CreateProgramRequest;
use App\Services\Api\ProgramService;

class ProgramController extends Controller
{
    public function __construct(
        protected ProgramService $service,
    ) {}

    public function create(CreateProgramRequest $request)
    {
        $this->service->generateProgram($request->get('categoryId'));
    }
}
