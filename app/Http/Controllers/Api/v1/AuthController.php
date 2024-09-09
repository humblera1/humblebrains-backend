<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\LoginUserRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Models\Traits\Controllers\withResponseHelpers;
use App\Services\Api\v1\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use withResponseHelpers;

    public function me(Request $request): UserResource
    {
        if (!Auth::check()) {
            (new AuthService())->createAnonymous();
        }

        return new UserResource(Auth::user());
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginUserRequest $request): array
    {
        $credentials = $request->validated();

        (new AuthService())->prepareCredentials($credentials);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return [
                'user' => new UserResource(Auth::user()),
            ];
        }

        $this->responseWithPlainValidationErrors(['general' => 'The provided credentials do not match our records.']);
    }
}
