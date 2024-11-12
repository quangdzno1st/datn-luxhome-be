<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Http\Controllers\Controller;
use App\Models\BookingService;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    const PATH_VIEW = 'admin.orders.';

    public function index(Request $request,$payable=null)
    {
        $page = $request->query('page', 1);
        $perPage = 10;

        $orders = Order::query()
            ->paginate($perPage, ['*'], 'order', $page);
        $orders=$this->convertStatus($orders);
        return view(self::PATH_VIEW.__FUNCTION__, compact('orders','payable'));
    }
    public function convertStatus($orders)
    {
        foreach($orders as $order){
            if($order->status == StatusOrderEnum::CHUA_THANH_TOAN){
                $order->status='Chưa thanh toán';
            }elseif ($order->status==StatusOrderEnum::DA_THANH_TOAN){
                $order->status='Đã thanh toán';
            }else{
                $order->status='Thanh toán kết thúc';
            }
        }
        return $orders;
    }
    public function checkPayable($idOrder)
    {
        $checkPayableOrder = $this->payableMoneyOrder($idOrder);
        $checkPayableService = $this->payableMoneyService($idOrder);
        $payable = 0;

        if ($checkPayableOrder && $checkPayableService) {
            $payable = $checkPayableOrder->priceOrder + ($checkPayableService->priceService * $checkPayableService->quantityService);
        } elseif (!$checkPayableOrder && $checkPayableService) {
            $payable = $checkPayableService->priceService * $checkPayableService->quantityService;
        } elseif ($checkPayableOrder && !$checkPayableService) {
            $payable = $checkPayableOrder->priceOrder;
        }

        // Trả về JSON cho AJAX
        return response()->json([
            'success' => true,
            'payable' => $payable,
        ]);
    }

    public function payableMoneyOrder($idOrder){
        $order = Order::query()
            ->select('orders.total_amount as priceOrder', 'orders.status as statusOrder')
            ->where('orders.status', StatusOrderEnum::CHUA_THANH_TOAN)
            ->where('orders.id', $idOrder)
            ->get();

        if($order->isEmpty())
        {
            return false;
        }
        else
        {
            [$order1]=$order;
            return $order1;
        }
    }
    public function payableMoneyService($idOrder){
        $bookingServices = BookingService::query()
            ->select('booking_services.price as priceService',
                'booking_services.status as statusService',
                'booking_services.quantity as quantityService',
            )
            ->join('orders', function ($join) {
                $join->on('orders.id', '=', 'booking_services.order_id');
            })
            ->where('booking_services.status',
                StatusOrderEnum::CHUA_THANH_TOAN)
            ->where('orders.id', $idOrder)
            ->get();
        if(!$bookingServices->isEmpty())
        {
            [$bookingServices]=$bookingServices;
            return $bookingServices;
        }
        else
        {
            return false;
        }
    }
}
