<?php

namespace App\Http\Controllers\Client;

use App\Constant\Enum\ServiceTypeEnum;
use App\Constant\Enum\UserRankEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderSearchRequest;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\Rate;
use App\Models\User;
use App\Repositories\CatalogueRoom\CatalogueRoomRepository;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\Voucher\VoucherRepository;
use App\Services\HotelServiceService;
use App\Services\OrderService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccountSettingController extends Controller
{

    private OrderService $orderService;
    private VoucherRepository $voucherRepos;
    private HotelServiceService $hotelServiceService;
    private ServiceRepository $serviceRepos;

    /**
     * @param OrderService $orderService
     */
    public function __construct(OrderService            $orderService,
                                VoucherRepository       $voucherRepos,
                                HotelServiceService     $hotelServiceService,
                                CatalogueRoomRepository $catalogueRoomRepos,
                                ServiceRepository       $serviceRepos)
    {
        $this->orderService = $orderService;
        $this->voucherRepos = $voucherRepos;
        $this->hotelServiceService = $hotelServiceService;
        $this->catalogueRooms = $catalogueRoomRepos;
        $this->serviceRepos = $serviceRepos;
    }


    public function index(OrderSearchRequest $request)
    {
        $userId = Auth::user()->id;
        $vouchers = User::query()
            ->find($userId)
            ->vouchers()
            ->where('status', 1)
            ->where(function ($query) {
                $query->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
                      ->orWhereNull('end_date');
            })
            ->get();
        $rates = Rate::withoutTrashed()->with('hotel', 'comment')->where('user_id', $userId)->get();

        $orders = $this->orderService->searchByPage($request);

        $user = Auth::user();

        $rank = UserRankEnum::getRankByMoney($user->total_amount_ordered)->getRankName();

        return view('client.myaccount', compact('orders', 'user', 'rates', 'vouchers', 'rank'));
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

        $roomsServiceOrder = [];
        if (!empty($order)) {
            $roomsServiceOrder = $this->orderService->getDataBookingForFinish($order['org_id']);
        }

        $roomBooking = session('booking_data') ?? null;
        $servicesQty = $roomsServiceOrder['serviceBookingsQty'] ?? null;
        $servicesInfo = $roomsServiceOrder['serviceMapById'] ?? null;

        return view('client.bookingfinish', compact('order', 'status', "servicesQty", "servicesInfo", "roomBooking"));
    }

    public function confirmOrder(Request $request)
    {
        $hotelId = session('hotel_id') ?? null;
        if (!isset($hotelId)) {
            return redirect()->back()->with('error', 'Thông tin khách sạn không xác định');
        }

        $roomsServiceOrder = $this->orderService->getDataBookingForConfirm($request, $hotelId);
        $hotel = Hotel::query()->findOrFail($hotelId);
        $total_amount = $request?->total_amount;
        $user = Auth::user();
        $vouchers = $user ? $this->voucherRepos->getAllForOrder($total_amount, $hotelId, $user['id']) : [];
        $roomBooking = session('booking_data') ?? null;
        $servicesQty = $roomsServiceOrder['serviceBookingsQty'] ?? null;
        $servicesInfo = $roomsServiceOrder['serviceMapById'] ?? null;

        return view('client.booking', compact('vouchers', 'hotel', "total_amount", "servicesQty", "servicesInfo", "roomBooking"));
    }

    public function store(OrderRequest $request)
    {
        $paymentUrl = $this->orderService->create($request);
        return redirect($paymentUrl);
    }

    public function orderService(Request $request)
    {
        $roomsOrder = $this->orderService->getDataBookingOrder($request);
        $groupedRooms = $this->orderService->getRoomOrderQtyMapByCatalogueRoomId($roomsOrder);
        $hotel = Hotel::query()->findOrFail($roomsOrder[0]['hotel_id']);
        $services = $this->hotelServiceService->searchByPage($roomsOrder[0]['hotel_id'],
            new Request(['type' => ServiceTypeEnum::DICH_VU_TRA_PHI->value]));
        return view('client.bookingservice', compact('roomsOrder', 'services', 'groupedRooms','hotel'));

    }

    public function changeUserInfo(Request $request)
    {
        // dd($request->all());
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email,' . Auth::id(), 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/']
        ], $message);

        if ($validator->fails()) {
            // Xử lý lỗi validate
            return redirect()->to(route('orders.index') . '#MySettings')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Cập nhật thông tin không thành công!');
        }

        try {

            $data = $request->all();

            if ($request->has('avatar')) {
                $data['avatar'] = Storage::put('users', $data['avatar']);
            }

            $userUpdate = Auth::user();

            $oldAvatar = $userUpdate->avatar;

            $userUpdate->update($data);

            if ($request->has('avatar')) {
                if (!empty($oldAvatar) && Storage::exists($oldAvatar)) {
                    Storage::delete($oldAvatar);
                }
            }

            return redirect()->to(route('orders.index') . '#MySettings')->with('success', 'Cập nhật thông tin thành công');
        } catch (Exception $e) {
            if ($request->has('avatar')) {
                Storage::delete($data['avatar']);
            }
            return redirect()->to(route('orders.index') . '#MySettings')->with('error', $e->getMessage());
        }

    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $message = [
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.max' => 'Mật khẩu không được vượt quá :max ký tự.',
            'password.min' => 'Mật khẩu tối thiểu phải :min ký tự.'
        ];
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => ['required', 'string', 'confirmed', 'min:6', 'max:255']
        ], $message);

        if (!Hash::check($request->input('old_password'), $user->password)) {
            return redirect()->to(route('orders.index') . '#ChangePassword')
                ->withErrors(['password' => 'Mật khẩu không đúng.'])
                ->with('error', 'Đổi mật khẩu không thành công!');
        }

        if ($validator->fails()) {
            // Xử lý lỗi validate
            return redirect()->to(route('orders.index') . '#ChangePassword')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Đổi mật khẩu không thành công!');
        }

        try {
            $data = [];

            $data['password'] = bcrypt($request->input('password'));

            $userUpdate = Auth::user();

            $userUpdate->update($data);

            return redirect()->to(route('orders.index') . '#ChangePassword')->with('success', 'Thay đổi mật khẩu thành công');
        } catch (Exception $e) {
            return redirect()->to(route('orders.index') . '#ChangePassword')->with('error', $e->getMessage());
        }
    }

    public function cancelOrder($orderId)
    {
        $this->orderService->cancelOrder($orderId);
        return redirect()->back()->with('success', 'Yêu cầu hủy phòng thành công.');
    }

    public function show($orderId)
    {
        $order = $this->orderService->getOrderById($orderId);
        $catalogueRooms = $this->catalogueRooms->getByOrderId($orderId);
        $services = $this->serviceRepos->getByOrderId($orderId);
        return view('client.bookingdetail', compact('catalogueRooms', 'order', 'services'));
    }

    public function rating(Request $request, $orderId)
    {
        $validator = $request->validate([
            'rate' => 'required'
        ], ['rate.required' => 'Bạn chưa chọn điểm đánh giá']);

        $data = $request->all();

        $data['user_id'] = Auth::user()->id;

        $rate = Rate::query()->create($data);

        $order = Order::query()->where('id', $orderId)->firstOrFail();

        $order->update(['is_rating' => 1]);

        return back()->with('success', 'Đánh giá thành công!');
    }
}
