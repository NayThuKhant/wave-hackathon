<?php

namespace Database\Factories;

use App\Enums\SystemStatus;
use App\Helpers\Enum;
use App\Models\Employer;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployerFactory extends Factory
{
    protected $model = Employer::class;

    public function definition(): array
    {
        return [
            'system_status' => Enum::make(SystemStatus::class)->collection()->random(),
        ];
    }
}
