<?php

namespace App\Http\Controllers\Api;

use App\Mail\BookingInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        // Xử lý logic đặt phòng (save vào database, kiểm tra thông tin, v.v.)

        // Sau khi đặt phòng thành công
        $bookingDetails = []; // Thông tin hóa đơn/đặt phòng

        $email = $request->input('email');

        Mail::to($email)->send(new BookingInvoice($bookingDetails));

        return response()->json([
            'result' => true,
            'message' => 'Booking confirmed and invoice sent.'
        ], Response::HTTP_OK);
    }
}