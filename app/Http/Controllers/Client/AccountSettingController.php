<?php

namespace App\Http\Controllers\Client;

use App\Constant\Enum\ServiceTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderSearchRequest;
use App\Repositories\Voucher\VoucherRepository;
use App\Services\HotelServiceService;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        $user = Auth::user();

        return view('client.myaccount', compact('orders', 'user'));
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
    public function changeUserInfo(Request $request)
    {
        // dd($request->all());
        try {
            $message = [
                'name.required' => 'Vui lòng nhập tên tài khoản.',
                'name.max' => 'Tên tài khoản không được vượt quá :max ký tự.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Sai định dạng email.',
                'email.max' => 'Email không được vượt quá :max ký tự.',
                'email.unique' => 'Email này đã được sử dụng.',
                'phone.required' => 'Vui lòng nhập số điện thoại.',
                'phone.regex' => 'Số điện thoại không hợp lệ.'
            ];
            $validator = $request->validate([
                'name' => ['required', 'string','max:255'],
                'email' => ['required', 'string','email', 'unique:users,email,' . Auth::id(), 'max:255'],
                'phone' => ['required', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/']
            ], $message);
    
            $data = $request->all();
    
            $userUpdate = Auth::user();
    
            $userUpdate->update($data);
    
            return back()->with('msg', 'Cập nhật thông tin thành công');
       } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->with('error', 'Cập nhật thông tin không thành công!');
       }

    }

    public function changePassword(Request $request)
    {
       try {
            $message = [
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
                'password.max' => 'Mật khẩu không được vượt quá :max ký tự.'
            ];
            $data = $request->validate([
                'password' => ['required', 'string','confirmed', 'max:255']
            ], $message);

            $data['password'] = bcrypt($data['password']);
            
            $userUpdate = Auth::user();

            $userUpdate->update($data);

            return back()->with('msg', 'Thay đổi mật khẩu thành công');
       } catch (ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->with('error', 'Đổi mật khẩu không thành công!');
       }
    }

}
