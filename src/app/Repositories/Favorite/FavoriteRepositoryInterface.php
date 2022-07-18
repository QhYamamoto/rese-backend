<?php

namespace App\Repositories\Favorite;

interface FavoriteRepositoryInterface
{
    public function create($attributes);
    public function delete($condition);
}
