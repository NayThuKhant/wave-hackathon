<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscribeCategoriesRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use function Livewire\on;

class ProfileController extends Controller
{
    public function me()
    {
        $user = User::with('employee', 'employer', 'categories', 'addresses')->find(Auth::id());

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

        $user->on_hold_balance = (config("app.platform_fee_percentage") / 100 * $onHoldBalance);

        return response()->json([
            'message' => 'Profile information has been successfully retrieved.',
            'data' => $user,
        ]);
    }

    public function startWorking()
    {
        $employee = Auth::user()->employee()->firstOrCreate();

        return response()->json([
            'message' => 'Employee profile has been created',
            'data' => $employee,
        ]);
    }

    public function subscribeCategories(SubscribeCategoriesRequest $request)
    {
        $data = Auth::user()->categories()->sync($request->get('category_ids'));

        return response()->json([
            'message' => 'Categories have been subscribed',
            'data' => Auth::user()->categories,
        ]);
    }
}
