<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\file\FileUploadRequest;
use App\Http\Requests\Api\v1\user\UserUpdateRequest;
use App\Http\Resources\Api\v1\UserResource;
use App\Services\Api\AuthService;
use App\Services\Api\UserService;
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

    public function setAvatar(FileUploadRequest $request)
    {
        $file = $request->file('file');

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('avatars', $filename, 'public');

        // $user->avatar_path = $path; $user->save();

        return response()->json(['message' => 'Avatar uploaded successfully', 'path' => $path], 200);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        return new UserResource($this->service->update($request->validated()));
    }
}
