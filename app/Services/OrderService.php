<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;

interface OrderService
{
    public function create(OrderRequest $request);
    public function paymentReturn(Request $request);
}