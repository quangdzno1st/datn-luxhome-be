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

    public function searchByPage(array $request, bool $isPaginate)
    {
        $query = Order::query()
            ->select('orders.start_date', 'orders.end_date', 'orders.code', 'orders.total_amount', 'orders.status',
                'h.district', 'h.name as hotel_name', 'orders.id', 'h.province',
                'h.star', 'orders.code', 'orders.name', 'orders.email', 'orders.phone', 'orders.note')
            ->join('hotels as h', 'h.id', '=', 'orders.org_id');

        if (isset($request['user_id'])) {
            $query->where('user_id', $request['user_id']);
        }
        if (isset($request['keyword'])) {
            $query->where(function ($query) use ($request) {
                return $query->where('note', 'like', '%' . $request['keyword'] . '%')
                    ->orWhere('code', 'like', '%' . $request['keyword'] . '%');
            });
        }
        if (isset($request['status'])) {
            $query->where('status', $request['status']);
        }
        if (isset($request['start_date'])) {
            $query->whereDate('start_date', '>=', $request['start_date']);
        }
        if (isset($request['end_date'])) {
            $query->whereDate('end_date', '<=', $request['end_date']);
        }
        if (isset($request['total_amount'])) {
            $query->where(function ($query) use ($request) {
                $query->where('total_price', '<=', $request['total_amount'] + 500_000)
                    ->orWhere('total_price', '>=', $request['total_amount'] - 500_000);
            });
        }
        if (isset($request['hotel_id'])) {
            $query->where('h.id', $request['hotel_id']);
        }
        if (isset($request['order_id'])) {
            $query->where('orders.id', $request['order_id']);
        }
        if (isset($request['transaction_id'])) {
            $query->where('orders.transaction_id', $request['transaction_id']);
        }

        if ($isPaginate) {
            return $query->orderByDesc('orders.code')
                ->paginate(10);
        }
        return $query->orderByDesc('orders.code')->first();
    }

    public function updateStatusById($status, $id)
    {
        Order::query()->where('id', $id)
            ->update(['status' => $status]);
    }
}