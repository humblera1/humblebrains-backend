<?php

namespace App\Services\Api\v1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

final class AuthService
{
    public function createAnonymous(): void
    {
        $this->loginUser($this->storeAnonymous());
    }

    public function storeAnonymous(): User
    {
        $user = new User();

        $user->save();

        return $user;
    }

    public function loginUser(User $user): void
    {
        Auth::login($user);
    }

    /**
     * Prepares credentials in a specific way before retrieving them from the database
     *
     * @param array $credentials
     * @return void
     */
    public function prepareCredentials(array &$credentials): void
    {
        if (array_key_exists('usermail', $credentials)) {
            $usermail = $credentials['usermail'];

            $credentials['usermail'] = function ($query) use ($usermail) {
                // Email or username?
                $validator = Validator::make(['email' => $usermail], ['email' => 'required|email']);

                if ($validator->passes()) {
                    return $query->where('email', $usermail);
                }

                return $query->where('username', $usermail);
            };
        }
    }
}
