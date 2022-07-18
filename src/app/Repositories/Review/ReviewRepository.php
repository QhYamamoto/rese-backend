<?php

namespace App\Repositories\Review;

use App\Models\Review;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function create($attributes)
    {
        return Review::create($attributes);
    }

    public function update($attributes, $condition)
    {
        return Review::where($condition)->update($attributes);
    }

    public function delete($condition)
    {
        Review::where($condition)->delete();
    }
}
