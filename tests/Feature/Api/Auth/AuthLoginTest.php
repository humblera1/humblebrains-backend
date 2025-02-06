<?php

namespace Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that a user can log in successfully.
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_anonymous' => false,
        ]);

        $response = $this
            ->postJson(route('api.v1.auth.login'), [
            'usermail' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
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

        $this->assertAuthenticatedAs($user);
    }


    /**
     * Test that login fails with invalid credentials.
     */
    public function test_login_fails_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson(route('api.v1.auth.login'), [
            'usermail' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['general']);
    }
}
