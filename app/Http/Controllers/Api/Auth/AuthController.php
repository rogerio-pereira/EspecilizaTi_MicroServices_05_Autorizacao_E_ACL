<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\AuthUserRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $repository;

    public function __construct(User $repository)
    {
        $this->repository = $repository;
    }

    public function auth(AuthUserRequest $request)
    {
        $user = $this->repository->where('email', $request->email)->firstOrFail();

        if( ! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return (new UserResource($user))
                    ->additional([
                        'token' => $user->createToken($request->device_name)->plainTextToken,
                    ]);
    }
}
