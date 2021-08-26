<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    protected $repository;

    public function __construct(Resource $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $resources = $this->repository->with('permissions')->get();

        return MenuResource::collection($resources);
    }
}
