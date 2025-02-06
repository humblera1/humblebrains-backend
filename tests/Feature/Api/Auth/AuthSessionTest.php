<?php

namespace Api\Auth;

use App\Models\Traits\Tests\WithAuthenticate;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthSessionTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate;

    /**
     * Test that the 'me' method returns the authenticated user's data.
     *
     * This method verifies that when a user is authenticated,
     * the 'me' method correctly returns the user's information.
     */
    public function test_me_method_returns_authenticated_user()
    {
        // Create a user and authenticate
        $user = $this->authenticateUser();
        $user->is_anonymous = false;

        // Call the 'me' method
        $response = $this->postJson(route('api.v1.users.me'));

        // Assert the response contains the authenticated user's data
        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'isAnonymous' => false,
                ],
            ]);
    }

    /**
     * Test that the 'me' method registers and returns an anonymous user.
     *
     * This method ensures that if no user is authenticated,
     * the 'me' method registers a new anonymous user and returns their information.
     */
    public function test_me_method_registers_and_returns_anonymous_user()
    {
        // Ensure no user is authenticated
        Auth::logout();

        // Call the 'me' method
        $response = $this->postJson(route('api.v1.users.me'));

        // Assert the response contains an anonymous user's data
        $response->assertCreated()
            ->assertJson([
            'data' => [
                'isAnonymous' => true,
            ],
        ]);

        // Assert a new user was created in the database
        $this->assertDatabaseHas('users', [
            'is_anonymous' => true,
        ]);
    }
}
