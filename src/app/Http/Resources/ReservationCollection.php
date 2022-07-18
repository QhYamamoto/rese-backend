<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReservationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $res = [
            'reservations' => [],
            'histories' => [],
        ];

        if (!count($this->collection)) {
            return $res;
        }

        $formedData = ReservationResource::collection($this->collection);  // 成形されたデータを取得

        if (count($formedData)) {  // データがあれば
            foreach ($formedData as $reservation) {
                if (!$reservation->visit_completed) {  // 来店前の予約と来店済みの予約に仕分ける
                    array_push($res['reservations'], $reservation);
                } else {
                    array_push($res['histories'], $reservation);
                }
            }
            $res['histories'] = array_reverse($res['histories']);
        }

        return $res;
    }
}
