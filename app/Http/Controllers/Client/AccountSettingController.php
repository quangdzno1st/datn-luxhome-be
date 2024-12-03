<?php

namespace App\Http\Controllers\Client;

use App\Models\Rate;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\HotelServiceService;
use App\Constant\Enum\ServiceTypeEnum;
use App\Http\Requests\OrderSearchRequest;
use App\Models\Order;
use Illuminate\Validation\ValidationException;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\Voucher\VoucherRepository;
use App\Repositories\CatalogueRoom\CatalogueRoomRepository;
use Exception;
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

        $rates = Rate::withoutTrashed()->with('hotel', 'comment')->where('user_id', $userId)->get();

        $orders = $this->orderService->searchByPage($request);

        $user = Auth::user();

        return view('client.myaccount', compact('orders', 'user', 'rates'));
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
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email,' . Auth::id(), 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/']
        ], $message);

        if ($validator->fails()) {
            // Xử lý lỗi validate
            return redirect()->back()
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

            return back()->with('success', 'Cập nhật thông tin thành công');
        } catch (Exception $e) {
            if ($request->has('avatar')) {
                Storage::delete($data['avatar']);
            }
            return back()->with('error', $e->getMessage());
        }

    }

    public function changePassword(Request $request)
    {
        $message = [
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.max' => 'Mật khẩu không được vượt quá :max ký tự.'
        ];
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'confirmed', 'max:255']
        ], $message);

        if ($validator->fails()) {
            // Xử lý lỗi validate
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Đổi mật khẩu không thành công!');
        }

        try {
            $data = [];

            $data['password'] = bcrypt($request->input('password'));

            $userUpdate = Auth::user();
            
            $userUpdate->update($data);

            return back()->with('success', 'Thay đổi mật khẩu thành công');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
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
