<?php

namespace App\Repositories;

use App\DataTransferObjects\UserData;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Validator\Exceptions\ValidatorException;

class UserRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * @return Collection
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * @param UserData $data
     * @return User
     * @throws ValidatorException
     */
    public function createUser(UserData $data): User
    {
        return $this->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password)
            ]);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param array $attributes
     * @param array $values
     * @return mixed
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return User::updateOrCreate($attributes, $values);
    }

}
