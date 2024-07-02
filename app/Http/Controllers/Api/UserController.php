<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return UserResource
     */
    public function profile()
    {
        return UserResource::make(Auth::user());
    }

    /**
     * @return UserResource
     */
    public function users()
    {
        $users = $this->userService->getAllUsers();

        return UserResource::make($users);
    }
}
