<?php

namespace App\Http\Controllers\Api\v1;

use App\Exceptions\Validation\WithPlainErrorsValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LoginUserRequest;
use App\Http\Requests\Api\v1\RegisterUserRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Models\Traits\Controllers\withResponseHelpers;
use App\Services\Api\AuthService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use withResponseHelpers;

    public function __construct(
        protected AuthService $service,
    ) {}

    public function me(): UserResource
    {
        if (!Auth::check()) {
            $this->service->registerAndLoginAnonymousUser();
        }

        return new UserResource(Auth::user()->loadAllRelations());
    }

    /**
     * @param LoginUserRequest $request
     * @return UserResource
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function login(LoginUserRequest $request): UserResource
    {
        /** @throws AuthorizationException */
        Gate::authorize('login');

        /** @throws ValidationException */
        $credentials = $request->validated();

        $this->service->prepareCredentialsToLogin($credentials);

        if (!Auth::attempt($credentials)) {
            /** @throws WithPlainErrorsValidationException */
            $this->responseWithPlainValidationError('The provided credentials do not match our records');
        }

        $request->session()->regenerate();

        return new UserResource(Auth::user()->loadWithRelations());
    }

    /**
     * @param RegisterUserRequest $request
     * @return UserResource
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function register(RegisterUserRequest $request): UserResource
    {
        /** @throws AuthorizationException */
        Gate::authorize('register');

        /** @throws ValidationException */
        $credentials = $request->validated();

        // Вторая ветка недостижима, поскольку пока существует только веб-приложение. Пока...
        // Сценарий работы с веб-приложением не подразумевает существования не-анонимных пользователей
        Auth::check()
            ? $this->service->promoteCurrentUserToFullFledged($credentials)
            : $this->service->registerAndLoginFullFledgedUser($credentials);

        return new UserResource(Auth::user()->loadWithRelations());
    }
}
