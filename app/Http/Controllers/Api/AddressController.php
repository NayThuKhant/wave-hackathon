<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreAddressRequest;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Addresses have been retrieved.',
            'data' => Auth::user()->addresses,
        ]);
    }

    public function store(StoreAddressRequest $request)
    {
        $address = Auth::user()->addresses()->create($request->validated());

        return response()->json([
            'message' => 'Address has been created.',
            'data' => $address,
        ]);
    }
}
