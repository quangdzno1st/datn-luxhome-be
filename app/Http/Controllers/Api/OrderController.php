<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request)
    {
       $paymentUrl = $this->orderService->create($request);
       return $this->createSuccess($paymentUrl);
    }

    public function paymentReturn(Request $request){
        $returnUrl = $this->orderService->paymentReturn($request);
        return $this->sendSuccess($returnUrl);
    }
}
