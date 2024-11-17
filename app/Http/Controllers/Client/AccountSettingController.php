<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSearchRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{

    private OrderService $orderService;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }


    public function index(OrderSearchRequest $request)
    {
        $orders = $this->orderService->searchByPage($request);
        return view('client.myaccount', compact('orders'));
    }

    public function paymentOrder($orderId)
    {
        $urlRedirect = $this->orderService->paymentOrder($orderId);
        return redirect($urlRedirect);
    }

    public function paymentReturn(Request $request){
        $data = $this->orderService->paymentReturn($request);
        $order = $data['order'];
        $status = $data['status'];
        return view('client.bookingfinish', compact('order', 'status'));
    }

}
