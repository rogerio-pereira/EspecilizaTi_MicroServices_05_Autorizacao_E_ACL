<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;

class PermissionUserController extends Controller
{
    public function permissionsUser(Request $request)
    {
        $permissions = $request->user()->permissions;  //Same thing as Auth::user()

        return PermissionResource::collection($permissions);
    }
}
