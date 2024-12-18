<?php

namespace App\Services\impl;

use App\Constant\Enum\StatusOrderEnum;
use App\Constant\Enum\StatusPaymentOrderEnum;
use App\Events\OrderSuccess;
use App\Exceptions\RespException;
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
use App\Repositories\Wallet\WalletRepository;
use App\Services\CommonKeyCodeService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
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
    private WalletRepository $walletRepos;

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
        WalletRepository         $walletRepos,
    )
    {
        $this->hotelRepos = $hotelRepos;
        $this->voucherRepos = $voucherRepos;
        $this->roomRepos = $roomRepos;
        $this->commonKeyCodeService = $commonKeyCodeService;
        $this->hotelServiceRepos = $hotelServiceRepos;
        $this->orderRepos = $orderRepos;
        $this->bookingServiceRepos = $bookingServiceRepos;
        $this->walletRepos = $walletRepos;
    }


    /**
     * @throws RespException
     */
    public function create(OrderRequest $request)
    {
        $data = $request->validated();

        $data['hotel_id'] = session('hotel_id') ?? null;
        $this->validateBeforeSave($data);

        $order = new Order();
        $this->createOrder($order, $data);
        $this->handleOrderItem($order);

        return $this->handlePaymentOrder($order);
    }

    /**
     * @throws RespException
     */
    private function handleOrderItem($order): void
    {
        $orderItems = [];
        $bookingServices = [];
        $totalServiceAmount = 0;
        $totalBookingFee = 0;

        $orderItemReqs = $this->validateAngGetBookingData();
        $serviceReqs = session('service_booking') ?? null;
        $serviceMapById = $this->getServiceMapById($order['org_id']);
        $roomMapById = $this->getRoomMapById($order['org_id'], $orderItemReqs, $order);

        $start_date = $order['start_date'];
        $end_date = $order['end_date'];

        foreach ($orderItemReqs as $item) {
            $roomId = $item['room_id'];

            $this->validateCurrentRoomIsBooking($roomId, $start_date, $end_date);

            $roomEntity = $roomMapById[$roomId] ?? null;
            $this->createOrderItem($orderItems, $roomEntity, $order['id']);

            $serviceForRoom = $serviceReqs[$item['room_id']] ?? null;
            if (!empty($serviceForRoom)) {
                $serviceAmount = $this->createBookingServices(
                    $bookingServices,
                    $serviceForRoom,
                    $serviceMapById,
                    $order['org_id'],
                    $item['room_id']
                );

                $totalServiceAmount += $serviceAmount;
            }

            $totalBookingFee += $roomEntity['price'] * 1;
        }

        $order->total_amount = $totalServiceAmount + $totalBookingFee;

        $order->booking_fee = $totalBookingFee;

        $order->orderItem()->saveMany($orderItems);
        $order->bookingService()->saveMany($bookingServices);
    }

    private function validateAngGetBookingData()
    {
        $orderItemReqs = session('booking_data') ?? null;
        if (!isset($orderItemReqs)) {
            return redirect()->back()->with('error', 'Thông tin phòng cần đặt đang trống');
        }

        return $orderItemReqs;
    }

    /**
     * @throws RespException
     */
    private function createOrderItem(&$orderItems, $room, $orderId): void
    {
        if (is_null($room)) {
            throw new RespException(trans('messages.room_not_available'));
        }

        $orderItem = new OrderItem();
        $orderItem->order_id = $orderId;
        $orderItem->quantity = 1;
        $orderItem->room_id = $room['id'];

        $orderItems[] = $orderItem;
    }

    /**
     * @throws RespException
     */
    private function createBookingServices(&$bookingServices, $serviceReqs, $serviceMapById, $orderId, $roomId): float|int
    {
        $totalServicesAmount = 0;
        foreach ($serviceReqs as $serviceReqId) {
            $bookingService = $this->createBookingService($serviceReqId, $serviceMapById[$serviceReqId], $orderId, $roomId);
            $totalServicesAmount += $bookingService['quantity'] * $bookingService['price'];
            $bookingServices[] = $bookingService;
        }

        return $totalServicesAmount;
    }

    /**
     * @throws RespException
     */
    private function createBookingService($serviceId, $serviceEntity, $orderId, $roomId)
    {
        if (is_null($serviceEntity)) {
            throw new RespException(trans('messages.service_not_found'));
        }

        $bookingService = new BookingService();

        $bookingService->order_id = $orderId;
        $bookingService->service_id = $serviceId;
        $bookingService->quantity = 1;
        $bookingService->price = $serviceEntity["price"];
        $bookingService->status = StatusOrderEnum::DANG_CHO->value;
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
        $order->user_id = Auth::user()?->id ?? null;
        $order->name = $data['user_name'];
        $order->code = Str::upper(Str::random(10));

        $order->status = StatusOrderEnum::DANG_CHO->value;
        $order->status_payment = StatusPaymentOrderEnum::CHUA_THANH_TOAN->value;
        $order->start_date = Carbon::createFromFormat('Y-m-d', session('start_date'))->setTime(14, 00);
        $order->end_date = Carbon::createFromFormat('Y-m-d', session('end_date'))->setTime(12, 00);
        $order->note = $data['note'];
        $order->incidental_costs = 0;
    }

    /**
     * @throws RespException
     */
    private function validateBeforeSave(array $data): void
    {
        $this->validateHotel($data["hotel_id"]);
        $this->validateVoucher($data["voucher_id"], $data["hotel_id"], $data['total_amount']);
    }

    /**
     * @throws RespException
     */
    private function validateCurrentRoomIsBooking($roomId, $startDate, $endDate)
    {
        $startTime = Carbon::parse($startDate);
        $endTime = Carbon::parse($endDate);

        $key = "room_{$roomId}_booked_times";

        $existingBookings = Redis::lrange($key, 0, -1);

        foreach ($existingBookings as $booking) {
            $bookingData = json_decode($booking, true);
            $existingStartTime = Carbon::parse($bookingData['start_time']);
            $existingEndTime = Carbon::parse($bookingData['end_time']);

            if ($startTime < $existingEndTime && $endTime > $existingStartTime) {
                throw new RespException(trans('Phòng không còn trống trong khách sạn.'));
            }
        }

        $bookingData = json_encode([
            'start_time' => $startTime->toDateTimeString(),
            'end_time' => $endTime->toDateTimeString()
        ]);

        Redis::rpush($key, $bookingData);
        Redis::expire($key, 20);
    }

    private function generateUniqueKey($username, $email, $phone)
    {
        $separator = "|";
        return $username . $separator . $email . $separator . $phone;
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
    private function validateVoucher($voucherId, $orgId, $orderTotalAmount): void
    {
        if (is_null($voucherId)) {
            return;
        }

        $user = Auth::user();
        if (is_null($user)) {
            throw new RespException("Người dùng chưa đăng nhập không thể dùng phiếu giảm giá.");
        }

        $voucher = $this->voucherRepos->getInvalidVoucherByUserIdAndVoucherId($voucherId, $orgId, $orderTotalAmount, $user['id']);
        if (empty($voucher) || empty($voucher[0])) {
            throw new RespException(__('messages.voucher_not_found'));
        }
    }

    /**
     * @throws RespException
     */
    private function getRoomMapById($orgId, $orderItemReqs, $order): Collection
    {

        $roomIds = array_map(function ($room) {
            return $room['room_id'] ?? null;
        }, $orderItemReqs);


        $rooms = $this->roomRepos->getRoomAvailableByIdInAndOrgId($orgId, $roomIds, $order['start_date'], $order['end_date']);
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
    private function getServiceMapById($orgId): Collection
    {
        $data = session('service_booking') ?? null;// lưu trữ thông tin đặt phòng và địch vụ
        if (!isset($data)) {
            return collect();
        }

        $serviceIds = array_unique(array_merge(...array_values($data)));
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
        $data = $this->generateUrlRedirect($this->getTotalAmountDiscount($order['total_amount'], $order['voucher_id'], $order['org_id']));
        $order->transaction_id = $data['vnp_TxnRef'];
        session('transaction_id', $data['vnp_TxnRef']);
        $order->save();

        return $data['vnp_Url'];
    }

    public function getTotalAmountDiscount($totalAmount, $voucherId, $orgId)
    {

        $user = Auth::user();
        if (is_null($voucherId) || is_null($user)) {
            return $totalAmount;
        }

        $discountAmount = 0;
        $voucher = $this->voucherRepos->getInvalidVoucherByUserIdAndVoucherId($voucherId, $orgId, $totalAmount, $user['id']);

        if ($voucher[0]['discount_type'] == 1) {
            $discountAmount = $totalAmount * ($voucher[0]['discount_value'] / 100);
            $discountAmount = (min($discountAmount, $voucher[0]['max_price']));
        } else {
            $discountAmount = $voucher[0]['discount_value'];
        }

        return $totalAmount - $discountAmount;
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
        $this->bookingServiceRepos->updateStatusByOrderId(StatusOrderEnum::DA_XAC_NHAN->value, $order['id']);
        $this->orderRepos->updateStatusById(
            StatusOrderEnum::DA_XAC_NHAN->value,
            StatusPaymentOrderEnum::DA_THANH_TOAN,
            $order['id']
        );
        $this->handleVoucherWhenOrderSuccess($order['voucher_id']);
        //        //Send mail hóa đơn
        OrderSuccess::dispatch($order);
    }

    private function handleVoucherWhenOrderSuccess($voucherId)
    {
        $this->voucherRepos->decrement(['id' => $voucherId], 'quantity');
        $userId = Auth::user()?->id;

        if (!is_null($userId)) {
            $this->walletRepos->deleteWhere([
                'user_id' => $userId,
                'voucher_id' => $voucherId
            ]);
        }
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
        if (!Auth::check()) {
            throw new RespException(__('messages.you_have_not_permission'));
        }

        $data['user_id'] = auth()->user()->id;

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
        if (!StatusOrderEnum::isDangCho($order['status'])) {
            throw new RespException(__('messages.payment_has_been_made'));
        }
        if ($order['start_date'] < Carbon::now()) {
            throw new RespException(__('messages.the_order_cannot_be_paid', ['order_name' => $order['code']]));
        }
        $this->validateAvailableRoom($order);
    }

    /**
     * @throws RespException
     */
    public function validateAvailableRoom($order)
    {
        $roomIds = $this->orderRepos->getRoomIdsById($order['id']);
        $roomAvailable = $this->roomRepos->getRoomBookedIdByIdIn($order['hotel_id'], $roomIds, $order['start_date'], $order['end_date']);
        if (!empty($roomAvailable)) {
            throw new RespException(__('messages.room_not_available'));
        }
    }

    /**
     * @throws RespException
     */
    public function getDataBookingOrder(Request $request)
    {
        $bookingData = $this->validateOrderQtyRequest($request);
        $dataSearch = session('search_data');
        if (empty($dataSearch)) {
            return redirect()->back()->with('error', 'Thông tin đặt phòng trống');
        }

        $dataResp = $this->handleBookingData($dataSearch, $bookingData);

        if (empty($dataResp)) {
            throw new RespException('Thông tin đặt phòng trống');
        }


        return $dataResp;
    }

    public function getRoomOrderQtyMapByCatalogueRoomId($roomsOrder)
    {
        $groupedRooms = [];

        foreach ($roomsOrder as $room) {
            $roomType = $room['catalogue_room_name'];
            $number_child = $room['number_child'];
            $number_adult = $room['number_adult'];

            $price = $room['price'];

            if (!isset($groupedRooms[$roomType])) {
                $groupedRooms[$roomType] = [
                    'catalogue_room_name' => $roomType,
                    'number_child' => $number_child,
                    'number_adult' => $number_adult,
                    'quantity' => 0,
                    'total_price' => 0,
                ];
            }

            $groupedRooms[$roomType]['quantity'] += 1;
            $groupedRooms[$roomType]['total_price'] += $price;
        }

        return array_values($groupedRooms);
    }


    private function validateOrderQtyRequest(Request $request): array
    {
        $data = $request->except('_token');

        $rules = [];
        $isInvalid = false;
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $isInvalid = true;
            } else {
                unset($data[$key]);
            }

            $rules[$key] = 'required';
        }

        if (!$isInvalid) {
            $request->validate($rules, ['required' => 'Số lượng đặt phòng không thể để trống']);
        }

        return $data;
    }

    /**
     * @throws RespException
     */
    private function handleBookingData(&$searchData, array $bookingsData)
    {
        $dataResp = [];
        foreach ($searchData as $item) {

            $roomQty = $bookingsData[$item['id']] ?? null;
            if (is_null($roomQty)) {
                continue;
            }

            if (sizeof($item['available_rooms']) < $roomQty) {
                throw new RespException(__('Số lượng phòng còn trống không đủ ' . $roomQty . ' phòng'));
            }

            $roomBooking = array_slice($item['available_rooms'], 0, $roomQty);
            $this->buildRoomBookingResp($dataResp, $roomBooking, $item);
        }

        if (!empty($dataResp)) {
            session(['booking_data' => $dataResp]);
        }

        return $dataResp;
    }

    private function clearSessionByKey($key)
    {
        if (!empty(session($key))) {
            session()->forget($key);
        }
    }

    private function buildRoomBookingResp(&$dataResp, $roomsBooking, $catalogueInformation)
    {
        foreach ($roomsBooking as $item) {
            $dataResp[] = [
                'room_id' => $item['room_id'],
                'code' => $item['code'],
                'catalogue_room_name' => $catalogueInformation['name'],
                'start_date' => $catalogueInformation['start_date'],
                'end_date' => $catalogueInformation['end_date'],
                'price' => $catalogueInformation['price'],
                'hotel_id' => $catalogueInformation['hotel_id'],
                'hotel_name' => $catalogueInformation['hotel_name'],
                'number_adult' => $catalogueInformation['number_adult'],
                'number_child' => $catalogueInformation['number_child']
            ];
        }
    }

    /**
     * @throws RespException
     */
    public function getDataBookingForConfirm(Request $request, $hotelId)
    {
        $this->clearSessionByKey('service_booking');
        $data = $this->getBookingServiceForRooms($request);
        if (empty($data)) {
            return [];
        }

        session(['service_booking' => $data]);// lưu trữ thông tin đặt phòng và địch vụ

        return $this->handleBookingForConfirmData($hotelId, $data);
    }

    public function getDataBookingForFinish($hotelId)
    {
        $data = session('service_booking');
        return $this->handleBookingForConfirmData($hotelId, $data);
    }


    public function handleBookingForConfirmData($hotelId, $data)
    {
        if (empty($data)) {
            return [];
        }

        $serviceBookingsQty = $this->handleCountService($data);// sử lý thông tin đầu ra hiển thị giao diện;'
        $serviceMapById = $this->getServiceMapById($hotelId);
        return [
            'serviceBookingsQty' => $serviceBookingsQty,
            'serviceMapById' => $serviceMapById
        ];
    }

    private function handleCountService($data)
    {
        $result = [];
        foreach ($data as $roomId => $services) {
            foreach ($services as $serviceId) {
                if (isset($result[$serviceId])) {
                    $result[$serviceId]++;
                } else {
                    $result[$serviceId] = 1;
                }
            }
        }

        return $result;
    }

    private function getBookingServiceForRooms($request)
    {
        $data = $request->input('services', []);
        $result = [];

        foreach ($data as $roomId => $services) {
            $result[$roomId] = array_keys($services); // Lấy danh sách các service_id
        }

        return $result;
    }

    /**
     * @throws RespException
     */
    public function cancelOrder($orderId): void
    {
        $order = $this->getNonNullById($orderId);
        $this->validateBeforeRequirementCancel($order);
        $this->orderRepos->updateWhenRequirementCancel(
            StatusOrderEnum::YEU_CAU_HUY->value,
            StatusPaymentOrderEnum::DA_THANH_TOAN,
            $orderId
        );
    }


    /**
     * @throws RespException
     */
    public function validateBeforeRequirementCancel($order): void
    {
        if (!StatusOrderEnum::isDaXacNhan($order['status'])) {
            throw new RespException('Đơn đặt ở trạng thái không thể hủy.');
        }

        if (isset($order['check_in'])) {
            throw new RespException('Không thể hủy đơn khi đã sử dụng phòng');
        }
    }

    /**
     * @throws RespException
     */
    public function getOrderById($orderId)
    {
        $order = $this->orderRepos->getById($orderId);

        if (empty($order)) {
            throw new RespException(__('messages.order_not_found'));
        }

        return $order;
    }

}