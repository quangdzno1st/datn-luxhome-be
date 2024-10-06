<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccess($data, $code = 200, $message = 'Thành công')
    {
        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function sendSuccessData($data, $code = 200, $message = 'Thành công')
    {
        return response()->json([
            'result' => true,
            'message' => $message,
            'current_page' => $data->currentPage(),
            'data' => $data->items(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'last_page' => $data->lastPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
            'first_page_url' => $data->url(1),
            'last_page_url' => $data->url($data->lastPage()),
            'next_page_url' => $data->nextPageUrl(),
            'prev_page_url' => $data->previousPageUrl(),
            'path' => $data->path(),
        ], $code);

    }

    public function createSuccess($data, $message = 'Thêm thành công')
    {
        return $this->sendSuccess($data, 201, $message);
    }

    public function updateSuccess($data, $message = 'Cập nhật thành công')
    {
        return $this->sendSuccess($data, 201, $message);
    }

    public function deleteSuccess($message = "Xóa thành công")
    {
        return response()->json([
            'result' => true,
            'message' => $message
        ], 200);
    }


    public function sendError($message = 'Lỗi bắt buộc', $code = 200)
    {
        return response()->json([
            'result' => false,
            'message' => $message,
        ], $code);
    }

    public function sendForbidden($message = 'Không có quyền truy cập', $code = 403)
    {
        return response()->json([
            'result' => false,
            'message' => $message,
        ], $code);
    }

}
