<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Hotel\CreateHotelRequest;
use App\Http\Requests\Admin\Hotel\UpdateHotelRequest;
use App\Http\Resources\HotelCollection;
use App\Http\Resources\HotelResource;
use App\Repositories\Hotel\HotelRepository;
use App\Services\impl\HotelServiceImpl;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
                    'message' => 'Not data',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new HotelCollection($hotels),
                'message' => 'Get hotels successfully',
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
                    'message' => 'Create hotel successfully',
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

    public function show($slug)
    {
        $hotel = $this->hotelRepository->detailHotel($slug);

        if ($hotel === null) {
            return response()->json(
                [
                    'data' => null,
                    'message' => 'Not found',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new HotelResource($hotel),
                'message' => 'Get hotel successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function update(UpdateHotelRequest $request, $slug)
    {

        try {
            $data = $request->validated();

            $hotel = $this->hotelService->updateHotel($data, $slug);

            return response()->json(
                [
                    'data' => new HotelResource($hotel),
                    'message' => 'Update hotel successfully',
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

    public function destroy($slug)
    {
        try {
            $hotel = $this->hotelService->deleteHotel($slug);

            return response()->json(
                [
                    'data' => null,
                    'message' => 'Delete hotel successfully',
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
    public function trash(){
        $hotels = $this->hotelRepository->trash();

        if ($hotels->isEmpty()) {
            return response()->json(
                [
                    'data' => null,
                    'message' => 'Not data',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new HotelCollection($hotels),
                'message' => 'Get hotels deleted successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function restore($slug){
        $hotel = $this->hotelService->restoreHotel($slug);

        return response()->json(
            [
                'data' => new HotelResource($hotel),
                'message' => 'Restore hotels successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function forceDelete($slug)
    {
        try {
            $hotel = $this->hotelService->forceDeleteHotel($slug);

            return response()->json(
                [
                    'data' => null,
                    'message' => 'cAI NAY LA XOA VINH VIEN HEHE',
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
