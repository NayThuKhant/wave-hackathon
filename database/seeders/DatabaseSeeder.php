<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\PricingModel;
use App\Models\Address;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('passport:install');

        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => Str::upper(config('app.name')),
        ]);

        $this->call(AdminSeeder::class);

        User::factory(100)
            ->has(Employee::factory()->count(1))
            ->has(Employer::factory()->count(1))
            ->has(Address::factory()->count(2))
            ->create();

        Order::factory()->count(100)->state(function () {
            return [
                'employer_id' => rand(1, 100),
                'employee_id' => rand(1, 100),
            ];
        })->create();

        Order::factory()->count(10)->state(function () {
            return [
                'employer_id' => 1,
                'employee_id' => 2,
            ];
        })->create();

        Order::factory()->count(10)->state(function () {
            return [
                'employer_id' => 2,
                'employee_id' => 1,
            ];
        })->create();

        $category = Category::create([
            'name' => 'Household Services',
            'description' => 'Household services are services provided by professional domestic workers.',
        ]);

        $category->services()->createMany([
            [
                'name' => 'House Cleaning',
                'pricing_model' => PricingModel::PER_HOUR->value,
            ],
            [
                'name' => 'Garden Maintenance',
                'pricing_model' => PricingModel::PER_HOUR->value,
            ],
        ]);

        $category = Category::create([
            'name' => 'Laundry Services',
            'description' => 'Laundry services are services provided by professional domestic workers.',
        ]);

        $category->services()->createMany([
            [
                'name' => 'White Laundry',
                'pricing_model' => PricingModel::PER_ITEM->value,
            ],
            [
                'name' => 'Colored Laundry',
                'pricing_model' => PricingModel::PER_ITEM->value,
            ],
        ]);
    }
}
