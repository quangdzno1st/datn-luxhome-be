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
    public function convertStatus($items)
    {
        foreach($items as $item){
            if($item->status == StatusOrderEnum::CHUA_THANH_TOAN->value){
                $item->status='Chưa thanh toán';
            }elseif ($item->status==StatusOrderEnum::DA_THANH_TOAN->value){
                $item->status='Đã thanh toán';
            }else{
                $item->status='Thanh toán kết thúc';
            }
        }
        return $items;
    }
    public function delete($order){
        if ($order->status=='Chưa thanh toán'){
            Order::query()->find($order)->delete();
            return redirect()->back()-with([
                'result'=>'Xóa thành công',
                'color'=>'success'
                ]);
        }else{
            return redirect()->back()-with([
                'result'=>'Xóa không thành công',
                'color'=>'danger'
                ]);
        }
    }
}
