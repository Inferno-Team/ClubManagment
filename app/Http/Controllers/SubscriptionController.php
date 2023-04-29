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
        $sub = SubscriptionType::where('id', $request->id)->first();

        $sub->update([
            'name' => $request->name,
        ]);
        return LocalResponse::returnData("sub", $sub, 'Subscription updated successffully.');
    }
    public function showAllSubscription()
    {
        $subscriptions = SubscriptionType::get()->map->getAllFormat();
        return LocalResponse::returnData("subs", $subscriptions);
    }
    public function showSingleSubscription(int $id)
    {
        $subscription = SubscriptionType::where('id', $id)->first()->singleSubscriptionFormat();
        return LocalResponse::returnData("sub", $subscription);
    }
}
