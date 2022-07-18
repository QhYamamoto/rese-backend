<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShopCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $formedData = ShopResource::collection($this->collection);

        /* 各店舗の地域Id、ジャンルIdを配列に格納 */
        $shopIds = $regionIds = $genreIds = [];
        foreach ($formedData as $shop) {
            array_push($shopIds, $shop->id);
            array_push($regionIds, $shop->region_id);
            array_push($genreIds, $shop->genre_id);
        }
        
        /* 重複要素を削除し、キーを振りなおす */
        $regionIds = array_values(array_unique($regionIds));
        $genreIds = array_values(array_unique($genreIds));

        return [
            'shops' => $formedData,
            'shopIds' => $shopIds,
            'regionIds' => $regionIds,
            'genreIds' => $genreIds,
        ];
    }
}
