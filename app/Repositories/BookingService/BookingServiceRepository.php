<?php

namespace App\Repositories\BookingService;

use App\Models\BookingService;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class BookingServiceRepository extends BaseRepository implements BookingServiceInterface
{
    public function model(): string
    {
        return BookingService::class;
    }

    public function updateStatusByOrderId($status, $orderId)
    {
        BookingService::query()->where('order_id', $orderId)
            ->update(['status' => $status]);
    }

    public function getByOrderId($orderId)
    {
        $query = BookingService::query()
            ->where('order_id', $orderId)
            ->join('services as s', 'booking_services.service_id', '=', 's.id')
            ->join('rooms as r', 'booking_services.room_id', '=', 'r.id')
            ->groupBy('booking_services.service_id')
            ->select(
                's.*',
                DB::raw('COUNT(r.id) as room_count'),
                DB::raw('GROUP_CONCAT(r.code SEPARATOR ", ") as room_codes')
            );

        return $query->get();

    }
}