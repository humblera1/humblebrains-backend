<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\file\FileUploadRequest;
use App\Http\Requests\Api\v1\user\UserUpdateRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Services\Api\AuthService;
use App\Services\Api\UserService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service,
        protected AuthService $authService,
    ) {}

    public function me(): UserResource
    {
        if (!Auth::check()) {
            $this->authService->registerAndLoginAnonymousUser();
        }

        return new UserResource(Auth::user()->loadAllRelations());
    }

    public function setAvatar(FileUploadRequest $request): UserResource
    {
        $this->service->setAvatar($request->file('file'));

        return new UserResource(Auth::user());
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $this->service->update($request->validated());

        return new UserResource(Auth::user());
    }

    public function sendEmailVerificationNotification(Request $request): Response
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->noContent();
    }

    public function verifyEmail(EmailVerificationRequest $request): UserResource
    {
        $request->fulfill();

        return new UserResource(Auth::user());
    }
}
