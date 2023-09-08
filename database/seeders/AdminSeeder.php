<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                "name" => "Admin",
                "email" => "admin@domesticservice.com",
                "password" => "password"
            ],
            [
                "name" => "Admin1",
                "email" => "admin1@domesticservice.com",
                "password" => "password"
            ],
            [
                "name" => "Admin2",
                "email" => "admin2@domesticservice.com",
                "password" => "password"
            ],
            [
                "name" => "Admin3",
                "email" => "admin3@domesticservice.com",
                "password" => "password"
            ],
            [
                "name" => "Admin4",
                "email" => "admin4@domesticservice.com",
                "password" => "password"
            ]
        ])->map(function ($admin) {
            $name = $admin["name"];
            $email = $admin["email"];
            $password = $admin["password"];
            Artisan::call("make:filament-user --name=$name --email=$email --password=$password");
        });
    }
}
