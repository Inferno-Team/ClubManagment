<?php

namespace App\Http\Controllers;

use App\Http\Requests\customer\SubscribeToDietRequest;
use App\Http\Requests\CustomerSubscribeRequest;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Models\EatTable;
use App\Models\UserEatTable;
use Illuminate\Support\Facades\Auth;

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
        return LocalResponse::returnData("usc", $usc, 'Created Successfully');
    }
    public function getAllTables()
    {
        $tables = EatTable::get()->map->format();
        return LocalResponse::returnData('tables', $tables);
    }
    public function subscribeTotable(SubscribeToDietRequest $request)
    {
        $user = Auth::user();
        $user_diet = UserEatTable::create([
            'user_id' => $user->id,
            'table_id' => $request->id,
        ]);
        return LocalResponse::returnData("user_diet", $user_diet, 'Created Successfully');
    }
}
