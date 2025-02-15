<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\user\ChangePasswordRequest;
use App\Http\Requests\Api\v1\user\ForgotPasswordRequest;
use App\Http\Requests\Api\v1\user\LoginUserRequest;
use App\Http\Requests\Api\v1\user\RegisterUserRequest;
use App\Http\Requests\Api\v1\user\ResetPasswordRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Models\Traits\Controllers\withResponseHelpers;
use App\Services\Api\AuthService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use withResponseHelpers;

    public function __construct(
        protected AuthService $service,
    ) {}

    /**
     * @param LoginUserRequest $request
     * @return UserResource
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function login(LoginUserRequest $request): UserResource
    {
        Gate::authorize('login');

        $credentials = $request->validated();

        $this->service->prepareCredentialsToLogin($credentials);

        if (!Auth::attempt($credentials)) {
            $this->responseWithPlainValidationError('The provided credentials do not match our records');
        }

        session()->regenerate();

        return new UserResource(Auth::user()->loadAllRelations());
    }

    /**
     * @param RegisterUserRequest $request
     * @return UserResource
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function register(RegisterUserRequest $request): UserResource
    {
        Gate::authorize('register');

        // Вторая ветка недостижима, поскольку пока существует только веб-приложение. Пока...
        // Сценарий работы с веб-приложением не подразумевает существования не-анонимных пользователей
        Auth::check()
            ? $this->service->promoteCurrentUserToFullFledged($request->validated())
            : $this->service->registerAndLoginFullFledgedUser($request->validated());

        $user = Auth::user();

        $user->refresh();
        $user->loadAllRelations();

        return new UserResource($user);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->service->changePassword($request->getDTO());
    }

    public function forgotPassword(ForgotPasswordRequest $request): void
    {
        $this->service->sendPasswordResetLink($request->validated());
    }

    public function resetPassword(ResetPasswordRequest $request): void
    {
        $this->service->resetPassword($request->validated());
    }
}
