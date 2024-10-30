<?php

namespace App\Services\Api;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

final class AuthService
{
    /**
     * @param array $credentials
     * @return User
     */
    public function registerAnonymousUser(array $credentials): User
    {
        self::prepareCredentialsToRegisterAnonymous($credentials);

        return self::registerUser($credentials);
    }

    /**
     * @param array $credentials
     * @return User
     */
    public function registerFullFledgedUser(array $credentials): User
    {
        self::prepareCredentialsToRegisterFullFledged($credentials);

        return self::registerUser($credentials);
    }

    /**
     * @param array $credentials
     * @return User
     */
    public function registerUser(array $credentials): User
    {
        $user =  self::upsertUser($credentials);

        event(new Registered($user));

        return $user;
    }

    /**
     * @param User|Authenticatable $user
     * @param array $credentials
     * @return void
     */
    public function promoteUserToFullFledged(User|Authenticatable $user, array $credentials): void
    {
        self::prepareCredentialsToPromoteUser($user, $credentials);

        self::upsertUser($credentials);
    }

    /**
     * @param array $credentials
     * @return void
     */
    public function promoteCurrentUserToFullFledged(array $credentials): void
    {
        self::promoteUserToFullFledged(Auth::user(), $credentials);
    }

    /**
     * @param array $credentials
     * @return void
     */
    public function registerAndLoginAnonymousUser(array $credentials = []): void
    {
        self::loginUser(self::registerAnonymousUser($credentials));
    }

    /**
     * @param array $credentials
     * @return void
     */
    public function registerAndLoginFullFledgedUser(array $credentials): void
    {
        self::loginUser(self::registerFullFledgedUser($credentials));
    }

    /**
     * @param array $credentials
     * @return void
     */
    public function prepareCredentialsToRegisterAnonymous(array &$credentials): void
    {
        $credentials['id'] = null;
        $credentials['is_anonymous'] = true;
    }

    /**
     * @param array $credentials
     * @return void
     */
    public function prepareCredentialsToRegisterFullFledged(array &$credentials): void
    {
        self::prepareCredentialsToRegisterAnonymous($credentials);

        $credentials['is_anonymous'] = false;
    }

    /**
     * @param User|Authenticatable $user
     * @param array $credentials
     * @return void
     */
    public function prepareCredentialsToPromoteUser(User|Authenticatable $user, array &$credentials): void
    {
        $credentials['id'] = $user->id;
        $credentials['is_anonymous'] = false;
    }

    /**
     * @param array $credentials
     * @return User
     */
    public function upsertUser(array $credentials): User
    {
        return User::updateOrCreate(
            ['id' => $credentials['id']],
            Arr::except($credentials, ['id'])
        );
    }

    /**
     *
     * @param User $user
     * @return void
     */
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
    public function prepareCredentialsToLogin(array &$credentials): void
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

    /**
     * The user is authenticated and is not anonymous
     *
     * @return bool
     */
    public function isCurrentUserFullFledged(): bool
    {
        return (($user = Auth::user()) && self::isFullFledgedUser($user));
    }

    /**
     * The user is not anonymous
     *
     * @param Authenticatable|User $user
     * @return bool
     */
    public function isFullFledgedUser(Authenticatable | User $user): bool
    {
        return !self::isUserAnonymous($user);
    }

    /**
     * The user is authenticated and is anonymous
     *
     * @return bool
     */
    public function isCurrentUserAnonymous(): bool
    {
        return (($user = Auth::user()) && self::isUserAnonymous($user));
    }

    /**
     * The user is anonymous
     *
     * @param Authenticatable|User $user
     * @return bool
     */
    public function isUserAnonymous(Authenticatable | User $user): bool
    {
        return $user->is_anonymous;
    }
}
