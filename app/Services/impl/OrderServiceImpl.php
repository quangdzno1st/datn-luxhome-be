<?php

namespace App\Services\impl;

use App\Constant\Enum\HttpStatusCodeEnum;
use App\Constant\Enum\StatusOrderEnum;
use App\Constant\Enum\TypeCodeEnum;
use App\Exceptions\RespException;
use App\Helpers\Constant;
use App\Http\Controllers\Api\BookingController;
use App\Http\Requests\OrderRequest;
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
    private BookingController $bookingController;

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
        BookingServiceRepository $bookingServiceRepos,
        BookingController        $bookingController
    )
    {
        $this->hotelRepos = $hotelRepos;
        $this->voucherRepos = $voucherRepos;
        $this->roomRepos = $roomRepos;
        $this->commonKeyCodeService = $commonKeyCodeService;
        $this->hotelServiceRepos = $hotelServiceRepos;
        $this->orderRepos = $orderRepos;
        $this->bookingServiceRepos = $bookingServiceRepos;
        $this->bookingController = $bookingController;
    }


    /**
     * @throws RespException
     */
    public function create(OrderRequest $request): string
    {
        $data = $request->validated();
        $this->validateBeforeSave($data);

        $order = new Order();
        $this->createOrder($order, $data);
        $this->handleOrderItem($data, $order);

        return $this->createVnPayUrl($order);
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
        $totalAmount = 0;
        $totalBookingFee = 0;
        foreach ($orderItemReqs as $item) {
            $roomEntity = $roomMapById[$item['room_id']];
            $this->createOrderItem($orderItems, $item, $roomEntity, $order['id']);
            $serviceAmount = $this->createBookingServices($bookingServices, $item, $serviceMapById, $order['org_id'], $item['room_id']);

            $totalBookingFee += $roomEntity['price'] * 1;
            $totalAmount += $serviceAmount + $totalBookingFee;
        }

        $order->total_amount = $totalAmount;
        $order->booking_fee = $totalBookingFee;

        $order->orderItem()->saveMany($orderItems);
        $order->bookingService()->saveMany($bookingServices);
    }

    private function createOrderItem(&$orderItems, $oderItemReq, $room, $orderId): void
    {
        if (is_null($room)) {
            throw new RespException(
                trans('messages.room_not_found', ['room_code' => $oderItemReq["room_code"]]),
                HttpStatusCodeEnum::NOT_FOUND->value
            );
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

    private function createBookingService($bookingServicesReq, $serviceEntity, $orderId, $roomId)
    {
        if (is_null($serviceEntity)) {
            throw new RespException(
                trans('messages.service_not_found', ['service_name' => $bookingServicesReq["service_name"]]),
                HttpStatusCodeEnum::NOT_FOUND->value
            );
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
            throw new RespException(__('messages.hotel_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
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
            throw new RespException(__('messages.voucher_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
        }
    }

    /**
     * @throws RespException
     */
    private function getRoomMapById($orgId, $orderRequest): \Illuminate\Support\Collection
    {
        $roomIds = array_map(function ($room) {
            return $room['room_id'] ?? null;
        }, $orderRequest['order_items']);

        $rooms = $this->roomRepos->getRoomAvailableByIdInAndOrgId($orgId, $roomIds, $orderRequest['start_date'], $orderRequest['end_date']);

        if (empty($rooms->toArray())) {
            throw new RespException(__('messages.room_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
        }

        return collect($rooms)->mapWithKeys(function ($item) {
            return [$item['id'] => $item];
        });
    }

    private function getServiceMapById($orgId, $orderItems): \Illuminate\Support\Collection
    {
        $serviceIds = collect($orderItems)->flatMap(function ($item) {
            return collect($item['services'])->pluck('service_id');
        });

        $services = $this->hotelServiceRepos->getByOrgIdAndIds($orgId, $serviceIds);

        if (empty($services->toArray())) {
            throw new RespException(
                trans('messages.service_not_found', ['service_name' => ""]),
                HttpStatusCodeEnum::NOT_FOUND->value
            );
        }

        return collect($services)->mapWithKeys(function ($item) {
            return [$item['id'] => $item];
        });
    }

    private function detail($id)
    {
        $order = $this->orderRepos->find($id);
        if (is_null($order)) {
            throw new RespException(__('messages.order_not_found'), HttpStatusCodeEnum::NOT_FOUND->value);
        }

        return $order;
    }

    private function createVnPayUrl(Order $order): string
    {
        $vnp_TmnCode = "ME3DBPPL";
        $vnp_HashSecret = "I4DW6LYA3KPCUK7ZYC1GR7054X59P7L3";
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/vnpay-return";

        $vnp_TxnRef = 'MRD' . rand(00, 9999);
        $vnp_OrderInfo = "Thanh toán đặt phòng khách sạn";
        $vnp_OrderType = "vnpay";
        $vnp_Amount = $order['total_amount'] * 100;
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

        $order->transaction_id = $vnp_TxnRef;
        $order->save();

        return $vnp_Url;
    }

    public function paymentReturn(Request $request)
    {
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = $request->except('vnp_SecureHash');
        ksort($inputData);
        $hashData = "";
        foreach ($inputData as $key => $value) {
            $hashData .= $key . '=' . $value . '&';
        }
        $hashData = rtrim($hashData, '&');
        $vnp_HashSecret = "VNPAY_HASH_SECRET";

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            $order = Order::where('transaction_id', $inputData['vnp_TxnRef'])->first();
            if ($order) {
                if ($inputData['vnp_ResponseCode'] == '00') {
                    $this->handleWhenPaymentSuccess($order);

                }
                return redirect('/payment-result?status=' . $order->status);
            }
        }
        return redirect('/payment-result?status=failed');
    }

    private function handleWhenPaymentSuccess($order): void
    {
        $order->status = StatusOrderEnum::DA_THANH_TOAN->value;
        $this->bookingServiceRepos->updateStatusByOrderId(StatusOrderEnum::DA_THANH_TOAN->value, $order['id']);
        $order->save();
        $this->bookingController->confirmBooking($order);
    }

}