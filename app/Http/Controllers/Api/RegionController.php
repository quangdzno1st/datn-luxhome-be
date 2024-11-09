<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Region\CreateReigonRequest;
use App\Http\Requests\Admin\Region\UpdateRegionRequest;
use App\Http\Resources\RegionCollection;
use App\Http\Resources\RegionResource;
use App\Repositories\Reigion\RegionRepository;
use App\Services\impl\RegionServiceImpl;
use Illuminate\Http\Response;

class RegionController extends Controller
{
    protected $regionRepository, $regionService;

    public function __construct(RegionRepository $regionRepository, RegionServiceImpl $regionService)
    {
        $this->regionRepository = $regionRepository;
        $this->regionService = $regionService;
    }

    public function index(){
        $regions = $this->regionRepository->getAllRegion();

        if ($regions->isEmpty()) {
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
                'data' => new RegionCollection($regions),
                'message' => 'Get regions successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function show($id){
        $region = $this->regionRepository->regionDetail($id);

        if($region === null){
            return response()->json(
                [
                    'data' => null,
                    'message' => 'Region not found',
                    'status' => Response::HTTP_NOT_FOUND
                ]
            );
        }

        return response()->json(
            [
                'data' => new RegionResource($region),
                'message' => 'Get region successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function store(CreateReigonRequest $request){
        try{
            $data = $request->all();

            $region = $this->regionService->createNewReigon($data);

            return response()->json(
                [
                    'data' => new RegionResource($region),
                    'message' => 'Created region successfully',
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

    public function update(UpdateRegionRequest $request, $id){
        try{
            $data = $request->all();

            $region =$this->regionService->updateReigon($data, $id);

            return response()->json(
                [
                    'data' => new RegionResource($region),
                    'message' => 'Updated region successfully',
                    'status' => Response::HTTP_OK
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

    public function destroy($id){
        try{
            $region = $this->regionService->deleteReigon($id);

            return response()->json(
                [
                    'data' => null,
                    'message' => 'Deleted region successfully',
                    'status' => Response::HTTP_NO_CONTENT
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

    public function trash(){
        $regions = $this->regionRepository->trash();

        if ($regions->isEmpty()) {
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
                'data' => new RegionCollection($regions),
                'message' => 'Get regions deleted successfully',
                'status' => Response::HTTP_OK
            ]
        );
    }

    public function restore($id){
        try{
            $region = $this->regionService->restoreReigon($id);

            return response()->json(
                [
                    'data' => new RegionResource($region),
                    'message' => 'Restored region successfully',
                    'status' => Response::HTTP_OK
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

    public function forceDelete($id){
        try{
            $region = $this->regionService->forceDeleteReigon($id);

            return response()->json(
                [
                    'data' => null,
                    'message' => 'Permanently deleted region successfully',
                    'status' => Response::HTTP_NO_CONTENT
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
}
