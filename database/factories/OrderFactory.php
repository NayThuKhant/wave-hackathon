<?php

namespace Database\Factories;

use App\Enums\SystemStatus;
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
            'status' => Enum::make(SystemStatus::class)->collection()->random(),
            'started_at' => $this->faker->word(),
            'rating' => $this->faker->randomNumber(),
            'feedback' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
