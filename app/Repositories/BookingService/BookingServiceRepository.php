<?php

namespace App\Repositories\BookingService;

use App\Models\BookingService;
use App\Repositories\Base\BaseRepository;

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
}