<?php

namespace App\Repositories\Hotel;

use App\Models\Hotel;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Auth;

class HotelRepository extends BaseRepository implements HotelInterface
{
    public function model(): string
    {
        return Hotel::class;
    }

    public function getAllForAdmin()
    {
        $hotels = $this->model
            ->select('id', 'name', 'location', 'quantity_of_room', 'star', 'city_id',
                'phone', 'email', 'status', 'quantity_floor')
            ->latest('id')
            ->with(['city', 'images'])
            ->paginate(10);

        return $hotels;
    }

    public function getAllForHotelier()
    {
        $user = Auth::user();

        $hotels = $this->model
            ->select('id', 'name', 'location', 'quantity_of_room', 'star', 'city_id',
                'phone', 'email', 'status', 'quantity_floor')
            ->latest('id')
            ->where('id', $user->org_id)
            ->with(['city', 'images'])
            ->paginate(10);

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
            ->paginate(10);

        return $hotels;
    }

    public function existsById($id)
    {
        return $this->model->where('id', $id)->exists();
    }
}