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
        $this->checkStatusNoti($orders);
//        dd($orders);
        return view(self::PATH_VIEW . __FUNCTION__, compact('orders', 'payable'));
    }

    public function checkStatusNoti($orders)
    {
//        0: chưa đến ngày
//        1: checkin,checkout muộn
//        2: check out muộn đằng sau có khách
//        3: đang dùng phòng
        foreach ($orders as $order) {
            $currentTime = Carbon::now(); // Thời gian hiện tại
            $startDateTime = Carbon::parse($order->start_date); // Thời gian bắt đầu
            $endDateTime = Carbon::parse($order->end_date); // Thời gian kết thúc

            // Trạng thái 1: Checkin muộn
            if ($currentTime->greaterThan($startDateTime) && $order->check_in==null) {
                $order['statusNoti']=1; // Checkin muộn
            }
            elseif (!is_null($order->check_in) && is_null($order->check_out) && $currentTime->greaterThan($endDateTime)) {
                // Kiểm tra có khách đặt khác cùng ngày
                $hasNextBooking = Order::where('id', '!=', $order->id)
                ->whereDate('start_date', $endDateTime->toDateString())
//                    ->where('check_in', '!=', null)
                ->exists();
                if ($hasNextBooking) {
                    $order['statusNoti']=2; // Checkout muộn, có khách đặt khác cùng ngày
                }else{
                    $order['statusNoti']=1;
                }
            }
            elseif (!is_null($order->check_in) && $currentTime->between($startDateTime, $endDateTime)) {
                $order['statusNoti']=3; // Đang dùng phòng
            }else{
                $order['statusNoti']=0; // chưa đến ngày
            }
        }
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

    public function search(\Illuminate\Http\Request $request)
    {
        $query = Order::query();

        // Lọc theo mã đặt phòng
        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo tổng tiền
        if ($request->filled('total_amount')) {
            $query->where('total_amount', '>=', $request->total_amount);
        }

        // Lọc theo ngày bắt đầu
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        // Lọc theo ngày kết thúc
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Thực thi query và phân trang kết quả
        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

}
