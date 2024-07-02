<?php

namespace App\Services;

use App\DataTransferObjects\UserData;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as UserContract;
use Prettus\Validator\Exceptions\ValidatorException;

class UserService
{
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return Collection
     */
    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    /**
     * @param array $data
     * @return User
     * @throws ValidatorException
     */
    public function registerUser(UserData $data)
    {
        // Создание пользователя через репозиторий
        return $this->userRepository->createUser($data);
    }

    /**
     * @param array $credentials
     * @return User|Authenticatable|null
     */
    public function authenticateUser(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return Auth::user();
    }

    /**
     * @param $user
     * @return void
     */
    public function logoutUser($user)
    {
        $user->currentAccessToken()->delete();
        Auth::logout();
    }

    public function handleCallback(UserContract $sociaUser)
    {
        $user = $this->userRepository->findByEmail($sociaUser->getEmail());

        $name = $sociaUser->getName() ?? ($user->name ?? $sociaUser->getEmail());

        $userData = [
            'name' => $name,
            'password' => $user->password ?? bcrypt(Str::random(8)),
            'github_id' => $sociaUser->getId(),
            'github_token' => $sociaUser->token,
            'github_refresh_token' => $sociaUser->refreshToken,
        ];

        return $this->userRepository->updateOrCreate(['email' => $sociaUser->getEmail()], $userData);
    }
}
