<?php

namespace App\Services\Api;

use App\Entities\DTOs\user\ChangePasswordDTO;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class UserService
{
    public function update(array $data): User
    {
        $user = Auth::user();

        $user->update($data);

        return $user;
    }

    public function changePassword(ChangePasswordDTO $changePasswordDTO): void
    {
        $user = Auth::user();

        Auth::logoutOtherDevices($changePasswordDTO->currentPassword);

        $user->password = $changePasswordDTO->newPassword;
        $user->save();
    }
}
