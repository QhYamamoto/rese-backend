<?php

namespace App\Repositories\Reservation;

interface ReservationRepositoryInterface
{
    public function getBy($condition);
    public function getAsCollectionBy($condition);
    public function create($attributes);
    public function update($attributes, $condition);
    public function delete($condition);
}
