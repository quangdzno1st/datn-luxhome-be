<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Hotel\CreateHotelRequest;
use App\Http\Requests\Api\Hotel\UpdateHotelRequest;
use App\Http\Resources\HotelCollection;
use App\Http\Resources\HotelResource;
use App\Repositories\Hotel\HotelRepository;
use App\Services\impl\HotelServiceImpl;
use Illuminate\Http\Response;

class HotelController extends Controller
{
    protected $hotelRepository;
    protected $hotelService;

    public function __construct(HotelRepository $hotelRepository, HotelServiceImpl $hotelService)
    {
        $this->hotelRepository = $hotelRepository;
        $this->hotelService = $hotelService;
    }

    public function index()
    {
        $hotels = $this->hotelRepository->getAll();

        if ($hotels->isEmpty()) {
            return response()->json(
                [
                    'data' => null,
                    'message' => 'Không có dữ liệu',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new HotelCollection($hotels),
                'message' => 'Lấy thông tin tất cả khách sạn thành công',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function store(CreateHotelRequest $request)
    {
        try {
            $data = $request->validated();

            $hotel = $this->hotelService->createNewHotel($data);

            return response()->json(
                [
                    'data' => new HotelResource($hotel),
                    'message' => 'Thêm mới khách sạn thành công',
                    'status' => Response::HTTP_CREATED
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'data' => null,
                    'message' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }

    public function show($id)
    {
        $hotel = $this->hotelRepository->detailHotel($id);

        if ($hotel === null) {
            return response()->json(
                [
                    'data' => null,
                    'message' => 'Khách sạn không tồn tại hoặc đã bị xóa',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new HotelResource($hotel),
                'message' => 'Lấy thông tin khách sạn thành công',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function update(UpdateHotelRequest $request, $id)
    {

        try {
            $data = $request->validated();

            $hotel = $this->hotelService->updateHotel($data, $id);

            return response()->json(
                [
                    'data' => new HotelResource($hotel),
                    'message' => 'Cập nhật khách sạn thành công',
                    'status' => Response::HTTP_OK
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'data' => null,
                    'message' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }

    public function destroy($id)
    {
        try {
            $hotel = $this->hotelService->deleteHotel($id);

            return response()->json(
                [
                    'data' => null,
                    'message' => 'Xóa khách sạn thành công',
                    'status' => Response::HTTP_OK
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'data' => null,
                    'message' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }

    // thung rac'
    public function trash()
    {
        $hotels = $this->hotelRepository->trash();

        if ($hotels->isEmpty()) {
            return response()->json(
                [
                    'data' => null,
                    'message' => 'Không có dữ liệu',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new HotelCollection($hotels),
                'message' => 'Lấy tất cả khách sạn đã xóa thành công',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function restore($id)
    {
        $hotel = $this->hotelService->restoreHotel($id);

        return response()->json(
            [
                'data' => new HotelResource($hotel),
                'message' => 'Khôi phục khách sạn thành công',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function forceDelete($id)
    {
        try {
            $hotel = $this->hotelService->forceDeleteHotel($id);

            return response()->json(
                [
                    'data' => null,
                    'message' => 'Xóa khách sạn vĩnh viễn thành công',
                    'status' => Response::HTTP_OK
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'data' => null,
                    'message' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }
}
