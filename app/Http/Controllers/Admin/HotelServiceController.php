<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Service;
use App\Services\impl\HotelServiceServiceImpl;
use Illuminate\Http\Request;

class HotelServiceController extends Controller
{

    const PATH_VIEW = 'admin.hotelservices.';
    protected $hotelService;

    public function __construct(HotelServiceServiceImpl $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function index(string $idHotel)
    {
        $hotel = Hotel::findOrFail($idHotel);

        $services = Service::all();

        $hotelServices = $this->hotelService->getServicesByIdHotel($idHotel);

        return view(self::PATH_VIEW . __FUNCTION__, compact('hotelServices', 'hotel', 'services'));
    }

    public function store(string $idHotel, Request $request)
    {
        $this->hotelService->create($idHotel, $request);

        return back();
    }

    public function destroy(string $id)
    {
        $this->hotelService->delete($id);
        return back();
    }

    public function destroyMulti(Request $request)
    {
        $this->hotelService->deleteMulti($request);
        return back();
    }
}