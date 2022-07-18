<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'shop' => $this->shop,
            'course' => CourseResource::make($this->course),
            'date' => substr($this->datetime, 0, 10),
            'time' => substr($this->datetime, 11, -3),
            'number' => $this->number.'人',
            'review' => ReviewResource::make($this->review),
            'visit_completed' => $this->visit_completed,
            'advance_payment' => $this->advance_payment,
        ];

        if ($this->course) {
            $res['amount'] = $this->number * $this->course->price;
        }

        return $res;
    }
}
