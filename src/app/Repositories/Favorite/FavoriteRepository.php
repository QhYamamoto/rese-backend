<?php

namespace App\Repositories\Favorite;

use App\Models\Favorite;

class FavoriteRepository implements FavoriteRepositoryInterface
{
    public function create($attributes)
    {
        return Favorite::create($attributes);
    }

    public function delete($condition)
    {
        Favorite::where($condition)->delete();
    }
}
