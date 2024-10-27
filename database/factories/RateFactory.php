<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rate>
 */
class RateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'user_id' => $this->faker->uuid(),
            'hotel_id' => '4b146c5b-4e5c-39cd-89e1-a78d739f8570',
            'rate'=>$this->faker->numberBetween(0,5),
            'content' => $this->faker->text(),
        ];
    }
}
