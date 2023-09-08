<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
//        dd($request->all());
        $user = User::firstOrCreate(
            ['mobile_number' => $request->msisdn],
            [
                'name' => $request->name,
                'dob' => $request->dob,
                'nrc' => $request->nrc,
                'gender' => $request->gender,
                'kyc_status' => $request->kyc_status
            ]
        );

        // TODO : Check User's KYC Status
        $token = $user->createToken('auth_token')->accessToken;
        return response()->json(['token' => $token, 'user' => $user], 200);
    }
}
