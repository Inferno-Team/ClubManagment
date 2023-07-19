<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\LocalResponse;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', "like", $request->email)->first();
        if (!Hash::check($request->password, $user->password))
            return LocalResponse::returnError('البيانات غير متوافقة', 400, [
                'password' => ['كملة السر خطأ']
            ]);


        $token = $user->createToken('authToken')->plainTextToken;
        return LocalResponse::returnData("login", [
            'token' => $token,
            'user' => $user
        ], "Logged in succesffully.", 200);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'name' => $request->name,
            'password' => $request->password,
            'type' => $request->type ?? 'customer',
            'age' => $request->age ?? null,
            'avatar' => $request->avatar ?? '',
        ]);
        $user->password = Hash::make($request->password);
        $user->update();
        $token = $user->createToken('authToken')->plainTextToken;
        return LocalResponse::returnData("login", [
            'token' => $token,
            'user' => $user
        ], "Account Creared Successfully.");
    }
}
