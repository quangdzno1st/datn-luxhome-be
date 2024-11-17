<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderSearchRequest;
use Illuminate\Http\Request;

interface OrderService
{
    public function create(OrderRequest $request);

    public function paymentReturn(Request $request);

    public function getTotalOrderMapByCityId(array $cityIds);

    public function searchByPage(OrderSearchRequest $request);

    public function generateUrlRedirect($total_amount);

    public function paymentOrder($orderId);
}