<?php

namespace App\Http\Controllers;

use App\Http\Requests\subscriptions\CreateSubscriptionRequest;
use App\Http\Requests\subscriptions\DeleteSubscriptionRequest;
use App\Http\Requests\subscriptions\UpdateSubscriptionRequest;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;

class SubscriptionController extends Controller
{
    public function createSubscription(CreateSubscriptionRequest $request)
    {
        $subscription = SubscriptionType::create([
            'name' => $request->name,
        ]);
        return LocalResponse::returnData("sub", $subscription, "created successfully");
    }
    public function deleteSubscription(DeleteSubscriptionRequest $request)
    {
        SubscriptionType::find($request->id)->delete();
        return LocalResponse::returnMessage("subscription deleted");
    }
    public function updateSubscription(UpdateSubscriptionRequest $request)
    {
        SubscriptionType::find($request->id)->update([
            'name' => $request->name,
        ]);
        return LocalResponse::returnMessage("subscription updated");
    }
    public function showAllSubscription()
    {
        $subscriptions = SubscriptionType::all();
        return LocalResponse::returnData("subs", $subscriptions);
    }
}
