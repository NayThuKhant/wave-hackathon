<?php

namespace App\Http\Controllers;

use App\Enums\SystemStatus;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::join('employees', 'users.id', 'employees.user_id')
            ->where('employees.system_status', SystemStatus::ACTIVE)
            ->select(['users.id as id', 'users.name', 'users.email', 'users.mobile_number', 'users.gender'])
            ->with('categories', 'orders')
            ->where("employees.system_status", SystemStatus::ACTIVE->value)
            ->distinct()
            ->get()
            ->map(function ($employee) {
                $orderCount = $employee->orders->count();
                if (!$orderCount) {
                    $employee->rating = 0.0;
                    return $employee;
                }

                $employee->rating = number_format($employee->orders->sum('rating') / $orderCount, 2);
                return $employee;
            })->sortByDesc('rating')
        ->values();

        return response()->json([
            'message' => 'Employees have been retrieved.',
            'data' => $employees
        ]);
    }
}
