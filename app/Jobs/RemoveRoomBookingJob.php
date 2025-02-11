<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class RemoveRoomBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $key;
    protected $startTime;
    protected $endTime;

    public function __construct($key, $startTime, $endTime)
    {
        $this->key = $key;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function handle()
    {

        $existingBookings = Redis::lrange($this->key, 0, -1);

        if (empty($existingBookings)) {
            return;
        }

        $updatedBookings = array_filter($existingBookings, function ($booking) {
            $bookingData = json_decode($booking, true);
            return $bookingData['start_time'] !== $this->startTime || $bookingData['end_time'] !== $this->endTime;
        });

        if (empty($updatedBookings)) {
            Redis::del($this->key);
        } else {
            Redis::del($this->key);
            foreach ($updatedBookings as $booking) {
                Redis::rpush($this->key, $booking);
            }
        }
    }
}
