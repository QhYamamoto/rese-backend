<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Http\Resources\UserResource;

class UserRepository implements UserRepositoryInterface
{
    public function getBy($condition)
    {
        return UserResource::make(
            User::where($condition)->with('shop')->first()
        );
    }
}
