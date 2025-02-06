<?php

namespace Api\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that a user can register successfully.
     */
    public function test_user_can_register()
    {
        $response = $this->postJson(route('api.v1.auth.register'), [
            'email' => 'test@example.com',
            'password' => 'password',
            'username' => 'testuser',
            'name' => 'Test User',
        ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'isAnonymous',
                    'personalData' => [
                        'name',
                        'username',
                        'email',
                        'isEmailVerified',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'is_anonymous' => false,
        ]);
    }
}
