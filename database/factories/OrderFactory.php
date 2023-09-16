<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Helpers\Enum;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {

        return [
            'address' => $this->faker->address(),
            'employer_id' => $this->faker->randomNumber(),
            'employee_id' => $this->faker->randomNumber(),
            'status' => Enum::make(OrderStatus::class)->collection()->random(),
            'started_at' => Carbon::now()->addDay(),
            'rating' => array_rand([1, 2, 3, 4, 5]) + 1,
            'feedback' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
