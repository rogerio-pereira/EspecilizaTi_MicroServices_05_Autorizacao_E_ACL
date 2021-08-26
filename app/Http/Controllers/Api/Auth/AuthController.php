<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\AuthUserRequest;
use Illuminate\Support\Facades\Auth;
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

    public function logout(Request $request)
    {
        // Auth::user()->tokens()->where('device_name', $device_name)->delete();   //Delete specific token
        Auth::user()->tokens()->delete();   //Delete all tokens

        return response()->json([
            'logout' => 'success'
        ]);
    }

    public function me(Request $request)
    {
        $user = Auth::user();

        return new UserResource($user);
    }
}
