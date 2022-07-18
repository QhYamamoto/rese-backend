<?php

namespace App\Repositories\Shop;

interface ShopRepositoryInterface
{
    public function getAll();
    public function getById($condition, $necessaryData = []);
    public function getAsCollectionWhere($column, $values);
    public function create($attributes);
    public function update($attributes, $condition);
}
