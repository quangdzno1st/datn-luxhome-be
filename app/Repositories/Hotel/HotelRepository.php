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
        return $this->model
            ->select('id', 'name', 'location', 'quantity_of_room', 'star', 'city_id',
                'phone', 'email', 'status', 'quantity_floor', 'thumbnail', 'description', 'province',
                'district', 'commune', 'latitude', 'longitude', 'view')
            ->orderByDesc('view')
            ->latest('id')
            ->with(['city', 'images'])
            ->paginate(10);
    }

    public function getAllForClient()
{
    $currentMonthStart = now()->startOfMonth();
    $currentMonthEnd = now()->endOfMonth();

    return $this->model
        ->select(
            'hotels.id', 'hotels.name', 'hotels.location', 'hotels.quantity_of_room', 'hotels.star',
            'hotels.city_id', 'hotels.phone', 'hotels.email', 'hotels.status', 'hotels.quantity_floor',
            'hotels.thumbnail', 'hotels.description', 'hotels.province', 'hotels.district', 'hotels.commune',
            'hotels.latitude', 'hotels.longitude', 'hotels.view'
        )
        // ->join('orders', 'orders.org_id', '=', 'hotels.id') // Thực hiện join với bảng orders
        ->withCount([
            'orders as monthly_orders' => function ($query) use ($currentMonthStart, $currentMonthEnd) {
                $query->whereBetween('orders.created_at', [$currentMonthStart, $currentMonthEnd])->where('orders.status', 3);
            }
        ])
        
        ->whereNull('hotels.deleted_at')
        ->where('status', 1)
        ->groupBy('hotels.id')
        ->orderByDesc('monthly_orders')
        ->paginate(10);
}


    public function getAllForHotelier()
    {
        $user = Auth::user();

        $hotels = $this->model
            ->select('id', 'name', 'location', 'quantity_of_room', 'star', 'city_id',
                'phone', 'email', 'status', 'quantity_floor', 'thumbnail', 'description', 'province',
                'district', 'commune', 'latitude', 'longitude', 'view')
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
            ->select('id', 'name', 'thumbnail', 'location', 'quantity_of_room', 'star', 'city_id',
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