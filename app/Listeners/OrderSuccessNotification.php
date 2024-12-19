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

        $order = $event->bookingDetails;
        $services = $event->services;
        $catalogueRooms = $event->catalogueRooms;

        Mail::send('emails.booking.invoice', [
            'order' => $order,
            'services' => $services,
            'catalogueRooms' => $catalogueRooms
        ], function ($message) use ($order) {
            $message->from('quangdzno1st@gmail.com');
            $message->to($order['email']) // Email khách hàng
            ->subject('Hóa Đơn Đặt Phòng Khách Sạn');
        });
    }
}
