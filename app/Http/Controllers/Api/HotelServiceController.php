<?php

namespace App\Http\Controllers\Api;

use App\Services\impl\HotelServiceServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class HotelServiceController extends Controller
{
    protected HotelServiceServiceImpl $hotelServices;

    public function __construct(HotelServiceServiceImpl $hotelServices)
    {
        $this->hotelServices = $hotelServices;
    }

    public function index($idHotel)
    {
        $data = $this->hotelServices->getServicesByIdHotel($idHotel, request());
        if ($data) {
            return $this->sendSuccess($data);
        }
        return response()->json([
            'result' => false,
            'message' => 'Data not found',
            'data' => []
        ], Response::HTTP_NOT_FOUND);
    }

    public function store($idHotel, Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->hotelServices->create($idHotel, $request);
            DB::commit();
            return $this->createSuccess($data);
        } catch (\Exception $exception) {
            return response()->json([
                'result' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->hotelServices->delete($id);
            DB::commit();
            return $this->deleteSuccess();
        } catch (\Exception $exception) {
            return response()->json([
                'result' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function destroyMulti(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->hotelServices->deleteMulti($request);
            DB::commit();
            return $this->deleteSuccess();
        } catch (\Exception $exception) {
            return response()->json([
                'result' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
