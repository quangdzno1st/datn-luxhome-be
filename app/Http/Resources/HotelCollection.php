<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HotelCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            [
                'data' => HotelResource::collection($this->collection),
//                'meta' => [
//                    'current_page' => $this->currentPage(), // trang hiện tại
//                    'last_page' => $this->lastPage(), // trang cuối cùng
//                    'per_page' => $this->perPage(), // số bản ghi trên 1 trang
//                    'total' => $this->total(), // tổng số bản ghi
//                ],
            ];
    }
}
