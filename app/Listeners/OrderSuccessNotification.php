<?php

namespace App\Listeners;

use App\Mail\BookingInvoice;
use Illuminate\Support\Facades\Mail;
use App\Events\OrderSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderSuccessNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderSuccess  $event
     * @return void
     */
    public function handle(OrderSuccess $event)
    {
        $data = $event->bookingDetails;

        Mail::send('emails.booking.invoice', $data, function ($message) {
            $message->to('kiennmph41026@fpt.edu.vn') //chỗ $message->to thay bằng mail khách hàng nhé, nhận được trong $data
                    ->subject('Hóa Đơn Đặt Phòng Khách Sạn');
        });
    }
}
