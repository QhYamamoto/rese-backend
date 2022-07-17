<?php

namespace App\Repositories\Shop;

use App\Models\Shop;
use App\Http\Resources\ShopResource;
use App\Http\Resources\ShopCollection;

class ShopRepository implements ShopRepositoryInterface
{
    public function getAll()
    {
        return ShopCollection::make(
            Shop::with(['region', 'genre'])->get(),
        );
    }

    public function getById($condition, $necessaryData = [])
    {
        return ShopResource::make(
            Shop::where($condition)->with($necessaryData)->first()
        );
    }

    public function getAsCollectionWhere($column, $values)
    {
        return ShopCollection::make(
            Shop::whereIn($column, $values)->with(['region', 'genre'])->get()
        );
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
