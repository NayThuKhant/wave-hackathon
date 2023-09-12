<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function me() {
        return response()->json([
            "message" => "Profile information has been successfully retrieved.",
            "data" => User::with("employee", "employer")->find(Auth::id())
        ]);
    }

    public function startWorking() {
        $employee = Auth::user()->employee()->firstOrCreate();

        return response()->json([
            "message" => "Employee profile has been created",
            "data" => $employee
        ]);
    }
}
