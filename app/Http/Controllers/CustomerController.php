<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerSubscribeRequest;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;

class CustomerController extends Controller
{
    public function subscribeToClub(CustomerSubscribeRequest $request)
    {
        $usc = UserSubscription::create([
            'customer_id' => $request->user()->id,
            'subscription_id' => $request->sub_id,
            "start_at" => now(),
            "end_at" => now()->addDay(),
        ]);
        return LocalResponse::returnData("usc", $usc, 'created successfully');
    }
}
