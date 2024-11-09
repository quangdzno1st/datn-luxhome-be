<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepository;

class OrderRepository extends BaseRepository implements OrderInterface
{
    public function model()
    {
        return Order::class;
    }
}