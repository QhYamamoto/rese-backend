<?php

namespace App\Repositories\Reservation;

use App\Models\Reservation;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getBy($condition)
    {
        return Reservation::where($condition)->with(['shop', 'course'])->first();
    }

    public function getAsCollectionBy($condition)
    {
        return Reservation::where($condition)->orderBy('datetime', 'asc')->with(['shop', 'shop.courses', 'review', 'course'])->get();
    }

    public function create($attributes)
    {
        return Reservation::create($attributes);
    }

    public function update($attributes, $condition)
    {
        return Reservation::where($condition)->update($attributes);
    }

    public function delete($condition)
    {
        Reservation::where($condition)->delete();
    }
}
