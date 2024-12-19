<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Constant\Enum\StatusPaymentOrderEnum;
use App\Exceptions\RespException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BaseSearchRequest;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Voucher;
use App\Repositories\Order\OrderRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function index1(BaseSearchRequest $request)
    {
        $org_id = Auth::user()?->org_id;
        $hotels=null;
        if (Auth::user()->type==User::ADMIN){
            $orders = Order::query()
//                ->orderByDesc('created_at')
                    ->latest('created_at')
                ->paginate(10, ['*'], 'order');
            $hotels=Hotel::query()->select('id','name')->get();
        }else{
            $orders = Order::query()
//                ->orderByDesc('created_at')
                ->latest('created_at')
                ->where('org_id', $org_id)
                ->paginate(10, ['*'], 'order');
        }

        foreach ($orders as $order){
            $order['haventCheckin']=true;
            $currentTime = Carbon::now();
            $endDate = Carbon::parse($order->end_date)->endOfDay();
            if ($currentTime->gt($endDate)&&$order->check_in==null) {
                $order['haventCheckin']=false;
            }
        if ($order->voucher_id!=null){
            $voucher=$this->VoucherOrder($order->voucher_id);
            foreach ($voucher as $item){
                if ($item['discount_type']){
                    if ((($order['total_amount'])*$item['discount_value'])/100>$item['max_price']){
                        $order['total_amount']=$order['total_amount']-$item['max_price'];
                    }else{
                        $order['total_amount']=$order['total_amount']-($order['total_amount']*$item['discount_value'])/100;
                    }
                }else{
                    $order['total_amount']=$order['total_amount']-$item['discount_value'];
                }
                $order['voucher_id']=$item->code;
            }
        }
        }

        if ($_GET) $orders= $this->search($request->all());
        $this->checkStatusNoti($orders);
        return view('admin.orders.index1', compact('orders','hotels'));
    }

    public function finishOrder($idOrder){
        $order = Order::query()->where('id', $idOrder)->first();
            $user = User::where('id', $order->user_id)->first();
            if ($user) {

                $newTotalAmountOrdered = $user->total_amount_ordered + $order->total_amount;

                $newRank = 0;
                if ($newTotalAmountOrdered > 8000000) {
                    $newRank = 3;
                } elseif ($newTotalAmountOrdered > 5000000) {
                    $newRank = 2;
                } elseif ($newTotalAmountOrdered > 2000000) {
                    $newRank = 1;
                }

                $user->update([
                    'rank' => $newRank,
                    'total_amount_ordered' => $newTotalAmountOrdered,
                ]);
            }
                DB::table('orders')->where('id', $idOrder)
                    ->update([
                        'status' => StatusOrderEnum::HOAN_THANH->value,
                        'net_amount'=>$order->total_amount,
                    ]);
            return redirect()->back()->with(
                [
                    'success'=>'Hoàn thành đơn thành công',
                ]);
    }

    public function VoucherOrder($voucherId){
        $voucher=Voucher::query()->where('vouchers.id', $voucherId)
//            ->where('vouchers.status',1)
            ->select('vouchers.description','vouchers.discount_type',
                'vouchers.discount_value','vouchers.code','vouchers.max_price')->get()
        ;
        return $voucher;
    }
    public function checkStatusNoti($orders)
    {
//        note place
//        0: chưa đến ngày
//        1: checkin muộn
//        2: check out muộn đằng sau có khách
//        3: đang dùng phòng
//        4: checkout muộn
        foreach ($orders as $order) {
            $currentTime = Carbon::now();
            $startDateTime = Carbon::parse($order->start_date);
            $endDateTime = Carbon::parse($order->end_date);
//dd($endDateTime->toDateString());
            if ($order->status==StatusOrderEnum::HOAN_THANH->value){
                $order['statusNoti']=0;
            }
            else{
                // Trạng thái 1: Checkin muộn
            if ($currentTime->greaterThan($startDateTime) && $order->check_in==null) {
                $order['statusNoti']=1; // Checkin muộnnnn
            }
            elseif (!is_null($order->check_in) && is_null($order->check_out) && $currentTime->greaterThan($endDateTime)) {

                $currentOrderRoomIds = OrderItem::where('order_id', $order->id)
                    ->pluck('room_id')
                    ->toArray();

                $hasNextBookingWithSameRoom = Order::where('id', '!=', $order->id)
                    ->whereDate('start_date', $endDateTime->toDateString())
                    ->whereHas('orderItem', function ($query) use ($currentOrderRoomIds) {
                        $query->whereIn('room_id', $currentOrderRoomIds);
                    })
                    ->exists();

                if ($hasNextBookingWithSameRoom) {
                    // Checkout muộn, có khách đặt khác cùng ngày và trùng phòng
                    $order['statusNoti'] = 2;
                } else {
                    // Checkout muộn, không có khách đặt trùng phòng
                    $order['statusNoti'] = 4;
                }
            }
            elseif (!is_null($order->check_in) && $currentTime->between($startDateTime, $endDateTime)) {
                // Đang dùng phòng
                $order['statusNoti']=3;
            }else{
                // chưa đến ngày
                $order['statusNoti']=0;
            }

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
//        if (!StatusOrderEnum::isYeuCauHuy($order['status'])) {
//            throw new RespException('Đơn đặt ở trạng thái không thể hủy.');
//        }

        if (isset($order['check_in'])) {
            throw new RespException('Không thể hủy đơn khi đã sử dụng phòng');
        }

        if ($order['start_date'] <= Carbon::now()->setTime(14, 00)) {
            throw new RespException('Không thể hủy đơn trong quá khứ.');
        }
    }

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

    public function search($request)
    {
//        dd($request);
        if (Auth::user()->type==User::ADMIN){
        $query = Order::query()
//            ->orderByDesc('created_at')
            ->latest('created_at')
            ;
        }else{
            $query = Order::query()
//                ->orderByDesc('created_at')
                ->latest('created_at')
                ->where('org_id', Auth::user()->org_id)
            ;
        }
        if (isset($request['hotel'])&&$request['hotel']!='') {
            $query->where('org_id',  $request['hotel']);
        }

        if (isset($request['code'])&&$request['code']!='') {
            $query->where('code', 'LIKE', '%' . $request['code']. '%');
        }

        if (isset($request['status'])&&$request['status']!='') {
            $query->where('status', $request['status']);
        }

        if (isset($request['total_amount'])&&$request['total_amount']!='') {
            $query->where('total_amount', '>=', $request['total_amount']);
        }

        if (isset($request['start_date'])&&$request['start_date']!='') {
            $query->whereDate('start_date', '>=', $request['start_date']);
        }

        if (isset($request['end_date'])&&$request['end_date']!='') {
            $query->whereDate('end_date', '<=', $request['end_date']);
        }
        return $query->paginate(10, ['*'], 'order');
    }
    public function cancel_order_admin($id)
    {
        $this->accepted_cancel($id,'admin');
    }

}