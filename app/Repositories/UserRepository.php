<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Contracts\RepositoryInterface;
use App\Models\User;

class UserRepository
{
    /**
     * @return Collection
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function create(array $attributes): User
    {
        return User::create($attributes);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return User::updateOrCreate($attributes, $values);
    }

}
