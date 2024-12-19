<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\StatusOrderEnum;
use App\Http\Controllers\Controller;
use App\Models\BookingService;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

define('CHECKIN_START', '14:00');
define('CHECKIN_END', '00:00');
define('CHECKOUT_START', '05:00');
define('CHECKOUT_END', '11:30');

class OrderDetailController extends Controller
{
    const PATH_VIEW = 'admin.orders.view';

    public function showOrderDetail(Order $order)
    {
        if (Auth::user()->type==User::HOTELIER&&$order->org_id==Auth::user()->org_id||
            Auth::user()->type==User::STAFF&&$order->org_id==Auth::user()->org_id ||
            Auth::user()->type==User::ADMIN){
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
//        dd($servicesInfo);
            $sumServiceNotPayment=0;
        foreach ($orderItemInfo as $item){
            $sumOrderItem+=$item->totalQuantity*$item->cataloguePrice;
        }
        foreach ($servicesInfo as $item){
            $sumService+=$item->servicePrice;
            if ($item->status==1){
                $sumServiceNotPayment+=$item->servicePrice;
            }
        }
//dd($sumServiceNotPayment);
        if ($order->voucher_id!=null){
            $voucher=$this->VoucherOrder($order->voucher_id);
            foreach ($voucher as $item){
                if ($item['discount_type']){
                    if ((($sumService+$sumOrderItem)*$item['discount_value'])/100>$item['max_price']){
                        $order['net_amount']=($sumService+$sumOrderItem)-$item['max_price'];
                        session(['voucherValue'=>$item['max_price']]);
                    }else{
                        $order['net_amount']=($sumService+$sumOrderItem)-(($sumService+$sumOrderItem)*$item['discount_value'])/100;
                        session(['voucherValue'=>(($sumService+$sumOrderItem)*$item['discount_value'])/100]);
                    }
                }else{
                    $order['net_amount']=($sumService+$sumOrderItem)-$item['discount_value'];
                    session(['voucherValue'=>$item['discount_value']]);
                }
                $order['voucher_id']=$item->code;
            }
        }else{
            $voucher=null;
            session([
                'voucherValue'=>0
            ]);
            $order['net_amount']=($sumService+$sumOrderItem);
        }
        $payable=$this->checkPayableOrTotal($order->id);
        $roomCode=$this->roomCode($order->id);
//        $services=$this->availableServices($order->id);
        return view(self::PATH_VIEW, compact('order',
            'orderItemInfo','servicesInfo','sumService',
            'sumOrderItem','payable','voucher',
            'roomCode','sumServiceNotPayment'
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
            $incidental_costs=$this->calculateLateCheckoutFee($order)['incidental_costs'];
            $percent_incidental=$this->calculateLateCheckoutFee($order)['percent_incidental'];
            $extraHours=$this->calculateLateCheckoutFee($order)['extraHours'];
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
            $this->updateStatusGeneral($idOrder,$incidental_costs,$order->total_amount);
            return redirect()->back()->with(
                ['success-checkout'=>'Checkout thành công',
                    'success'=>'Checkout thành công',
                    'incidental_costs'=>$incidental_costs,
                    'percent_incidental'=>$percent_incidental,
                    'extraHours'=>$extraHours
                ]);
        }else{
            return redirect()->back()->with(
                ['error'=>$isCheckout['message']]);
        }
    }

    public function updateStatusGeneral($idOrder,$incidental_costs=0,$total_amount=0){
            DB::table('booking_services')->where('order_id', $idOrder)
                ->update(['status' => 2]);
            DB::table('orders')->where('id', $idOrder)
                ->update([
                    'incidental_costs'=>$incidental_costs,
                    'status' => StatusOrderEnum::HOAN_THANH->value,
                    'net_amount'=>($incidental_costs+$total_amount)-session('voucherValue'),
                    'check_out' => Carbon::now()
                ]);
        session()->forget('voucherValue');
    }

    public function servicesInfo($orderId)
    {
        try {
            $result = Order::where('orders.id', $orderId)
                ->join('booking_services', 'booking_services.order_id', '=', 'orders.id')
                ->join('rooms', 'rooms.id', '=', 'booking_services.room_id')
                ->join('services', 'services.id', '=', 'booking_services.service_id')
//                ->where('services.status',1)
                ->select(
                    'rooms.code as roomCode',
                    'booking_services.id as bookingServiceId',
                    'services.name as serviceName',
                    'booking_services.quantity as serviceQuantity',
                    'services.price as servicePrice',
                    'booking_services.status as status',
                )
                ->latest('booking_services.created_at')
                ->get();
            return $result;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

    public function availableServices(Request $request, $orderId)
    {
        try {
            $roomId = $request->query('roomId');
            $hotel_id=Order::query()->where('id', $orderId)->value('org_id');
//            dd($hotel_id);
            // Truy vấn các dịch vụ trống liên quan đến phòng và đơn hàng
            $services = Service::leftJoin('booking_services', function ($join) use ($orderId, $roomId) {
                $join->on('services.id', '=', 'booking_services.service_id')
                    ->where('booking_services.order_id', '=', $orderId)
                    ->where('booking_services.room_id', '=', $roomId)
                ;
            })
                ->select('services.id', 'services.name', 'services.price')
                ->where('services.hotel_id', $hotel_id)
                ->where('services.type', 1)
                ->whereNull('booking_services.service_id')
                ->get();

            return response()->json(['services' => $services], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

//check service cũ
//    public function availableServices($orderId)
//    {
//        try {
//            $result = Service::leftJoin('booking_services', function ($join) use ($orderId) {
//                $join->on('services.id', '=', 'booking_services.service_id')
//                    ->where('booking_services.order_id', '=', $orderId);
//            })
//                ->select(
//                    'services.id as id',
//                    'services.name as name',
//                    'services.price as price',
//                    'booking_services.status as status'
//                )
//                ->whereNull('booking_services.service_id')
//                ->get();
//            return $result;
//        } catch (\Exception $exception) {
//            return $exception->getMessage();
//        }
//    }

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
//            ->where('vouchers.status',1)
            ->select('vouchers.description','vouchers.discount_type','vouchers.max_price',
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
        $percent_incidental=Hotel::query()->select('percent_incidental')->where('id', $order->org_id)->first();

        if ($actualCheckout->greaterThan($checkoutEnd)) {
            $extraHours = $checkoutEnd->diffInHours($actualCheckout);

            $extraFeePerHour = $order->booking_fee*$percent_incidental->percent_incidental/100;

            return [ 'incidental_costs'=> $extraHours * $extraFeePerHour,
                'percent_incidental'=>$percent_incidental->percent_incidental,
                'extraHours'=>$extraHours
            ];
        }
        return [ 'incidental_costs'=> 0,
            'percent_incidental'=>$percent_incidental->percent_incidental,
            'extraHours'=>0
        ];
    }

    public function isCheckin($order)
    {
        $currentTime = Carbon::now();
        $startDate = Carbon::parse($order->start_date);
        $endDate = Carbon::parse($order->end_date)->endOfDay();

        $checkinStartTime = Carbon::createFromTimeString(CHECKIN_START);
        $checkinEndTime = Carbon::createFromTimeString(CHECKIN_END);

        if ($currentTime->lt($startDate)) {
            return [
                'order_id' => $order->id,
                'is_valid_checkin' => false,
                'message' => 'Chưa đến ngày check-in.',
            ];
        }

        if ($currentTime->gt($endDate)) {
            return [
                'order_id' => $order->id,
                'is_valid_checkin' => false,
                'message' => 'Đã quá hạn ngày check-in.',
            ];
        }

        $checkinStartDateTime = $currentTime->copy()->setTimeFrom($checkinStartTime);
        $checkinEndDateTime = $currentTime->copy()->setTimeFrom($checkinEndTime);

        if ($checkinEndTime->lt($checkinStartTime)) {
            $checkinEndDateTime = $checkinEndDateTime->addDay();
        }

        $isValidCheckinTime = $currentTime->between($checkinStartDateTime, $checkinEndDateTime);

        return [
            'order_id' => $order->id,
            'is_valid_checkin' => $isValidCheckinTime,
            'message' => $isValidCheckinTime
                ? 'Thời gian hợp lệ để check-in.'
                : 'Check-in thất bại (không trong khoảng thời gian 14:00 đến 00:00).',
        ];
    }

    public function isCheckout($order)
    {
        $currentTime = Carbon::now();

        $checkinTime = $order->check_in;

        if (!$checkinTime) {
            return [
                'order_id' => $order->id,
                'is_valid_checkout' => false,
                'message' => 'Người dùng chưa thực hiện check-in.',
            ];
        }

        $checkoutStartTime = Carbon::createFromTimeString(CHECKOUT_START); // 05:00
        $checkoutEndTime = Carbon::createFromTimeString(CHECKOUT_END); // 11:30

        $isValidCheckoutTime = $currentTime->between($checkoutStartTime, $checkoutEndTime);

        $isCheckoutAfterCheckin = $currentTime->greaterThan($checkinTime);

        return [
            'order_id' => $order->id,
            'is_valid_checkout' => $isValidCheckoutTime && $isCheckoutAfterCheckin,
            'message' => !$isCheckoutAfterCheckin
                ? 'Thời gian checkout phải lớn hơn thời gian check-in.'
                : (!$isValidCheckoutTime ? 'Không trong thời gian cho phép checkout (05:00 đến 11:30).' : 'Thời gian hợp lệ.'),
        ];
    }

}