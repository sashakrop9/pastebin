<?php

namespace App\Services;

use App\DataTransferObjects\SociaData;
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
     * @param SociaData $credentials
     * @return User|Authenticatable|null
     */
    public function authenticateUser(UserData $credentials)
    {
        if (!Auth::attempt([
            'name' => $credentials->name,
            'email' => $credentials->email,
            'password' => $credentials->password
        ])) {
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
        Auth::logout();
        $user->currentAccessToken()->delete();
    }

    /**
     * @throws ValidatorException
     */
    public function handleCallback(UserContract $sociaUser)
    {
        $user = $this->userRepository->findByEmail($sociaUser->getEmail());

        if ($user === null) {
            // Логика для случая, когда пользователь не найден
            $name = $sociaUser->getName() ?? $sociaUser->getEmail();
            $sociaData = SociaData::fromArray([
                'socia_id' => $sociaUser->getId(),
                'token' => $sociaUser->token,
                'user_id' => null
            ]);
        }
        else{
            $name = $sociaUser->getName() ?? $sociaUser->getEmail();
            $sociaData = SociaData::fromArray([
                'socia_id' => $sociaUser->getId(),
                'token' =>$sociaUser->token,
                'user_id' => $user->id
            ]);
        }

        $userData = UserData::fromArray([
            'name' => $name,
            'password' => bin2hex(random_bytes(5)),
            'email' => $sociaUser->getEmail()
        ]);

        if ($user===null) {
            return $this->userRepository->createSociaUser($userData, $sociaData);
        } else {
            return $this->userRepository->updateUser($userData, $sociaData);
        }
    }
}
