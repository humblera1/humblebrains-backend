<?php

namespace App\Entities\DTOs\user;

use App\Entities\DTOs\BaseDTO;

class ChangePasswordDTO extends BaseDTO
{
    public function __construct(
        public readonly string $currentPassword,
        public readonly string $newPassword,
    )
    {}
}
