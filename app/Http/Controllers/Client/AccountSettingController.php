<?php

namespace App\Http\Controllers\Client;

use App\Constant\Enum\ServiceTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderSearchRequest;
use App\Repositories\Voucher\VoucherRepository;
use App\Services\HotelServiceService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{

    private OrderService $orderService;
    private VoucherRepository $voucherRepos;
    private HotelServiceService $hotelServiceService;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService        $orderService,
                                VoucherRepository   $voucherRepos,
                                HotelServiceService $hotelServiceService)
    {
        $this->orderService = $orderService;
        $this->voucherRepos = $voucherRepos;
        $this->hotelServiceService = $hotelServiceService;
    }


    public function index(OrderSearchRequest $request)
    {
        $orders = $this->orderService->searchByPage($request);
        return view('client.myaccount', compact('orders'));
    }

    public function paymentOrder($orderId)
    {
        $urlRedirect = $this->orderService->paymentOrder($orderId);
        return redirect($urlRedirect);
    }

    public function paymentReturn(Request $request)
    {
        $data = $this->orderService->paymentReturn($request);
        $order = $data['order'];
        $status = $data['status'];
        return view('client.bookingfinish', compact('order', 'status'));
    }

    public function confirmOrder(Request $request)
    {
        $hotelId = session('hotel_id') ?? null;
        if (!isset($hotelId)) {
            return redirect()->back()->with('error', 'Thông tin khách sạn không xác định');
        }

        $roomsServiceOrder = $this->orderService->getDataBookingForConfirm($request, $hotelId);
        $vouchers = $this->voucherRepos->getAllForOrder(1200000, $hotelId);
        return view('client.booking', compact('vouchers'));
    }

    public function store(OrderRequest $request)
    {
        $paymentUrl = $this->orderService->create($request);
        return redirect($paymentUrl);
    }

    public function orderService(Request $request)
    {
        $roomsOrder = $this->orderService->getDataBookingOrder($request);
        $services = $this->hotelServiceService->getServicesByIdHotel($roomsOrder[0]['hotel_id'],
            new Request(['type' => ServiceTypeEnum::DICH_VU_TRA_PHI->value]));
        return view('client.bookingservice', compact('roomsOrder', 'services'));

    }
}
