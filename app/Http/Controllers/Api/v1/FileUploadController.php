<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\file\FileUploadRequest;

class FileUploadController extends Controller
{
    public function validateFile(FileUploadRequest $request): array
    {
        return $this->formatResponse(['success' => 'File is valid']);
    }
}
