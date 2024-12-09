<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Http\Controllers\Controller;
use App\Models\BookingService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

define('CHECKIN_START', '14:00');
define('CHECKIN_END', '00:00');
define('CHECKOUT_START', '05:00');
define('CHECKOUT_END', '11:30');

class OrderDetailController extends Controller
{
    const PATH_VIEW = 'admin.orders.view';

    public function showOrderDetail(Order $order)
    {
        $sumService=0;
        $sumOrderItem=0;

        if ($order->status == StatusOrderEnum::DANG_CHO->value) {
            $order->status = 'Đang chờ';
        } elseif ($order->status == StatusOrderEnum::DA_XAC_NHAN->value) {
            $order->status = 'Đã xác nhận';
        } else if ($order->status == StatusOrderEnum::HOAN_THANH->value) {
            $order->status = 'Hoàn thành';
        } else if ($order->status == StatusOrderEnum::YEU_CAU_HUY->value) {
            $order->status = 'Yêu cầu hủy';
        } else {
            $order->status = 'Đã hủy';
        }
        $orderItemInfo=$this->orderItemInfo($order->id);
        $servicesInfo=$this->servicesInfo($order->id);
        foreach ($orderItemInfo as $item){
            $sumOrderItem+=$item->orderItemQuantity*$item->cataloguePrice;
        }
        foreach ($servicesInfo as $item){
            $sumService+=$item->servicePrice;
        }

        if ($order->voucher_id!=null){
            $voucher=$this->VoucherOrder($order->voucher_id);
            foreach ($voucher as $item){
                $order['total_amount']=($sumService+$sumOrderItem)-$item['discount_value'];
                $order['voucher_id']=$item->code;
            }
        }else{
            $voucher=null;
        $order['total_amount']=($sumService+$sumOrderItem);
        }
//        dd($order);
        Order::query()->where('id',$order->id)->update(['total_amount'=>$order['total_amount']]);
        $payable=$this->checkPayableOrTotal($order->id);
        $roomCode=$this->roomCode($order->id);
        $services=Service::all();
        return view(self::PATH_VIEW, compact('order',
            'orderItemInfo','servicesInfo','sumService',
            'sumOrderItem','payable','voucher','services',
            'roomCode'
        ));
    }

    public function checkPayableOrTotal($idOrder)
    {
        $checkPayableRoom = $this->payableMoneyRoom($idOrder);
        $checkPayableService = $this->payableMoneyService($idOrder);
        $payable = 0;

        if ($checkPayableRoom && $checkPayableService) {
            $payable = $checkPayableRoom->priceBooking + $checkPayableService;
        } elseif (!$checkPayableRoom && $checkPayableService) {
            $payable = $checkPayableService;
        } elseif ($checkPayableRoom && !$checkPayableService) {
            $payable = $checkPayableRoom->priceBooking;
        }
        return $payable;
    }

    public function payableMoneyRoom($idOrder){
        $order = Order::query()
            ->select('orders.booking_fee as priceBooking', 'orders.status as statusOrder')
            ->where('orders.status', 1)
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
            ->select('booking_services.id as idService','booking_services.price as priceService',
                'booking_services.status as statusService',
                'booking_services.quantity as quantityService',
            )
            ->join('orders', function ($join) {
                $join->on('orders.id', '=', 'booking_services.order_id');
            })
            ->where('booking_services.status',
                1)
            ->where('orders.id', $idOrder)
            ->get();
        if(!$bookingServices->isEmpty())
        {
            $total = 0;
            foreach($bookingServices as $bookingService){
                $total += $bookingService->priceService * $bookingService->quantityService;
            }
            return $total;
        }
        else
        {
            return false;
        }
    }
    public function updatePayable($idOrder,$payable){
        Order::query()->where('id', $idOrder)
            ->update(['payable' => $payable]);
    }

    public function updateStatus($idOrder){
        $order = Order::query()->where('id', $idOrder)->first();
        $isCheckout=$this->isCheckout($order);
        if ($isCheckout['is_valid_checkout']){
            $this->updateStatusGeneral('booking_services',$idOrder);
            $this->updateStatusGeneral('orders',$idOrder);
            $user=User::query()->update([
                'rank'=>1,
                'total_amount_ordered'=>$order->total_amount,
            ]);
            $incidental_costs=$this->calculateLateCheckoutFee($order);
            $order->update(['incidental_costs'=>$incidental_costs]);
            return redirect()->back()->with(
                ['success'=>'Checkout thành công',
                    'incidental_costs'=>$incidental_costs
                ]);
        }else{
            return redirect()->back()->with(
                ['error'=>'Không trong thời gian checkout (5:00 đến 11:30)',]);
        }
    }

