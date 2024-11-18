<?php

namespace App\Services\impl;

use App\Constant\Enum\StatusOrderEnum;
use App\Constant\Enum\TypeCodeEnum;
use App\Events\OrderSuccess;
use App\Exceptions\RespException;
use App\Helpers\Constant;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderSearchRequest;
use App\Models\BookingService;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\BookingService\BookingServiceRepository;
use App\Repositories\Hotel\HotelRepository;
use App\Repositories\HotelService\HotelServiceRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Room\RoomRepository;
use App\Repositories\Voucher\VoucherRepository;
use App\Services\CommonKeyCodeService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OrderServiceImpl implements OrderService
{

    private HotelRepository $hotelRepos;
    private VoucherRepository $voucherRepos;
    private CommonKeyCodeService $commonKeyCodeService;
    private RoomRepository $roomRepos;
    private HotelServiceRepository $hotelServiceRepos;
    private OrderRepository $orderRepos;
    private BookingServiceRepository $bookingServiceRepos;

    /**
     * @param HotelRepository $hotelRepos
     */
    public function __construct(
        HotelRepository          $hotelRepos,
        VoucherRepository        $voucherRepos,
        RoomRepository           $roomRepos,
        CommonKeyCodeService     $commonKeyCodeService,
        HotelServiceRepository   $hotelServiceRepos,
        OrderRepository          $orderRepos,
        BookingServiceRepository $bookingServiceRepos
    )
    {
        $this->hotelRepos = $hotelRepos;
        $this->voucherRepos = $voucherRepos;
        $this->roomRepos = $roomRepos;
        $this->commonKeyCodeService = $commonKeyCodeService;
        $this->hotelServiceRepos = $hotelServiceRepos;
        $this->orderRepos = $orderRepos;
        $this->bookingServiceRepos = $bookingServiceRepos;
    }


    /**
     * @throws RespException
     */
    public function create(OrderRequest $request)
    {
        $data = $request->validated();
        $this->validateBeforeSave($data);

        $order = new Order();
        $this->createOrder($order, $data);
        $this->handleOrderItem($data, $order);

        return $this->handlePaymentOrder($order);
    }

    /**
     * @throws RespException
     */
    private function handleOrderItem($orderRequest, $order): void
    {
        $orderItems = [];
        $bookingServices = [];
        $orderItemReqs = $orderRequest['order_items'];
        $serviceMapById = $this->getServiceMapById($order['org_id'], $orderItemReqs);
        $roomMapById = $this->getRoomMapById($order['org_id'], $orderRequest);
        $totalServiceAmount = 0;
        $totalBookingFee = 0;
        foreach ($orderItemReqs as $item) {
            $roomEntity = $roomMapById[$item['room_id']];
            $this->createOrderItem($orderItems, $item, $roomEntity, $order['id']);
            $serviceAmount = $this->createBookingServices($bookingServices, $item, $serviceMapById, $order['org_id'], $item['room_id']);

            $totalBookingFee += $roomEntity['price'] * 1;
            $totalServiceAmount += $serviceAmount;
        }

        $order->total_amount = $totalServiceAmount + $totalBookingFee;
        $order->booking_fee = $totalBookingFee;

        $order->orderItem()->saveMany($orderItems);
        $order->bookingService()->saveMany($bookingServices);
    }

    /**
     * @throws RespException
     */
    private function createOrderItem(&$orderItems, $oderItemReq, $room, $orderId): void
    {
        if (is_null($room)) {
            throw new RespException(trans('messages.room_not_found', ['room_code' => $oderItemReq["room_code"]]));
        }

        $orderItem = new OrderItem();
        $orderItem->order_id = $orderId;
        $orderItem->quantity = 1;
        $orderItem->room_id = $room['id'];
        $orderItem->room_codes = $room['code'];

        $orderItems[] = $orderItem;
    }

    /**
     * @throws RespException
     */
    private function createBookingServices(&$bookingServices, $orderItemReqs, $serviceMapById, $orderId, $roomId): float|int
    {
        $totalServicesAmount = 0;
        foreach ($orderItemReqs["services"] as $bookingServicesReq) {
            $bookingService = $this->createBookingService($bookingServicesReq, $serviceMapById[$bookingServicesReq['service_id']], $orderId, $roomId);
            $totalServicesAmount += $bookingService['quantity'] * $bookingService['price'];
            $bookingServices[] = $bookingService;
        }


        return $totalServicesAmount;
    }

    /**
     * @throws RespException
     */
    private function createBookingService($bookingServicesReq, $serviceEntity, $orderId, $roomId)
    {
        if (is_null($serviceEntity)) {
            throw new RespException(trans('messages.service_not_found', ['service_name' => $bookingServicesReq["service_name"]]));
        }

        $bookingService = new BookingService();

        $bookingService->order_id = $orderId;
        $bookingService->service_id = $bookingServicesReq["service_id"];
        $bookingService->quantity = $bookingServicesReq["service_quantity"];
        $bookingService->price = $serviceEntity["price"];
        $bookingService->status = StatusOrderEnum::CHUA_THANH_TOAN->value;
        $bookingService->room_id = $roomId;

        return $bookingService;
    }

    private function createOrder($order, $data): void
    {
        $order->id = Str::uuid();
        $order->org_id = $data['hotel_id'];
        $order->voucher_id = $data['voucher_id'];
        $order->phone = $data['user_phone_number'];
        $order->email = $data['user_email'];
        $order->name = $data['user_name'];
        $order->code = $this->commonKeyCodeService->genNewKeyCode(
            TypeCodeEnum::ORDER_TYPE->value,
            Constant::STRING_6_CHAR,
            $data['hotel_id']
        );
        $order->status = StatusOrderEnum::CHUA_THANH_TOAN->value;
        $order->start_date = $data['start_date'];
        $order->end_date = $data['end_date'];
        $order->note = $data['note'];
        $order->incidental_costs = 0;
    }

    /**
     * @throws RespException
     */
    private function validateBeforeSave(array $data): void
    {
        $this->validateHotel($data["hotel_id"]);
        $this->validateVoucher($data["voucher_id"], $data["hotel_id"]);
    }

    /**
     * @throws RespException
     */
    private function validateHotel($hotelId): void
    {
        $isValid = $this->hotelRepos->existsById($hotelId);
        if (!$isValid) {
            throw new RespException(__('messages.hotel_not_found'));
        }
    }

    /**
     * @throws RespException
     */
    private function validateVoucher($voucherId, $orgId): void
    {
        if (is_null($voucherId)) {
            return;
        }

        $isValid = $this->voucherRepos->existsByIdAndOrgId($voucherId, $orgId);
        if (!$isValid) {
            throw new RespException(__('messages.voucher_not_found'));
        }
    }

    /**
     * @throws RespException
     */
    private function getRoomMapById($orgId, $orderRequest): Collection
    {
        $roomIds = array_map(function ($room) {
            return $room['room_id'] ?? null;
        }, $orderRequest['order_items']);

        $rooms = $this->roomRepos->getRoomAvailableByIdInAndOrgId($orgId, $roomIds, $orderRequest['start_date'], $orderRequest['end_date']);

        if (empty($rooms->toArray())) {
            throw new RespException(__('messages.room_not_found'));
        }

        return collect($rooms)->mapWithKeys(function ($item) {
            return [$item['id'] => $item];
        });
    }

    /**
     * @throws RespException
     */
    private function getServiceMapById($orgId, $orderItems): Collection
    {
        $serviceIds = collect($orderItems)->flatMap(function ($item) {
            return collect($item['services'])->pluck('service_id');
        });

        $services = $this->hotelServiceRepos->getByOrgIdAndIds($orgId, $serviceIds);

        if (empty($services->toArray())) {
            throw new RespException(trans('messages.service_not_found', ['service_name' => ""]));
        }

        return collect($services)->mapWithKeys(function ($item) {
            return [$item['id'] => $item];
        });
    }

    /**
     * @throws RespException
     */
    public function handlePaymentOrder($order): string
    {
        $data = $this->generateUrlRedirect($order['total_amount']);
        $order->transaction_id = $data['vnp_TxnRef'];
        $order->save();

        return $data['vnp_Url'];
    }

    public function generateUrlRedirect($totalAmount): array
    {
        $vnp_TmnCode = env('VNP_TMNCODE');
        $vnp_HashSecret = env('VNP_HASHSECRET');
        $vnp_Url = env('VNP_URL');
        $vnp_Returnurl = env('VNP_RETURNURL');

        $vnp_TxnRef = 'MRD' . rand(00, 9999);
        $vnp_OrderInfo = "Thanh toán đặt phòng khách sạn";
        $vnp_OrderType = "vnpay";
        $vnp_Amount = $totalAmount * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_createDate = date('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_createDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return [
            'vnp_Url' => $vnp_Url,
            'vnp_TxnRef' => $vnp_TxnRef
        ];
    }

    public function paymentReturn(Request $request)
    {
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->except('vnp_SecureHash');
        $secureHash = $this->getSecureHash($inputData);

        $dataResp = [];

        if ($secureHash == $vnp_SecureHash) {

            $order = $this->orderRepos->searchByPage(['transaction_id' => $inputData['vnp_TxnRef']], false);
            $dataResp['order'] = $order;
            if ($order && $inputData['vnp_ResponseCode'] == '00') {
                $this->handleWhenPaymentSuccess($order);
                $dataResp['status'] = true;
            } else {
                $dataResp['status'] = false;
            }
            return $dataResp;
        }

        return [
            'status' => false,
            'order' => null
        ];
    }

    private function getSecureHash(array $inputData)
    {
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $vnp_HashSecret = env('VNP_HASHSECRET');

        return hash_hmac('sha512', $hashData, $vnp_HashSecret);
    }

    private function handleWhenPaymentSuccess($order): void
    {
        $this->bookingServiceRepos->updateStatusByOrderId(StatusOrderEnum::DA_THANH_TOAN->value, $order['id']);
        $this->orderRepos->updateStatusById(StatusOrderEnum::DA_THANH_TOAN->value, $order['id']);
//        //Send mail hóa đơn
        OrderSuccess::dispatch($order);
    }

    public function getTotalOrderMapByCityId(array $cityIds)
    {
        if (empty($cityIds)) {
            return [];
        }
        $totalOrders = $this->orderRepos->getTotalOrdersMapByCityId($cityIds);

        return collect($totalOrders)->mapWithKeys(function ($item) {
            return [$item['city_id'] => $item];
        });
    }

    /**
     * @throws RespException
     */
    public function searchByPage(OrderSearchRequest $request)
    {
        $data = $request->validated();
        //        if (!Auth::check()) {
//            throw new RespException(__('messages.you_have_not_permission'));
//        }

        //        $data['user_id'] = auth()->user()->id;

        return $this->orderRepos->searchByPage($data, true);
    }

    /**
     * @throws RespException
     */
    public function paymentOrder($orderId): string
    {
        $order = $this->getNonNullById($orderId);
        $this->validateBeforePayment($order);
        return $this->handlePaymentOrder($order);
    }

    /**
     * @throws RespException
     */
    private function getNonNullById($orderId)
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
    private function validateBeforePayment($order)
    {
        if (!StatusOrderEnum::isChuaThanhToan($order['status'])) {
            throw new RespException(__('messages.payment_has_been_made'));
        }
    }


}