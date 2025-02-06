<?php

namespace Api\Auth;

use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthPasswordTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate;

    protected User $user;

    /**
     * Test changing the user's password.
     * This test verifies that a user can successfully change their password
     * and that the new password is valid for authentication.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
    }

    public function test_change_password(): void
    {
        $this->user->email = 'new-email@example.com';
        $this->user->password = Hash::make('old-password');

        $this->user->save();

        $response = $this->postJson(route('api.v1.auth.change-password'), [
            'current_password' => 'old-password',
            'new_password' => 'new-password',
            'new_password_confirmation' => 'new-password',
        ]);

        $response->assertStatus(200);

        $this->assertTrue(auth('web')->validate(['email' => $this->user->email, 'password' => 'new-password']));
    }

    /**
     * Test sending a password reset link.
     * This test ensures that a password reset link can be sent to the user's email.
     */
    public function test_forgot_password(): void
    {
        $this->user->email = 'new-email@example.com';

        $this->user->save();

        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => $this->user->email])
            ->andReturn(Password::RESET_LINK_SENT);

        $response = $this->postJson(route('api.v1.auth.forgot-password'), [
            'email' => $this->user->email,
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test resetting the user's password.
     * This test verifies that a user can reset their password using a valid token
     * and that the new password is valid for authentication.
     */
    public function test_reset_password(): void
    {
        $user = $this->user;
        $user->email = 'new-email@example.com';

        $user->save();

        $token = Password::createToken($user);

        $response = $this->postJson(route('api.v1.auth.reset-password'), [
            'email' => $user->email,
            'token' => $token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertStatus(200);

        $this->assertTrue(auth('web')->validate(['email' => $user->email, 'password' => 'new-password']));
    }
}
