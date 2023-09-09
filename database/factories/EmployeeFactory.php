<?php

namespace Database\Factories;

use App\Enums\SystemStatus;
use App\Helpers\Enum;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'system_status' => Enum::make(SystemStatus::class)->collection()->random(),
        ];
    }
}
