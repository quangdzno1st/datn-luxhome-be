<?php

namespace App\Repositories\Hotel;

use App\Models\Hotel;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Log;

class HotelRepository extends BaseRepository implements HotelInterface
{
    public function model(): string
    {
        return Hotel::class;
    }

    public function getAll(){
        $hotels = $this->model
            ->select('id',	'name', 'slug',	'location',	'quantity_of_room',	'star',	'city_id',
            'phone', 'email', 'status', 'quantity_floor')
            ->latest('id')
        ->with('city')
        ->get();

        return $hotels;
    }

    public function detailHotel($slug){
        $hotel = $this->model->where('slug', $slug)->withTrashed()->first();

        return $hotel;
    }

    public function trash(){
        $hotels = $this->model
            ->select('id',	'name', 'slug',	'location',	'quantity_of_room',	'star',	'city_id',
                'phone', 'email', 'status', 'quantity_floor')
            ->latest('id')
            ->with('city')
            ->onlyTrashed()
            ->get();

        return $hotels;
    }
}