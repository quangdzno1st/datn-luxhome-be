<?php

namespace App\Repositories\Hotel;

use App\Models\Hotel;
use App\Repositories\Base\BaseRepository;

class HotelRepository extends BaseRepository implements HotelInterface
{
    public function model(): string
    {
        return Hotel::class;
    }

    public function getAll()
    {
        $hotels = $this->model
            ->select('id', 'name', 'location', 'quantity_of_room', 'star', 'city_id',
                'phone', 'email', 'status', 'quantity_floor')
            ->latest('id')
            ->with('city')
            ->get();

        return $hotels;
    }

    public function detailHotel($id)
    {
        $hotel = $this->model->where('id', $id)->withTrashed()->first();

        return $hotel;
    }

    public function trash()
    {
        $hotels = $this->model
            ->select('id', 'name', 'location', 'quantity_of_room', 'star', 'city_id',
                'phone', 'email', 'status', 'quantity_floor')
            ->latest('id')
            ->with('city')
            ->onlyTrashed()
            ->get();

        return $hotels;
    }
}