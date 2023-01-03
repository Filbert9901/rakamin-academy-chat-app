<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SenderReceiverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'receiver_id' => User::factory()->create()->id,
            'sender_id' => User::factory()->create()->id,
            'message' => $this->faker->sentence(),
        ];
    }
}