    public function updateStatusGeneral($table,$idOrder){
        if($table == 'booking_services'){
            DB::table($table)->where('order_id', $idOrder)
                ->update(['status' => StatusOrderEnum::HOAN_THANH->value]);
        }else{
            DB::table($table)->where('id', $idOrder)
                ->update([
                    'status' => StatusOrderEnum::HOAN_THANH->value,
                    'check_out' => Carbon::now()
                ]);
        }
    }

    public function servicesInfo($orderId)
    {
        try {
            $result = Order::where('orders.id', $orderId)
                    ->join('booking_services', 'booking_services.order_id', '=', 'orders.id')
                    ->join('services', 'services.id', '=', 'booking_services.service_id')
                    ->select(
                    'services.name as serviceName',
                    'booking_services.quantity as serviceQuantity',
                    'booking_services.price as servicePrice',
                    'booking_services.status as status',
                )
                ->get();
            return $result;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function orderItemInfo($orderId)
    {
        try {
            $result = Order::where('orders.id', $orderId)
                ->join('order_items', 'order_items.order_id', '=', 'orders.id')
                ->join('rooms', 'order_items.room_id', '=', 'rooms.id')
                ->join('catalogue_rooms', 'catalogue_rooms.id', '=', 'rooms.catalogue_room_id')
                ->select('catalogue_rooms.name as catalogueName', 'catalogue_rooms.price as cataloguePrice'
                    , 'rooms.code as roomCode', 'order_items.quantity as orderItemQuantity')
                ->get();
            return $result;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }
    public function checkinOrder($orderId)
    {
        $order=Order::query()->where('id', $orderId)->first();
        $isCheckin=$this->isCheckin($order);
        if ($isCheckin['is_valid_checkin']){
            Order::query()->where('id', $orderId)->update(['check_in' => Carbon::now()]);
            return redirect()->back()->with('success','Checkin thành công');
        }else{
            return redirect()->back()->with('error','Checkin thất bại(không trong thời gian 14:00 đến 00:00)');
        }
    }
    public function VoucherOrder($voucherId){
        $voucher=Voucher::query()->where('vouchers.id', $voucherId)
            ->select('vouchers.description',
                'vouchers.discount_value','vouchers.code')->get()
        ;
        return $voucher;
    }
    public function roomCode($orderId)
    {
        $result=OrderItem::query()->where('order_id', $orderId)
            ->join('rooms', 'rooms.id', '=', 'order_items.room_id')
            ->select('rooms.code as roomCode','rooms.id as roomId')->get();
        return $result;
    }

    public function calculateLateCheckoutFee($order) {
        $checkoutEnd = Carbon::parse($order->end_date);
        $actualCheckout = Carbon::now();

        if ($actualCheckout->greaterThan($checkoutEnd)) {
            $extraHours = $checkoutEnd->diffInHours($actualCheckout);

            $extraFeePerHour = 100000;

            return $extraHours * $extraFeePerHour;
        }
        return 0;
    }

    public function isCheckin($order)
    {
        $currentTime = Carbon::now(); // Thời gian hiện tại

        // Lấy thời gian check-in dự kiến
        $checkinStartTime = Carbon::createFromTimeString(CHECKIN_START); // 14:00
        $checkinEndTime = Carbon::createFromTimeString(CHECKIN_END); // 00:00

        // Nếu thời gian kết thúc nhỏ hơn thời gian bắt đầu, nghĩa là khoảng thời gian qua ngày
        if ($checkinEndTime->lt($checkinStartTime)) {
            $isValidCheckinTime = $currentTime->between($checkinStartTime, Carbon::createFromTime(23, 59, 59)) ||
                $currentTime->between(Carbon::createFromTime(0, 0, 0), $checkinEndTime);
        } else {
            // Xử lý bình thường nếu không qua ngày
            $isValidCheckinTime = $currentTime->between($checkinStartTime, $checkinEndTime);
        }

        // Lưu kết quả kiểm tra cho order
        return [
            'order_id' => $order->id,
            'is_valid_checkin' => $isValidCheckinTime,
        ];
    }

    public function isCheckout($order)
    {
        $currentTime = Carbon::now(); // Thời gian hiện tại

        // Lấy thời gian checkout dự kiến
        $checkoutStartTime = Carbon::createFromTimeString(CHECKOUT_START); // 05:00
        $checkoutEndTime = Carbon::createFromTimeString(CHECKOUT_END); // 11:30

        // Kiểm tra nếu thời gian hiện tại nằm trong khoảng checkout
        $isValidCheckoutTime = $currentTime->between($checkoutStartTime, $checkoutEndTime);

        // Lưu kết quả kiểm tra cho order
        return [
            'order_id' => $order->id,
            'is_valid_checkout' => $isValidCheckoutTime,
        ];
    }
}