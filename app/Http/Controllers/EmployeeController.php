<?php

namespace App\Http\Controllers;

use App\Enums\SystemStatus;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Employees have been retrieved.',
            'data' => User::join('employees', 'users.id', 'employees.user_id')
                ->where('employees.system_status', SystemStatus::ACTIVE)
                ->select(['users.id as id', 'users.name', 'users.email', 'users.mobile_number', 'users.gender'])
                ->with('categories')
                ->get(),
        ]);
    }
}
