<?php

namespace Tests\Feature\Api\Checkpoint;

use App\Enums\Game\CategoryEnum;
use App\Models\Category;
use App\Models\Checkpoint;
use App\Models\CheckpointStage;
use App\Models\Traits\Tests\WithAuthenticate;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CheckpointFinishStageTest extends TestCase
{
    use DatabaseTransactions, WithAuthenticate;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = $this->authenticateUser();
    }

    /**
     * Testing an unauthorized request.
     *
     * @return void
     */
    public function test_make_unauthorized_request(): void
    {
        Auth::logout();

        // Send a POST request to the finishStage endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-stage'));

        // Assert the response status
        $response->assertStatus(401);

        $response->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * Testing various cases of invalid input.
     *
     * @return void
     */
    public function test_return_errors_to_invalid_input(): void
    {
        $emptyRequestData = [];
        $onlyCategoryRequestData = [
            'category' => CategoryEnum::Memory->value,
        ];
        $invalidCategoryRequestData = [
            'category' => 'non-existing-category',
            'score' => 85,
        ];

        $responseOnEmpty = $this->postJson(route('api.v1.checkpoint.finish-stage'), $emptyRequestData);
        $responseOnOnlyCategory = $this->postJson(route('api.v1.checkpoint.finish-stage'), $onlyCategoryRequestData);
        $responseOnInvalidCategory = $this->postJson(route('api.v1.checkpoint.finish-stage'), $invalidCategoryRequestData);

        $responseOnEmpty->assertStatus(422);
        $responseOnEmpty->assertJsonStructure([
            'message',
            'errors' => [
                'score',
                'category',
            ],
        ]);

        $responseOnEmpty->assertStatus(422);
        $responseOnOnlyCategory->assertJsonStructure([
            'message',
            'errors' => [
                'score',
            ],
        ]);

        $responseOnEmpty->assertStatus(422);
        $responseOnInvalidCategory->assertJsonStructure([
            'message',
            'errors' => [
                'category',
            ],
        ]);

    }

    /**
     * Testing stage completion when there are unfinished stages left.
     *
     * @return void
     */
    public function test_save_stage_when_uncompleted_stages_left(): void
    {
        /** @var Checkpoint $checkpoint */
        $checkpoint = Checkpoint::factory()->for($this->user)->create();

        /** @var CheckpointStage $uncompletedStage */
        $uncompletedStage = CheckpointStage::factory()
            ->for($checkpoint)
            ->create([
                'category_id' => Category::where('name', CategoryEnum::Memory)->value('id'),
            ]);

        /** @var CheckpointStage $stageToComplete */
        $stageToComplete = CheckpointStage::factory()
            ->for($checkpoint)
            ->create([
                'category_id' => Category::where('name', CategoryEnum::Logic)->value('id'),
            ]);

        // Prepare the request data
        $requestData = [
            'category' => CategoryEnum::Logic->value,
            'score' => 100,
        ];

        // Send a POST request to the finishStage endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-stage'), $requestData);

        // Assert the response status
        $response->assertStatus(200);

        // Assert another stage is not updated
        $this->assertDatabaseHas('checkpoint_stages', [
            'id' => $uncompletedStage->id,
            'score' => 0,
            'is_completed' => false,
        ]);

        // Assert the stage is updated correctly
        $this->assertDatabaseHas('checkpoint_stages', [
            'id' => $stageToComplete->id,
            'score' => 100,
            'is_completed' => true,
        ]);

        // Assert the checkpoint IS NOT updated
        $this->assertDatabaseHas('checkpoints', [
            'id' => $checkpoint->id,
            'is_completed' => false,
        ]);

        $response->assertJson([
            'data' => [
                'id' => $stageToComplete->id,
                'score' => 100,
                'isCompleted' => true,
                'category' => [
                    'id' => Category::where('name', CategoryEnum::Logic)->value('id'),
                    'name' => CategoryEnum::Logic->value,
                    'label' => CategoryEnum::Logic->name,
                ],
            ],
        ]);
    }

    /**
     * Testing the completion of the last unfinished stage if the result of several stages coincides.
     */
    public function test_save_last_uncompleted_stage_with_score_coincidence(): void
    {
        /** @var Checkpoint $checkpoint */
        $checkpoint = Checkpoint::factory()->for($this->user)->create();

        CheckpointStage::factory()
            ->for($checkpoint)
            ->create([
                'score' => 10,
                'is_completed' => true,
                'category_id' => Category::where('name', CategoryEnum::Memory)->value('id'),
            ]);

        /** @var CheckpointStage $stageToComplete */
        $stageToComplete = CheckpointStage::factory()
            ->for($checkpoint)
            ->create([
                'category_id' => Category::where('name', CategoryEnum::Logic)->value('id'),
            ]);

        // Prepare the request data
        $requestData = [
            'category' => CategoryEnum::Logic->value,
            'score' => 10,
        ];

        // Send a POST request to the finishStage endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-stage'), $requestData);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the stage is updated correctly
        $this->assertDatabaseHas('checkpoint_stages', [
            'id' => $stageToComplete->id,
            'score' => 10,
            'is_completed' => true,
        ]);

        // Assert the checkpoint IS NOT updated
        $this->assertDatabaseHas('checkpoints', [
            'id' => $checkpoint->id,
            'is_completed' => false,
        ]);

        $response->assertJson([
            'data' => [
                'id' => $stageToComplete->id,
                'score' => 10,
                'isCompleted' => true,
                'category' => [
                    'id' => Category::where('name', CategoryEnum::Logic)->value('id'),
                    'name' => CategoryEnum::Logic->value,
                    'label' => CategoryEnum::Logic->name,
                ],
            ],
        ]);
    }


    /**
     * Checking the completion of the last unfinished stage if several stages DO NOT have equal scores.
     */
    public function test_save_last_uncompleted_stage_with_no_score_coincidence(): void
    {
        /** @var Checkpoint $checkpoint */
        $checkpoint = Checkpoint::factory()->for($this->user)->create();

        /** @var CheckpointStage $completedStage */
        $completedStage = CheckpointStage::factory()
            ->for($checkpoint)
            ->create([
                'score' => 10,
                'is_completed' => true,
                'category_id' => Category::where('name', CategoryEnum::Memory)->value('id'),
            ]);

        /** @var CheckpointStage $stageToComplete */
        $stageToComplete = CheckpointStage::factory()
            ->for($checkpoint)
            ->create([
                'category_id' => Category::where('name', CategoryEnum::Logic)->value('id'),
            ]);

        // Prepare the request data
        $requestData = [
            'category' => CategoryEnum::Logic->value,
            'score' => 100,
        ];

        // Send a POST request to the finishStage endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-stage'), $requestData);

        // Assert the response status
        $response->assertStatus(200);

        // Assert the stage is updated correctly
        $this->assertDatabaseHas('checkpoint_stages', [
            'id' => $stageToComplete->id,
            'score' => 100,
            'is_completed' => true,
        ]);

        // Assert the checkpoint IS NOT updated (responsibility now on a separate /finish-checkpoint request)
        $this->assertDatabaseHas('checkpoints', [
            'id' => $checkpoint->id,
            'is_completed' =>false,
        ]);

        $response->assertJson([
            'data' => [
                'id' => $stageToComplete->id,
                'score' => 100,
                'isCompleted' => true,
                'category' => [
                    'id' => Category::where('name', CategoryEnum::Logic)->value('id'),
                    'name' => CategoryEnum::Logic->value,
                    'label' => CategoryEnum::Logic->name,
                ],
            ],
        ]);
    }

    /**
     * Testing the request if there are no unfinished checkpoints.
     *
     * @return void
     */
    public function test_save_stage_when_no_unfinished_checkpoints_left(): void
    {
        Checkpoint::factory()
            ->for($this->user)
            ->completed()
            ->count(5)
            ->create();

        // Prepare the request data
        $requestData = [
            'category' => CategoryEnum::Logic->value,
            'score' => 100,
        ];

        // Send a POST request to the finishStage endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-stage'), $requestData);

        // Assert the response status
        $response->assertStatus(404);

        $response->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * Testing an attempt to save the result of an already completed stage.
     *
     * @return void
     */
    public function test_try_to_save_stage_that_already_completed(): void
    {
        $checkpoint = Checkpoint::factory()
            ->for($this->user)
            ->create();

        CheckpointStage::factory()
            ->for($checkpoint)
            ->for(Category::where('name', CategoryEnum::Logic->value)->first())
            ->completed()
            ->create();

        // Prepare the request data
        $requestData = [
            'category' => CategoryEnum::Logic->value,
            'score' => 100,
        ];

        // Send a POST request to the finishStage endpoint
        $response = $this->postJson(route('api.v1.checkpoint.finish-stage'), $requestData);

        // Assert the response status
        $response->assertStatus(404);

        $response->assertJsonStructure([
            'message',
        ]);
    }
}
