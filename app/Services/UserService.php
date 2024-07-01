<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as GitHubUserContract;

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
     */
    public function registerUser(array $data)
    {
        // Валидация данных, если необходимо

        // Создание пользователя через репозиторий
        return $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
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

    /**
     * @param GitHubUserContract $githubUser
     * @return mixed
     */
    public function handleGitHubCallback(GitHubUserContract $githubUser)
    {
        $user = $this->userRepository->findByEmail($githubUser->getEmail());

        $name = $githubUser->getName() ?? ($user->name ?? $githubUser->getEmail());

        $userData = [
            'name' => $name,
            'password' => $user->password ?? bcrypt(Str::random(8)),
            'github_id' => $githubUser->getId(),
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ];

        return $this->userRepository->updateOrCreate(['email' => $githubUser->getEmail()], $userData);
    }
}
