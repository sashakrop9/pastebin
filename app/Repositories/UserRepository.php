<?php

namespace App\Repositories;

use App\DataTransferObjects\SociaData;
use App\DataTransferObjects\UserData;
use http\Message;
use Illuminate\Database\Eloquent\Collection;
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
     * @param SociaData $data
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
    public function findByEmail($email): mixed
    {
        return $this->findWhere(['email' => $email])->first();
    }

    /**
     * @param UserData $userData
     * @param SociaData $sociaData
     * @return mixed
     * @throws ValidatorException
     */
    public function updateUser(UserData $userData, SociaData $sociaData): mixed
    {
        $user = $this->update([
            'name' => $userData->name,
            'password' => $userData->password,
        ],
            $sociaData->user_id);

        $user->sociaUser()->updateOrCreate(
            ['socia_id' => $sociaData->socia_id],
            [
                'user_id' =>$sociaData->user_id,
                'socia_id' =>$sociaData->socia_id,
                'token' => $sociaData->token,
            ]
        );
        return $user;
    }

    public function createSociaUser(UserData $userData, SociaData $sociaData)
    {
        $user = $this->create([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => $userData->password,
        ]);

        $user->SociaUser()->create([
            'socia_id' => $sociaData->socia_id,
            'token' => $sociaData->token,
            'user_id' => $user -> id,
        ]);


        return $user;


    }

}
