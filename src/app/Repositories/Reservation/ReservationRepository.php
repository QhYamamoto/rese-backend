<?php

namespace App\Repositories\Reservation;

use App\Models\Reservation;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\ReservationCollection;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getBy($condition)
    {
        return ReservationResource::make(
            Reservation::where($condition)->with(['shop', 'course'])->first()
        );
    }

    public function getAsCollectionBy($condition)
    {
        return ReservationCollection::make(
            Reservation::where($condition)->orderBy('datetime', 'asc')->with(['shop', 'shop.courses', 'review', 'course'])->get()
        );
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
