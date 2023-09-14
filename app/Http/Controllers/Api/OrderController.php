<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {

    }

    public function index()
    {
        return response()->json([
            'message' => 'Orders have been retrieved.',
            'data' => [
                'orders' => Order::where('employee_id', Auth::id())->with('category','employee')->get(),
                'offers' => Order::where('employer_id', Auth::id())->with('category','employer')->get()
            ]
            // 'data' => User::where('id', Auth::id())->with('orders', 'offers')->first()

        ]);
    }
}
