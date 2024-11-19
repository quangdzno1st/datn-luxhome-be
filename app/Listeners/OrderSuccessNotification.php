<?php

namespace App\Listeners;

use App\Events\OrderSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

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
     * @param \App\Events\OrderSuccess $event
     * @return void
     */
    public function handle(OrderSuccess $event)
    {
        $data = $event->bookingDetails->toArray();

        Mail::send('emails.booking.invoice', $data, function ($message) use ($data) {
            $message->from('quangdzno1st@gmail.com');
            $message->to($data['email']) //chỗ $message->to thay bằng mail khách hàng nhé, nhận được trong $data
            ->subject('Hóa Đơn Đặt Phòng Khách Sạn');
        });
    }
}
