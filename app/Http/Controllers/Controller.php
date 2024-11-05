<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function formatResponse($data): array
    {
        return [
            'data' => $data,
        ];
    }
}
