<?php

namespace App\Repositories\Order;

use App\Models\City;
use App\Models\Order;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class OrderRepository extends BaseRepository implements OrderInterface
{
    public function model()
    {
        return Order::class;
    }

    public function getTotalOrdersMapByCityId(array $cityIds)
    {
        return City::query()->select(
            'cities.id as city_id',
            DB::raw('COUNT(orders.id) as total_orders'),
            DB::raw('SUM(CASE WHEN YEAR(orders.created_at) = YEAR(CURDATE()) AND MONTH(orders.created_at) = MONTH(CURDATE()) THEN 1 ELSE 0 END) as orders_this_month')
        )
            ->leftJoin('hotels', 'hotels.city_id', '=', 'cities.id')
            ->leftJoin('orders', 'orders.org_id', '=', 'hotels.id')
            ->whereIn("cities.id", $cityIds)
            ->groupBy('cities.id')
            ->get();
    }
}