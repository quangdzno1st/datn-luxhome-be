<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'quantity_of_room' => $this->quantity_of_room,
            'city_id' => $this->city_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'star' => $this->star,
            'status' => $this->status,
            'quantity_floor' => $this->quantity_floor,
            'city_name' => optional($this->city)->name
        ];
    }
}
