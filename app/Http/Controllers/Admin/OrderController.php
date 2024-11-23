<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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

    public function delete($order)
    {
        if ($order->status == 'Chưa thanh toán') {
            Order::query()->find($order)->delete();
            return redirect()->back() - with([
                    'result' => 'Xóa thành công',
                    'color' => 'success'
                ]);
        } else {
            return redirect()->back() - with([
                    'result' => 'Xóa không thành công',
                    'color' => 'danger'
                ]);
        }
    }
}
