<?php

namespace App\Models\Traits\Tests;

use App\Models\User;

trait WithAuthenticate
{
    public function authenticateUser(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }
}
