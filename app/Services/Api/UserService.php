<?php

namespace App\Services\Api;

use App\Models\CheckpointStage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

final class UserService
{
    const USER_AVATAR_PATH = 'avatars';

    public function update(array $data): void
    {
        $user = Auth::user();

        if (isset($data['email']) && $data['email'] !== $user->email) {
            // Check if the email is being changed
            $user->email = $data['email'];

            // Reset the email_verified_at field if the email was verified
            $user->email_verified_at = null;
        }

        $user->update($data);
    }

    public function setAvatar(UploadedFile $file): void
    {
        $user = Auth::user();

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs(self::USER_AVATAR_PATH, $filename, 'public');

        $user->avatar = $path;

        $user->save();
    }

    public function getCheckpointStatistics(): array
    {
        /** @var CheckpointStage[] $completedStages */
        $completedStages = Auth::user()->completedStages()->with('category')->get();
        $statistics = [];

        $statistics['stages'] = [];

        foreach ($completedStages as $stage) {
            $statistics[$stage->category->name][] = $stage->score;
        }

        if (isset($stage)) {
            $scoresAmount = count($statistics[$stage->category->name]);

            if ($scoresAmount > 0) {
                $statistics['stages'] = range(1, $scoresAmount);
            }
        }

        return $statistics;
    }
}
