<?php

namespace App\Exceptions;

use Exception;

class RespException extends Exception
{
    protected int $statusCode;
    protected $message;

    /**
     * @param int $statusCode
     * @param $message
     */
    public function __construct($message, int $statusCode = 400)
    {
        $this->statusCode = $statusCode;
        $this->message = $message;

        parent::__construct($message, $statusCode);
    }

    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $this->statusCode,
            'title' => 'Lỗi hệ thống',
            'message' => $this->message,
        ], $this->statusCode);
    }
}
