<?php

namespace Api\User;

use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
    }

    /**
     * Test setting a user's avatar.
     * Verifies the file upload and storage.
     */
    public function test_set_avatar()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpeg', 100, 100)->size(100);

        $response = $this->postJson(route('api.v1.users.set-avatar'), [
            'file' => $file,
        ]);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $this->user->id,
                    'isAnonymous' => true,
                ],
            ]);

        // Check if file was stored
        Storage::disk('public')->assertExists($this->user->avatar);

        $this->assertNotEmpty($this->user->fresh()->avatar);
    }

    /**
     * Test updating user details.
     * Ensures name and email are updated correctly.
     */
    public function test_update_user()
    {
        $this->user->is_anonymous = false;
        $this->user->email_verified_at = now();

        $this->user->save();

        $newData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $response = $this->putJson(route('api.v1.users.update'), $newData);

        $response->assertOk()
            ->assertJsonPath('data.personalData.name', 'Updated Name')
            ->assertJsonPath('data.personalData.email', 'updated@example.com');

        $this->user->refresh();
        $this->assertEquals('Updated Name', $this->user->name);
        $this->assertEquals('updated@example.com', $this->user->email);

        $this->assertNull($this->user->email_verified_at);
    }

    /**
     * Test sending email verification notification.
     * Confirms notification is sent once.
     */
    public function test_send_email_verification_notification()
    {
        Notification::fake();

        $response = $this->postJson(route('api.v1.users.send-email-verification-notification'));

        $response->assertNoContent();

        // Assert that the notification was sent to the user once
        Notification::assertSentToTimes($this->user, VerifyEmailNotification::class, 1);
    }

    /**
     * Test email verification process.
     * Validates email is marked as verified.
     */
    public function testVerifyEmail()
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        // Generate a signed URL for email verification
        $signedUrl = URL::signedRoute('api.v1.verification.verify', [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $response = $this->getJson($signedUrl);

        $response->assertOk();

        // Assert that the user's email is now verified
        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
