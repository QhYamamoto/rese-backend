<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $res = [
            'id' => $this->id,
            'representative_id' => $this->representative_id,
            'courses' => CourseResource::collection($this->courses) ?? [],
            'region' => RegionAndGenreResource::make($this->region),
            'genre' => RegionAndGenreResource::make($this->genre),
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
        ];

        if (!$this->reservations) {  // 予約データがない場合はこれで返却
            return $res;
        }

        $reservationData = ReservationResource::collection($this->reservations);

        $reservations = $histories = $reviews = [];
        foreach ($reservationData as $reservation) {  // 来店前の予約と来店済みの予約の仕分け
            if (!$reservation->visit_completed) {
                array_push($reservations, $reservation);
            } else {
                array_push($histories, $reservation);
            }
        }

        $histories = array_reverse($histories);

        if (count($histories)) {
            foreach ($histories as $history) {
                array_push($reviews, $history->review);
            }
        }

        $res += [
            'reservations' => $reservations,
            'histories' => $histories,
            'reviews' => $reviews
        ];

        return $res;
    }
}
