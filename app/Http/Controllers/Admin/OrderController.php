<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Constant\Enum\StatusPaymentOrderEnum;
use App\Exceptions\RespException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BaseSearchRequest;
use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{

    private OrderRepository $orderRepos;

    const PATH_VIEW = 'admin.orders.';

    /**
     * @param OrderRepository $orderRepos
     */
    public function __construct(OrderRepository $orderRepos)
    {
        $this->orderRepos = $orderRepos;
    }


    public function index(BaseSearchRequest $request, $payable = null)
    {
        $orders = Order::query()
            ->orderByDesc('orders.code')
            ->paginate($request->getPerPage(), ['*'], 'order', $request->order);
        return view(self::PATH_VIEW . __FUNCTION__, compact('orders', 'payable'));
    }

    public function not_accepted_cancel($orderId)
    {
        try {
            $order = $this->getNonNullById($orderId);
            $this->validateBeforeRequirementCancel($order);
            $this->orderRepos->updateWhenRequirementCancel(StatusOrderEnum::DA_XAC_NHAN->value,
                StatusPaymentOrderEnum::DA_THANH_TOAN, $orderId);

            return redirect()->back()->with(['result' => 'Thành công',
                'success' => 'Xác nhận không hủy thành công!',
                'color' => 'danger'
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @throws RespException
     */
    public function validateBeforeRequirementCancel($order): void
    {
        if (!StatusOrderEnum::isYeuCauHuy($order['status'])) {
            throw new RespException('Đơn đặt ở trạng thái không thể hủy.');
        }

        if (isset($order['check_in'])) {
            throw new RespException('Không thể hủy đơn khi đã sử dụng phòng');
        }

        if ($order['start_date'] <= Carbon::now()->setTime(14, 00)) {
            throw new RespException('Không thể hủy đơn trong quá khứ.');
        }
    }

    /**
     * @throws RespException
     */
    private
    function getNonNullById($orderId)
    {
        $order = Order::query()->where('id', $orderId)->first();
        if (is_null($order)) {
            throw new RespException(__('messages.order_not_found'));
        }

        return $order;
    }

    /**
     * @throws RespException
     */
    public
    function accepted_cancel($orderId)
    {
        $order = $this->getNonNullById($orderId);
        $this->validateBeforeRequirementCancel($order);
        $this->orderRepos->updateWhenRequirementCancel(StatusOrderEnum::DA_HUY->value,
            StatusPaymentOrderEnum::CHUA_HOAN_TIEN, $orderId);

        return redirect()->back()->with(['result' => 'Thanhf coong',
            'success' => 'Hủy đơn đặt thành công',
            'color' => 'success'
        ]);
    }

    public
    function delete($order)
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

    /**
     * @throws RespException
     */
    public function refundMoney($orderId)
    {
        $order = $this->getNonNullById($orderId);
        $this->validateBeforeRefundMoney($order);

        $now = Carbon::now();
        $startDate = Carbon::parse($order->start_date);
        $net_amount = 0;

        if ($now->diffInDays($startDate, false) >= 3) {
            $net_amount = $order->total_amount * 0.25 ;
        } else if ($now->diffInDays($startDate, false) >= 1) {
            $net_amount = $order->total_amount * 0.75;
        }

        $order->net_amount = $net_amount;
        $order->status_payment = StatusPaymentOrderEnum::DA_HOAN_TIEN->value;
        $order->save();

        return redirect()->back()->with([
            'success' => "Đã thực hiện hoàn tiền thành công cho hóa đơn " . $order['code']
        ]);
    }

    /**
     * @throws RespException
     */
    private function validateBeforeRefundMoney($order): void
    {
        if (!StatusOrderEnum::isDaHuy($order['status']) || !StatusPaymentOrderEnum::isChuaHoanTien($order['status_payment'])) {
            throw new RespException("Đơn đặt chưa thanh toán hoặc ở trạng thái không thể hoàn tiền.");
        }
    }
}
