<?php

namespace Api\Checkpoint;

use App\Enums\Game\CategoryEnum;
use App\Models\Category;
use App\Models\Checkpoint;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CheckpointFinishTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
    }

    /**
     * Test that an unauthorized request returns a 401 status.
     * This ensures that the endpoint is protected and requires authentication.
     */
    public function test_unauthorized_request_returns_401()
    {
        Auth::logout();

        // Prepare the request data
        $requestData = [
            'category' => CategoryEnum::Logic->value,
        ];

        // Send a POST request to the finishCheckpoint endpoint without authentication
        $response = $this->postJson(route('api.v1.checkpoint.finish-checkpoint'), $requestData);

        $response->assertUnauthorized();
    }

    /**
     * Test that a request with a non-existent category returns a validation error.
     */
    public function test_non_existent_category_returns_error()
    {
        // Prepare the request data with a non-existent category
        $requestData = [
            'category' => 'non_existent_category',
        ];

        // Send a POST request to the finishCheckpoint endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-checkpoint'), $requestData);

        $response->assertStatus(422); // Assuming the API returns a 422 status for validation errors
        $response->assertJsonValidationErrors(['category']);
    }

    /**
     * Test the successful completion of a checkpoint.
     * This verifies that the checkpoint is marked as completed and a new program is generated.
     */
    public function test_finishing_checkpoint()
    {
        $category = Category::where('name', CategoryEnum::Logic->value)->first();

        /** @var Checkpoint $checkpoint */
        $checkpoint = Checkpoint::factory()->for($this->user)->create();

        // Prepare the request data
        $requestData = [
            'category' => $category->name,
        ];

        // Send a POST request to the finishCheckpoint endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-checkpoint'), $requestData);

        $response->assertOk();

        // Assert the checkpoint IS updated
        $this->assertDatabaseHas('checkpoints', [
            'id' => $checkpoint->id,
            'is_completed' => true,
        ]);

        // Assert the new program is generated
        $this->assertDatabaseHas('programs', [
            'user_id' => $this->user->id,
            'priority_category_id' => $category->id,
        ]);

        $program = $this->user->latestProgram;
        $this->assertNotNull($program);

        // Assert the correct number of sessions are created for the specific program
        $sessionsInProgramAmount = config('global.sessions_in_program_amount');
        $sessionCount = DB::table('program_sessions')->where('program_id', $program->id)->count();
        $this->assertEquals($sessionsInProgramAmount, $sessionCount);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'category' => [
                    'id',
                    'name',
                    'label',
                ],
                'sessionsAmount',
                'completedSessionsAmount',
                'currentSession' => [
                    'id',
                    'games',
                    'isCompleted',
                ],
                'isCompleted',
                'createdAt',
            ],
        ]);
    }
}
