<?php

namespace App\Repositories\Shop;

use App\Models\Shop;

class ShopRepository implements ShopRepositoryInterface
{
    public function getAll()
    {
        return Shop::with(['region', 'genre'])->get();
    }

    public function getById($condition, $necessaryData = [])
    {
        return Shop::where($condition)->with($necessaryData)->first();
    }

    public function getShops($column, $values)
    {
        return Shop::whereIn($column, $values)->with(['region', 'genre'])->get();
    }

    public function create($attributes)
    {
        return Shop::create($attributes);
    }
    
    public function update($attributes, $condition)
    {
        return Shop::where($condition)->update($attributes);
    }
}
