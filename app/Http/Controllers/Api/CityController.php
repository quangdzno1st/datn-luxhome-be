<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\City\CreateCityRequest;
use App\Http\Requests\Admin\City\UpdateCityRequest;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Repositories\City\CityRepository;
use App\Services\impl\CityServiceImpl;
use Illuminate\Http\Response;

class CityController extends Controller
{
    protected $cityRepository, $cityService;

    public function __construct(CityRepository $cityRepository, CityServiceImpl $cityService)
    {
        $this->cityRepository = $cityRepository;
        $this->cityService = $cityService;
    }

    public function index(){
        $cities = $this->cityRepository->getAllCity();

        if($cities->isEmpty()){
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
                'data' => new CityCollection($cities),
                'message' => 'Get cities successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function store(CreateCityRequest $request){
        try{
            $data = $request->all();

            $city = $this->cityService->createNewCity($data);

            return response()->json(
                [
                    'data' => new CityResource($city),
                    'message' => 'Created city successfully',
                    'status' => Response::HTTP_CREATED
                ]
            );
        }catch(\Exception $e){
            return response()->json(
                [
                    'errors' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }

    public function show($id){
        $citie = $this->cityRepository->detailCity($id);

        if($citie === null){
            return response()->json(
                [
                    'data' => null,
                    'message' => 'City not found',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new CityResource($citie),
                'message' => 'Get cities successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function update(UpdateCityRequest $request, $id){
        try{
            $data = $request->all();
            $city = $this->cityService->updateCity($data, $id);

            return response()->json(
                [
                    'data' => new CityResource($city),
                    'message' => 'Updated city successfully',
                    'status' => Response::HTTP_OK
                ]
            );
        }catch (\Exception $e){
            return response()->json(
                [
                    'errors' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }

    public function destroy($id){
       try{
           $city = $this->cityService->deleteCity($id);

           return response()->json(
               [
                   'message' => 'Deleted city successfully',
                   'status' => Response::HTTP_NO_CONTENT
               ]
           );
       }catch (\Exception $e){
           return response()->json(
               [
                   'errors' => $e->getMessage(),
                   'status' => Response::HTTP_BAD_REQUEST
               ]
           );
       }
    }

    public function trash(){
        $cities = $this->cityRepository->trash();

        if($cities->isEmpty()){
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
                'data' => new CityCollection($cities),
                'message' => 'Get cities successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function restore($id){
        try{
            $city = $this->cityService->restoreCity($id);

            return response()->json(
                [
                    'data' => new CityResource($city),
                    'message' => 'Restored city successfully',
                    'status' => Response::HTTP_OK
                ]
            );
        }catch (\Exception $e){
            return response()->json(
                [
                    'errors' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }

    public function forceDelete($id){
        try{
            $this->cityService->forceDeleteCity($id);

            return response()->json(
                [
                    'message' => 'Permanently deleted city successfully',
                    'status' => Response::HTTP_OK
                ]
            );
        }catch (\Exception $e){
            return response()->json(
                [
                    'errors' => $e->getMessage(),
                    'status' => Response::HTTP_BAD_REQUEST
                ]
            );
        }
    }
}
