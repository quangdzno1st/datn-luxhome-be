<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Constant\Enum\StatusOrderPaymentEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    const PATH_VIEW = 'admin.orders.';

    public function index(Request $request, $payable = null)
    {
        $page = $request->query('page', 1);
        $perPage = 10;

        $orders = Order::query()
            ->paginate($perPage, ['*'], 'order', $page);
        $orders = $this->convertStatus($orders);
        $orders = $this->convertStatusPayment($orders);
        return view(self::PATH_VIEW . __FUNCTION__, compact('orders', 'payable'));
    }

    public function convertStatus($items)
    {
        foreach ($items as $item) {
            if ($item->status == StatusOrderEnum::DANG_CHO->value) {
                $item->status = 'Đang chờ';
            } elseif ($item->status == StatusOrderEnum::DA_XAC_NHAN->value) {
                $item->status = 'Đã xác nhận';
            } else if ($item->status == StatusOrderEnum::HOAN_THANH->value) {
                $item->status = 'Hoàn thành';
            } else if ($item->status == StatusOrderEnum::YEU_CAU_HUY->value) {
                $item->status = 'Yêu cầu hủy';
            } else {
                $item->status = 'Đã hủy';
            }
        }
        return $items;
    }
    public function convertStatusPayment($items)
    {
        foreach ($items as $item) {
            if ($item->status_payment == StatusOrderPaymentEnum::CHUA_THANH_TOAN->value) {
                $item->status_payment = 'Chưa thanh toán';
            } elseif ($item->status_payment == StatusOrderPaymentEnum::DA_THANH_TOAN->value) {
                $item->status_payment = 'Đã thanh toán';
            } else if ($item->status_payment == StatusOrderPaymentEnum::DA_HOAN_TIEN->value) {
                $item->status_payment = 'Đã hoàn tiền';
            } else {
                $item->status_payment = 'Chưa hoàn tiền';
            }
        }
        return $items;
    }
    public function not_accepted_cancel(Order $order)
    {
        try {
            $order->update([
                'status'=>StatusOrderEnum::DA_XAC_NHAN->value,//
                'status_payment'=>StatusOrderPaymentEnum::DA_THANH_TOAN->value
            ]);
            return redirect()->back()->with(['result'=>'Thanhf coong',
                'success'=>'Huy khong thanh cong',
                'color'=>'danger'
            ]);
        }catch (\Exception $exception){
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
    public function accepted_cancel(Order $order)
    {
        $order->update([
            'status'=>StatusOrderEnum::DA_HUY->value,
            'status_payment'=>StatusOrderPaymentEnum::CHUA_HOAN_TIEN->value
        ]);
        return redirect()->back()->with(['result'=>'Thanhf coong',
            'success'=>'Huy order thanh cong',
            'color'=>'success'
        ]);
    }
    public function net_amount(Order $order)
    {
        $order_status=$order->status;
        $order_status_payment=$order->status_payment;
        $net_amount=0;
        if ($order_status == StatusOrderEnum::DA_XAC_NHAN->value &&
        $order_status_payment==StatusOrderPaymentEnum::DA_THANH_TOAN->value
        ) {
            $net_amount=$order->total_amount;
        }

    }

    public function calculateRefundCancel(Order $order) {
        $now = Carbon::now();
        $startDate = Carbon::parse($order->start_date);

        if ($now->diffInDays($startDate, false) >= 1) {
            $order->update('net_amount',$order->total_amount*0.25);
            return $order->total_amount * 0.75;
        }

        return 0;
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
