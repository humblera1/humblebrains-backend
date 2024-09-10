<?php

namespace App\Services\Api;

use Illuminate\Support\Str;
use InvalidArgumentException;

final class CaseService
{
    public const CASE_SNAKE = 'snake';
    public const CASE_CAMEL = 'camel';

    public const AVAILABLE_CASES = [
        self::CASE_SNAKE,
        self::CASE_CAMEL,
    ];

    /**
     * @param array $data
     * @return array
     */
    public function convertKeysToSnakeCase(array $data): array
    {
        return self::convertKeysCase(self::CASE_SNAKE, $data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function convertKeysToCamelCase(array $data): array
    {
        return self::convertKeysCase(self::CASE_CAMEL, $data);
    }

    /**
     * @param string $case One of the CASE_* constants
     * @param array $data
     * @return array
     */
    public function convertKeysCase(string $case, array $data): array
    {
        if (!in_array($case, self::AVAILABLE_CASES)) {
            throw new InvalidArgumentException('Available cases: ' . implode(', ', self::AVAILABLE_CASES));
        }

        $convertedData = [];

        foreach ($data as $key => $value) {
            $convertedData[Str::{$case}($key)] = is_array($value) ? self::convertKeysCase($case, $value) : $value;
        }

        return $convertedData;
    }
}
