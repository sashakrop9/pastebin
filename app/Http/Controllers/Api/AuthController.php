<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CreateLoginRequest;
use App\Http\Requests\CreateRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param CreateRegisterRequest $request
     * @return UserResource
     */
    public function register(CreateRegisterRequest $request)
    {
        $validatedData = $request->validated();

        $user = $this->userService->registerUser([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return new UserResource($user);
    }

    /**
     * @param CreateLoginRequest $request
     * @return JsonResponse
     */
    public function login(CreateLoginRequest $request)
    {
        $validatedData = $request->validated();

        $user = $this->userService->authenticateUser($validatedData);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }


    /**
     * @param Request $request
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->userService->logoutUser($user);

        return response(null, 204);
    }
}
