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
use Illuminate\Support\Facades\Auth;
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
        if (Auth::user()->type==User::HOTELIER&&$order->org_id==Auth::user()->org_id||Auth::user()->type==User::ADMIN){
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
            $sumOrderItem+=$item->totalQuantity*$item->cataloguePrice;
        }
        foreach ($servicesInfo as $item){
            $sumService+=$item->servicePrice;
        }

        if ($order->voucher_id!=null){
            $voucher=$this->VoucherOrder($order->voucher_id);
            foreach ($voucher as $item){
                if ($item['discount_type']){
                    if ((($sumService+$sumOrderItem)*$item['discount_value'])/100>$item['max_price']){
                        $order['total_amount']=($sumService+$sumOrderItem)-$item['max_price'];
                    }else{
                        $order['total_amount']=($sumService+$sumOrderItem)-(($sumService+$sumOrderItem)*$item['discount_value'])/100;
                    }
                }else{
                    $order['total_amount']=($sumService+$sumOrderItem)-$item['discount_value'];
                }
                $order['voucher_id']=$item->code;
            }
        }else{
            $voucher=null;
            $order['total_amount']=($sumService+$sumOrderItem);
//        dd($order['total_amount']);
        }
        Order::query()->where('id',$order->id)->update(['total_amount'=>$order['total_amount']]);
        $payable=$this->checkPayableOrTotal($order->id);
        $roomCode=$this->roomCode($order->id);
        $services=$this->availableServices($order->id);
        return view(self::PATH_VIEW, compact('order',
            'orderItemInfo','servicesInfo','sumService',
            'sumOrderItem','payable','voucher','services',
            'roomCode'
        ));
        }
        else{
            return redirect()->back()->with('error','Khách sạn bạn không quản lý đơn hàng này');
        }
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
            User::where('id', $order->user_id)
                ->update([
                    'rank' => 1,
                    'total_amount_ordered' => DB::raw('total_amount_ordered + ' . $order->total_amount),
                ]);
            $incidental_costs=$this->calculateLateCheckoutFee($order);
            $this->updateStatusGeneral('booking_services',$idOrder);
            $this->updateStatusGeneral('orders',$idOrder,$incidental_costs,$order->total_amount);
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

    public function updateStatusGeneral($table,$idOrder,$incidental_costs=null,$total_amount=null){
        if($table == 'booking_services'){
            DB::table($table)->where('order_id', $idOrder)
                ->update(['status' => StatusOrderEnum::HOAN_THANH->value]);
        }else{
            DB::table($table)->where('id', $idOrder)
                ->update([
                    'status' => StatusOrderEnum::HOAN_THANH->value,
                    'net_amount'=>$incidental_costs+$total_amount,
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
                    'services.price as servicePrice',
                    'booking_services.status as status',
                )
                ->get();
            return $result;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function availableServices($orderId)
    {
        try {
            $result = Service::leftJoin('booking_services', function ($join) use ($orderId) {
                $join->on('services.id', '=', 'booking_services.service_id')
                    ->where('booking_services.order_id', '=', $orderId);
            })
                ->select(
                    'services.id as id',
                    'services.name as name',
                    'services.price as price',
                    'booking_services.status as status'
                )
                ->whereNull('booking_services.service_id')
                ->get();
            return $result;
        } catch (\Exception $exception) {
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
                ->select(
                    'catalogue_rooms.name as catalogueName',
                    'catalogue_rooms.price as cataloguePrice',
                    DB::raw('GROUP_CONCAT(rooms.code SEPARATOR ", ") as roomCodes'),
                    DB::raw('SUM(order_items.quantity) as totalQuantity')
                )
                ->groupBy('catalogue_rooms.name', 'catalogue_rooms.price')
                ->get();

            return $result;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function checkinOrder($orderId)
    {
        $order=Order::query()->where('id', $orderId)->first();
        $isCheckin=$this->isCheckin($order);
        if ($isCheckin['is_valid_checkin']){
            Order::query()->where('id', $orderId)->update(['check_in' => Carbon::now()]);
            return redirect()->back()->with('success',$isCheckin['message']);
        }else{
            return redirect()->back()->with('error',$isCheckin['message']);
        }
    }
    public function VoucherOrder($voucherId){
        $voucher=Voucher::query()->where('vouchers.id', $voucherId)
            ->select('vouchers.description','vouchers.discount_type',
                'vouchers.discount_value','vouchers.code','vouchers.max_price')->get()
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
        $startDate = Carbon::parse($order->start_date); // Lấy ngày bắt đầu từ order

        // Lấy thời gian check-in dự kiến
        $checkinStartTime = Carbon::createFromTimeString(CHECKIN_START); // 14:00
        $checkinEndTime = Carbon::createFromTimeString(CHECKIN_END); // 00:00

        // Kiểm tra ngày hiện tại có đến ngày bắt đầu chưa
        if ($currentTime->lt($startDate)) {
            return [
                'order_id' => $order->id,
                'is_valid_checkin' => false,
                'message' => 'Chưa đến ngày check-in',
            ];
        }

        // Tính toán thời gian check-in trong ngày
        $checkinStartDateTime = $startDate->copy()->setTimeFrom($checkinStartTime); // Ngày bắt đầu + giờ check-in
        $checkinEndDateTime = $startDate->copy()->setTimeFrom($checkinEndTime); // Ngày bắt đầu + giờ kết thúc

        // Nếu thời gian kết thúc nhỏ hơn thời gian bắt đầu, xử lý qua ngày
        if ($checkinEndTime->lt($checkinStartTime)) {
            $isValidCheckinTime = $currentTime->between($checkinStartDateTime, $startDate->copy()->endOfDay()) ||
                $currentTime->between($startDate->copy()->addDay()->startOfDay(), $checkinEndDateTime);
        } else {
            // Xử lý bình thường nếu không qua ngày
            $isValidCheckinTime = $currentTime->between($checkinStartDateTime, $checkinEndDateTime);
        }

        return [
            'order_id' => $order->id,
            'is_valid_checkin' => $isValidCheckinTime,
            'message' => $isValidCheckinTime ? 'Thời gian hợp lệ để check-in' : 'Checkin thất bại(không trong thời gian 14:00 đến 00:00)',
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