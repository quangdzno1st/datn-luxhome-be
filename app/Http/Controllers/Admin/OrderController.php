<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Http\Controllers\Controller;
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
            if($order->status == StatusOrderEnum::CHUA_THANH_TOAN->value){
                $order->status='Chưa thanh toán';
            }elseif ($order->status==StatusOrderEnum::DA_THANH_TOAN->value){
                $order->status='Đã thanh toán';
            }else{
                $order->status='Thanh toán kết thúc';
            }
        }
        return $orders;
    }
}
