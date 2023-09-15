<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Http\Requests\Api\UpdateOrderStatusRequest;
use App\Models\Address;
use App\Models\Order;
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
            if ($request->has('address_id')) {
                $address = Address::find($request->address_id);
            } else {
                $address = Address::create([...$request->address, 'user_id' => Auth::id()]);
            }

            $order = Order::create([
                'address' => $address->floor.', '.$address->street.', '.$address->township.', '.$address->city,
                'employee_id' => $request->employee_id,
                'category_id' => $request->category_id,
                'started_at' => $request->started_at,
                'employer_id' => Auth::id(),
            ]);

            $order->services()->sync($request->services);

            DB::commit();

            return response()->json([
                'message' => 'Order has been placed.',
                'data' => $order->load('services'),
            ]);
        } catch (Exception $exception) {
            Log::error('Something went wrong in OrderController@store: '.$exception);
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong',
                'data' => $exception,
            ]);
        }
    }

    public function index()
    {
        return response()->json([
            'message' => 'Orders have been retrieved.',
            'data' => [
                'orders' => Order::where('employee_id', Auth::id())->with('category', 'employee')->get(),
                'offers' => Order::where('employer_id', Auth::id())->with('category', 'employer')->get(),
            ],
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
}
