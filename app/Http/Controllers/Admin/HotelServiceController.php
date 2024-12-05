<?php

namespace App\Http\Controllers\Admin;

use App\Constant\Enum\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelService;
use App\Models\Service;
use App\Services\impl\HotelServiceServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelServiceController extends Controller
{

    const PATH_VIEW = 'admin.hotelservices.';

    protected $hotelService;

    public function __construct(HotelServiceServiceImpl $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function index(string $idHotel = null)
    {

        if (!empty($idHotel) && Auth::user()->type == RoleEnum::Admin->value && Auth::user()->org_id != $idHotel) {
            return  redirect()->route('admin.error.404');
        }

        if (empty($idHotel) && isset(Auth::user()->org_id) && Auth::user()->type == RoleEnum::Admin->value) {
            $idHotel = Auth::user()->org_id;
        }

        // //Check xem có phải admin không, nếu phải mà url là admin/hotel/services thì return 404
        // if (empty($idHotel) && Auth::user()->type != RoleEnum::Admin->value) {
        //     return  redirect()->route('error.404');
        // }

        // //Check
        // if (empty($idHotel) && Auth::user()->type == RoleEnum::Admin->value) {
        //     $idHotel = Auth::user()->org_id;
        // }

        $hotel = Hotel::query()->where('id', $idHotel)->first();

        if (!isset($hotel)) {
            return  redirect()->route('admin.error.404');
        }

        $services = Service::all();

        $hotelServices = $this->hotelService->getServicesByIdHotel($idHotel, request());

        $hotelServiceConstants = HotelService::query()->where('hotel_id', $idHotel)->get();

        return view(self::PATH_VIEW . __FUNCTION__, compact('hotelServices', 'hotelServiceConstants', 'hotel', 'idHotel', 'services'));
    }

    public function store(Request $request ,string $idHotel = null)
    {
        $validator =$request->validate([
            'services' => 'required'
        ], ['services.required' => 'Chọn ít nhất 1 dịch vụ']);

        if (empty($idHotel) && Auth::user()->type == RoleEnum::Admin->value) {
            $idHotel = Auth::user()->org_id;
        }
        
        $this->hotelService->create($idHotel, $request);
        
        return back()->with('success', 'Thêm dịch vụ thành công!');
    }

    public function destroy(string $id)
    {
        $this->hotelService->delete($id);
        return back()->with('success', 'Xóa dịch vụ thành công!');
    }

    public function destroyMulti(Request $request)
    {
        $this->hotelService->deleteMulti($request);
        return back()->with('success', 'Xóa dịch vụ thành công!');
    }
}