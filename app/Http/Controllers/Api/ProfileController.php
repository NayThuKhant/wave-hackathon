<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscribeCategoriesRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function me()
    {
        return response()->json([
            'message' => 'Profile information has been successfully retrieved.',
            'data' => User::with('employee', 'employer', 'categories', 'addresses')->find(Auth::id()),
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
