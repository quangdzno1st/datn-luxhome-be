<?php

namespace App\Http\Controllers\Api;

use App\Mail\BookingInvoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function confirmBooking(Order $order)
    {
        // Xử lý logic đặt phòng (save vào database, kiểm tra thông tin, v.v.)

        // Sau khi đặt phòng thành công


        Mail::to($order['email'])->send(new BookingInvoice($order));

        return response()->json([
            'result' => true,
            'message' => 'Booking confirmed and invoice sent.'
        ], Response::HTTP_OK);
    }
}