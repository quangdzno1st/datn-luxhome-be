<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Service\CreateRequest;
use App\Http\Requests\Api\Service\UpdateRequest;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceController extends Controller
{

    public function index()
    {
        $services = Service::all();

        return response()->json([
           'result' => true,
           'data' => $services,
           'message' => 'Thành công'
        ], 200);
    }

    public function store(CreateRequest $request)
    {
        $data = $request->toArray();

        $data['id'] = Str::uuid()->toString();

        try {
            DB::beginTransaction();

            $service = Service::query()->create($data);

            DB::commit();

            return response()->json([
                'result' => true,
                'data' => $service,
                'message' => "Thêm mới thành công"
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'result' => false,
                    'error_message' => $exception->getMessage(),
                    'message' => 'Thêm mới thất bại'
                ],
            400);
        }
    }

    public function show($id)
    {
        $service = Service::query()->where('id', $id)->first();

        if (!$service) {
            return response()->json([
                'result' => false,
                'data' => [],
                'message' => 'Tìm kiếm thất bại'
            ], 404);
        }

        return response()->json([
            'result' => true,
            'data' => $service,
            'message' => 'Tìm kiếm thành công'
        ], 200);
    }

    public function update(UpdateRequest $request, $id)
    {

        $data = $request->toArray();

        $service = Service::query()->where('id', $id)->first();

        try {
            DB::beginTransaction();

            $service->update($data);

            DB::commit();

            return response()->json([
                'result' => true,
                'data' => $service,
                'message' => "Cập nhật thành công"
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'result' => false,
                    'error_message' => $exception->getMessage(),
                    'message' => 'Cập nhật thất bại'
                ],
                400);
        }
    }

    public function destroy($id)
    {
        $service = Service::withTrashed()->where('id', $id)->first();

        $service->forceDelete();

        return response()->json([
            'result' => true,
            'message' => 'Xóa thành công'
        ]);

    }
}
