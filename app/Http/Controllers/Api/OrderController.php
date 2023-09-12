<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {

    }

    public function index()
    {
        return response()->json([
            'message' => 'Orders have been retrieved.',
            'data' => Order::where('employer_id', Auth::id())->orWhere('employee_id', Auth::id())->get(),
        ]);
    }
}
