<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GiveRatingRequest;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\UpdateOrderStatusRequest;
use App\Jobs\AssignEmployeeJob;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            if ($request->get('address_id')) {
                $address = Address::find($request->address_id);
            } else {
                $address = Address::updateOrCreate([
                    'user_id' => Auth::id(),
                    'floor' => $request->address['floor'],
                    'street' => $request->address['street'],
                    'township' => $request->address['township'],
                    'city' => $request->address['city'],
                ]);
            }

            $order = Order::create([
                'address' => $address->floor . ', ' . $address->street . ', ' . $address->township . ', ' . $address->city,
                'employee_id' => $request->employee_id,
                'category_id' => $request->category_id,
                'started_at' => $request->started_at,
                'employer_id' => Auth::id(),
                'status' => $request->employee_id ? OrderStatus::OFFERED->value : OrderStatus::ORDERED->value,
            ]);

            $services = collect($request->services)->map(function ($service) {
                return [
                    'quantity' => $service['quantity'],
                    'service_id' => $service['service_id'],
                ];
            })->toArray();

            $order->services()->sync($services);

            DB::commit();

            return response()->json([
                'message' => 'Order has been placed.',
                'data' => $order->load('services'),
            ]);
        } catch (Exception $exception) {
            Log::error('Something went wrong in OrderController@store: ' . $exception);
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'data' => $exception,
            ], 500);
        }
    }

    public function index()
    {
        $orders = Order::where('employee_id', Auth::id())->orWhere('employer_id', Auth::id())
            ->orderByRaw("CASE
                        WHEN orders.status = 'ORDERED' THEN 1
                        WHEN orders.status = 'OFFERED' THEN 2
                        WHEN orders.status = 'ACCEPTED' THEN 3
                        WHEN orders.status = 'PAID' THEN 4
                        WHEN orders.status = 'IN_PROGRESS' THEN 5
                        WHEN orders.status = 'COMPLETED' THEN 6
                        WHEN orders.status = 'CANCELLED_BY_EMPLOYER' THEN 7
                        WHEN orders.status = 'CANCELLED_BY_SYSTEM' THEN 8
                    ELSE 9
            END")
            ->orderBy('created_at', 'desc')
            ->with('category', 'employee', 'employer', 'services')
            ->get();

        $orders = $orders->map(function ($order) {
            $order->services->each(function ($service) use ($order) {
                $order->total_price += $service->price * $service->pivot->quantity;
            });

            if ($order->employee_id == Auth::id()) {
                $order->total_price = $order->total_price - (config("app.platform_fee_percentage") / 100 * $order->total_price);
            }

            return $order;
        });

        return response()->json([
            'message' => 'Orders have been retrieved.',
            'data' => [
                'services' => $orders->filter(function (Order $order) {
                    return $order->employee_id == Auth::id();
                })->values(),
                'offers' => $orders->filter(function (Order $order) {
                    return $order->employer_id == Auth::id();
                })->values(),
            ],
        ]);
    }

    public function show(Order $order)
    {
        $order = $order->load(['services', 'employee', 'employer']);
        $totalPrice = 0;
        $order->services->each(function ($service) use (&$totalPrice, $order) {
            $totalPrice += $service->price * $service->pivot->quantity;
        });

        if ($order->employee_id === Auth::id()) {
            $order->total_price = $totalPrice - (config("app.platform_fee_percentage") / 100 * $totalPrice);
            $order->contact = User::find($order->employer_id);
        } else {
            $order->contact = User::find($order->employee_id);
            $order->total_price = $totalPrice;
        }

        return response()->json([
            'message' => 'Order has been retrieved.',
            'data' => $order,
        ]);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $order->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Order status has been updated.',
            'data' => $order->load('services'),
        ]);
    }

    public function giveRatings(Order $order, GiveRatingRequest $request)
    {
        $order->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'message' => 'Ratings have been given.',
            'data' => $order->load('services'),
        ]);
    }
}
