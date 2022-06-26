<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getBy($condition)
    {
        return User::where($condition)->with('shop')->first();
    }
}
