<?php

namespace Database\Factories;

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
    public function definition()
    {
        return [
            'user_id' => fake()->uuid,
            'booking_fee' => fake()->numberBetween(100, 1000),
            'phone' => fake()->phoneNumber,
            'email' => fake()->email,
            'name' => fake()->name,
            'code' => fake()->postcode,
            'qr_code' => fake()->postcode,
            'status' => 2,
            'start_date' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'end_date' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'check_in' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'check_out' => fake()->dateTime()->format('Y-m-d H:i:s'),
            'incidental_costs' => 1,
            'org_id'=> 'LHO-' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
            'total_amount' => fake()->numberBetween(1000000, 10000000)
        ];
    }
}
