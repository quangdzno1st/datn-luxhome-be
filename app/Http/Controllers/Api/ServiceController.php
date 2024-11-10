<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Api\Service\CreateRequest;
use App\Http\Requests\Api\Service\UpdateRequest;
use App\Services\impl\ServiceServiceImpl;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

class ServiceController extends Controller
{
    public $service;
    public function __construct(ServiceServiceImpl $service){
        $this->service = $service;
    }

    public function index()
    {
        $services = $this->service->getAll(request());

        if($services){
            return response()->json([
                'result' => true,
                'message' => 'Lấy dữ liệu thành công',
                'data' => $services,
            ], Response::HTTP_OK);
        }
        return response()->json([
            'result' => false,
            'message' => 'Lấy dữ liệu không thành công',
            'data' => [],
        ], Response::HTTP_NOT_FOUND);
    }

    public function show($id)
    {
        $service = $this->service->getById($id);

        if($service){
            return response()->json([
                'result' => true,
                'message' => 'Lấy dữ liệu thành công',
                'data' => $service,
            ], Response::HTTP_OK);
        }
        return response()->json([
            'result' => false,
            'message' => 'Lấy dữ liệu không thành công',
            'data' => [],
        ], Response::HTTP_NOT_FOUND);
    }

    public function store(CreateRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $service = $this->service->createNew($data);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => "Thêm mới thành công",
                'data' => $service,
            ], Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            return response()->json(
                [
                    'result' => false,
                    'message' => 'Thêm mới thất bại',
                    'error_message' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(UpdateRequest $request, $id)
    {

        $data = $request->all();

        try {
            DB::beginTransaction();

            $service = $this->service->update($data, $id);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => "Cập nhật thành công",
                'data' => $service,
            ], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'result' => false,
                    'message' => 'Cập nhật thất bại',
                    'error_message' => $exception->getMessage(),
                ],
                Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $service = $this->service->delete($id);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'Xóa mềm thành công',
                'data' => $service,
            ], Response::HTTP_OK);

        } catch (\Exception $exception)
        {
            return response()->json([
                'result' => false,
                'message' => 'Xóa mềm thất bại',
                'error_message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $service = $this->service->restore($id);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'Khôi phục thành công',
                'data' => $service,
            ],Response::HTTP_OK);

        } catch (\Exception $exception)
        {
            return response()->json([
                'result' => false,
                'message' => 'Khôi phục thất bại',
                'error_message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {

        try {
            DB::beginTransaction();

            $service = $this->service->forceDelete($id);

            DB::commit();

            return response()->json([
                'result' => true,
                'message' => 'Xóa thành công',
                'data' => $service,
            ],Response::HTTP_OK);

        } catch (\Exception $exception)
        {
            return response()->json([
                'result' => false,
                'message' => 'Xóa thất bại',
                'error_message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
