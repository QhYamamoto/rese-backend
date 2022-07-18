<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function getBy($condition);
}
