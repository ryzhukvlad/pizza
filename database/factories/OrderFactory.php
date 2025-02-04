<?php

namespace Database\Factories;

use App\Enum\OrderStatus;
use App\Models\User;
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
    public function definition(): array
    {
        $statuses = array_column(OrderStatus::cases(), 'name');
        return [
            'user_id' => User::all()->random()->id,
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'address' => $this->faker->address(),
            'time' => $this->faker->dateTimeBetween('now', '+1 month'),
            'total' => $this->faker->randomFloat(500, 10000),
            'status' => $this->faker->randomElement($statuses),
        ];
    }
}
