<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\PermissionResource;
use App\Http\Requests\AddPermissionsUserRequest;

class PermissionUserController extends Controller
{
    private $userRepository;

    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function permissionsUser($uuid)
    {
        $user = $this->userRepository
                    ->where('uuid', $uuid)
                    ->with('permissions')
                    ->firstOrFail();

        return PermissionResource::collection($user->permissions);
    }

    public function addPermissionsUser(AddPermissionsUserRequest $request)
    {
        if(Gate::denies('add_permissions_user'))
            abort(403, 'Forbiden');

        $user = $this->userRepository
                    ->where('uuid', $request->user)
                    ->firstOrFail();
        $user->permissions()->attach($request->permissions);

        return response()->json(['message' => 'success']);
    }

    public function userHasPermission($permission)
    {
        $user =  Auth::user();

        if(!$user->isSuperAdmin() && !$user->hasPermission($permission))
            return response()->json(['message' => 'Unauthorized'], 403);

        return response()->json(['message' => 'success']);
    }
}
