<?php

namespace App\Interfaces\Request;

use App\Entities\DTOs\BaseDTO;

interface RequestDTOInterface
{
    public function getDTO(): BaseDTO;
}
