<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::firstOrCreate(
            ['mobile_number' => $request->msisdn],
            [
                'name' => $request->name,
                'dob' => $request->dob,
                'nrc' => $request->nrc,
                'gender' => $request->gender,
            ]
        );

        $user->employer()->firstOrCreate();

        // TODO : Check User's KYC Status
        $token = $user->createToken('auth_token')->accessToken;

        $user->load('employee', 'employer', 'categories');

        $onHoldBalance = 0;
        $user->orders()
            ->with('services')
            ->where('orders.status', 'COMPLETED')
            ->get()
            ->each(function ($order) use (&$onHoldBalance) {
                $services = $order['services'];
                $services->each(function ($service) use (&$onHoldBalance) {
                    $onHoldBalance += $service->toArray()['pivot']['quantity'] * $service['price'];
                });
            });

        $user->on_hold_balance = $onHoldBalance - (config("app.platform_fee_percentage") / 100 * $onHoldBalance);

        return response()->json(['token' => $token, 'user' => $user], 200);
    }
}
