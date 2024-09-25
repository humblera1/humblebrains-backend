<?php

namespace App\Services\Api;

use App\Enums\TypeEnum;

final class TypeService
{
    public function convertStringToType(string &$string, string $type): void
    {
        $string = match ($type) {
            TypeEnum::Boolean->value => $this->convertStringToBoolean($string),
            TypeEnum::Integer->value => $this->convertStringToInteger($string),
            TypeEnum::Float->value => $this->convertStringToFloat($string),
            TypeEnum::Array->value => $this->convertStringToArray($string),
            default => $string,
        };
    }

    public function convertStringToBoolean(string $string): bool
    {
        return match (strtolower($string)) {
            "true", "1", "yes", "on" => true,
            default => false,
        };
    }

    public function convertStringToInteger(string $string): int
    {
        return (int) $string;
    }

    public function convertStringToFloat(string $string): float
    {
        return (float) $string;
    }

    public function convertStringToArray(string $string): array
    {
        if (json_validate($string)) {
            return json_decode($string, true);
        }

        // todo: поведение convertStringToArray
        return explode('', $string);
    }
}
