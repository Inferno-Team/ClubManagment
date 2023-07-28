<?php

namespace App\Http\Controllers;

use App\Http\Requests\customer\CheckIfSubscribedDietRequest;
use App\Http\Requests\customer\CheckIfSubscribedRequest;
use App\Http\Requests\customer\SubscribeToDietRequest;
use App\Http\Requests\CustomerSubscribeRequest;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Models\Club;
use App\Models\ClubSubScription;
use App\Models\EatTable;
use App\Models\UserEatTable;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function subscribeToClub(CustomerSubscribeRequest $request)
    {
        $sub = ClubSubScription::where('id', $request->sub_id)->with('subscription')->first();
        $end_at = now();
        info(now());

        $duration = $sub->subscription->duration;
        $end_at = now()->addDays($duration);
        $usc = UserSubscription::create([
            'customer_id' => $request->user()->id,
            'subscription_id' => $request->sub_id,
            "start_at" => now(),
            "end_at" => $end_at,
            'price' => $sub->price,
        ]);
        return LocalResponse::returnData("usc", $usc, 'Created Successfully');
    }
    public function getAllTables()
    {
        $tables = EatTable::get()->map->formatForCustomers();
        return LocalResponse::returnData('tables', $tables);
    }
    public function subscribeToTable(SubscribeToDietRequest $request)
    {
        $user = Auth::user();
        $old_user_diet = UserEatTable::where('user_id', $user->id)
            ->where('table_id', $request->id)->first();
        if (!empty($old_user_diet))
            return LocalResponse::returnMessage("You are already subscribe to to this diet.", 200);
        $user_diet = UserEatTable::create([
            'user_id' => $user->id,
            'table_id' => $request->id,
        ]);
        return LocalResponse::returnData("user_diet", $user_diet, 'Subscribed Successfully');
    }
    public function unSubscribeToTable(SubscribeToDietRequest $request)
    {
        $user = Auth::user();
        $old_user_diet = UserEatTable::where('user_id', $user->id)
            ->where('table_id', $request->id)->first();
        if (!empty($old_user_diet)) {
            $old_user_diet->delete();
            return LocalResponse::returnMessage("Un Subscribed Successfully.", 200);
        } else {
            return LocalResponse::returnMessage("you need to subscribe First.", 200);
        }
    }
    public function checkIfSubscribed(CheckIfSubscribedRequest $request)
    {
        $club = Club::where('id', $request->club_id)->first();
        $customer = Auth::user();
        $customer_club = UserSubscription::where('customer_id', $customer->id)
            ->whereHas('sub', function ($sub) use ($club) {
                $sub->where('club_id', $club->id);
            })->get()->filter(function ($subscription) {
                return $subscription->is_valid;
            })->values();
        return LocalResponse::returnData('subscribed', (object)[
            'value' => count($customer_club) != 0
        ]);
    }
    public function checkIfSubscribedDiet(CheckIfSubscribedDietRequest $request)
    {
        $table = EatTable::where('id', $request->club_id)->first();
        $customer = Auth::user();
        $customer_table = UserEatTable::where('user_id', $customer->id)
            ->where('table_id', $table->id)->first();
        return LocalResponse::returnData('subscribed', (object)[
            'value' => !empty($customer_table)
        ]);
    }
    public function singleClubSubscription(Request $request)
    {
        $subs = ClubSubScription::where('club_id', $request->id)->get()->map->formatForCusotmer();
        return LocalResponse::returnData('subs', $subs, 'found', !empty($subs) ? 200 : 401);
    }

    public function getMyDietsSubscriptions()
    {
        $customer = Auth::user();
        $subs = UserEatTable::where('user_id', $customer->id)->with('table')->get();
        $ids = $subs->pluck('table.id')->values();
        $tables = EatTable::whereIn('id', $ids)->get()->map->formatForCustomers();
        return LocalResponse::returnData('tables', $tables);
    }
}
