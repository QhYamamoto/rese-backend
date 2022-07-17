<?php

namespace App\Repositories\Review;

interface ReviewRepositoryInterface
{
    public function create($attributes);
    public function update($attributes, $condition);
    public function delete($condition);
}
