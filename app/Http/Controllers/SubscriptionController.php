<?php

namespace App\Http\Controllers;

use App\Http\Requests\subscriptions\CreateSubscriptionRequest;
use App\Http\Requests\subscriptions\DeleteSubscriptionRequest;
use App\Http\Requests\subscriptions\UpdateSubscriptionRequest;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use Carbon\Carbon;

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
    public function showJustNameSubscription()
    {
        $subscriptions = SubscriptionType::get()->map(function ($sub) {
            return (object)[
                'id' => $sub->id,
                'name' => $sub->name,
            ];
        });
        return LocalResponse::returnData("subs", $subscriptions);
    }
    public function showSingleSubscription(int $id)
    {
        $subscription = SubscriptionType::where('id', $id)->with('club_subscriptions')->get()->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'name' => $item->name,
                'last_year_subscriptions' => $item->getThisYearCustomersSubscriptions(),
                "clubs" => $item->club_subscriptions->filter(function ($club_subscription) {
                    $valYear = Carbon::parse($club_subscription->created_at)->format('Y');
                    $extaptedYear = Carbon::now()->format('Y');
                    return $valYear == $extaptedYear;
                })->values()->map->formatForAdmin(),
                "number_of_customer_sub" => count($item->customer_subscriptions->filter(function ($sub) use ($item) {
                    return $sub->sub->subscription_id == $item->id;
                })->values()),
                "number_of_last_month_subs" => count($item->customer_subscriptions->filter(function ($sub) use ($item) {
                    $this_mounth = Carbon::now()->subMonth()->month;
                    $sub_month = Carbon::parse($sub->start_at)->month;
                    return $sub->sub->subscription_id == $item->id && $this_mounth == $sub_month;
                })->values()),

            ];
        })[0];
        return LocalResponse::returnData("sub", $subscription);
    }
}
