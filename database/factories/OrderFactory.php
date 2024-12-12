<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     function generateRandomTimestamp() {
        $year = 2024; // Năm cố định
        $month = rand(1, 12); // Random từ tháng 1 đến tháng 12
        $day = rand(1, Carbon::create($year, $month)->daysInMonth); // Random ngày trong tháng
        $hour = rand(0, 23); // Random giờ
        $minute = rand(0, 59); // Random phút
        $second = rand(0, 59); // Random giây
    
        // Tạo đối tượng Carbon
        $randomDate = Carbon::create($year, $month, $day, $hour, $minute, $second);
    
        // Trả về định dạng Y-m-d H:i:s
        return $randomDate->format('Y-m-d H:i:s');
    }
    
    public function definition()
    {
        $hotelds = ['1ea03be9-58d8-422b-b20c-4f6493d9d066','7ac7513c-9f14-473c-947c-5d6422522faf','b99fad74-7cd9-4d77-b016-367f177cbd58'];
        $userIds = ['76fddf47-f976-4840-9ce1-5f38aee722ba', '1d19d9f0-7e83-4b1f-9e92-216553434a60', 'b9ad78ea-ee44-49b0-a131-e3a94c372a17'];
        return [
            'id' => fake()->uuid(),
            'user_id' => $userIds[rand(0,2)],
            'booking_fee' => fake()->numberBetween(100, 1000),
            'phone' => fake()->phoneNumber,
            'email' => fake()->email,
            'name' => fake()->name,
            'code' => fake()->postcode,
            'status' => 3,
            'status_payment' => 2,
            'start_date' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'end_date' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'check_in' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'check_out' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'incidental_costs' => 1,
            'org_id'=> $hotelds[rand(0,2)],
            'created_at' => $this->generateRandomTimestamp(),
            'total_amount' => fake()->numberBetween(1000000, 10000000),
            'net_amount' => fake()->numberBetween(1000000, 10000000)
        ];
    }
}
