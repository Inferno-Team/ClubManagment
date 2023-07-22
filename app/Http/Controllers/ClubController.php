<?php

namespace App\Http\Controllers;

use App\Http\Helpers\FileHelper;
use App\Http\Requests\Clubs\CreateClubRequest;
use App\Http\Requests\Clubs\DeleteClubRequest;
use App\Http\Requests\clubs\DeleteClubSubscrpitionRequest;
use App\Http\Requests\Clubs\EditClubManager;
use App\Http\Requests\manager\MakeClubSubscrpitionRequest;
use App\Http\Requests\Clubs\UpdateClubRequest;
use App\Http\Requests\manager\ApproveCustomerSubscriptionRequest;
use App\Http\Requests\manager\DeleteCustomerSubscriptionRequest;
use App\Models\Club;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Models\ClubSubScription;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    public function createNewClub(CreateClubRequest $request)
    {
        $manager = User::create($request->getManager());
        $club = Club::create($request->values($manager->id));
        if ($request->hasFile('image'))
            $club->image = FileHelper::uploadFileOnPublic($request->file('image'));
        $club->update();
        // $club = Club::find($club->id)->with('manager');
        $club->manager = $manager;
        return LocalResponse::returnData('club', $club, 'created successfully.', 201);
    }
    public function deleteClub(DeleteClubRequest $request)
    {
        $club = Club::find($request->club_id);
        $club->delete();
        User::find($club->manager_id)->delete();
        return LocalResponse::returnMessage('club deleted successfully.', 200);
    }
    public function updateClub(UpdateClubRequest $request)
    {
        $club = Club::findOrFail($request->club_id);
        $club->update($request->all());
        return LocalResponse::returnMessage('club updated successfully.', 200);
    }
    public function editClubManager(EditClubManager $request)
    {
        $manager = User::where('id', $request->manager_id)->first();
        if (isset($request->manager->email)) {
            $emailUser = User::where('email', $request->email)->first();
            if (isset($emailUser) && $manager->id != $emailUser->id) {
                return LocalResponse::returnError('Email already on use.', 301);
            }
        }
        $manager->update($request->values($manager));
        return LocalResponse::returnData('manager', $manager, 'Manager Updated Successfully.', 200);
    }
    public function searchClub(Request $request)
    {

        $clubs = Club::where('name', 'LIKE', '%' . $request->input('search') . '%')
            ->orWhere('location', 'LIKE', '%' . $request->input('search') . '%')
            ->get();
        return LocalResponse::returnData('clubs', $clubs, 'found', 200);
    }

    public function myClub()
    {
        $manager = Auth::user();
        $club = Club::where('manager_id', $manager->id)->with('customer_sub')->first()->myClubFormat();
        return LocalResponse::returnData('club', $club, 'found', isset($club) ? 200 : 401);
    }

    public function mySingleClubSubscription(int $id)
    {
        $clubSubscription = ClubSubScription::where('id', $id)->first()->format();
        return LocalResponse::returnData('sub', $clubSubscription, 'found', isset($clubSubscription) ? 200 : 401);
    }
    public function showClub(int $id)
    {
        $club = Club::where('id', $id)->with('customer_sub')->first()->formatForAdmin();
        return LocalResponse::returnData('club', $club, 'found', isset($club) ? 200 : 401);
    }
    public function showAllClub()
    {
        $clubs = Club::get()->map->format();
        return LocalResponse::returnData('clubs', $clubs, 'found', 200);
    }

    public function makeClubSubscription(MakeClubSubscrpitionRequest $request)
    {
        $cs = ClubSubScription::create($request->values());
        return LocalResponse::returnData("cs", $cs, "created successfully.", 201);
    }
    public function deleteClubSubscription(DeleteClubSubscrpitionRequest $request)
    {
        ClubSubScription::find($request->id)->delete();
        return LocalResponse::returnMessage("Club Subscrpition deleted successfully.", 200);
    }

    public function deleteCustomerSubscription(DeleteCustomerSubscriptionRequest $request)
    {
        // check if this request is on this manager customers
        $manager = Auth::user();
        $club = Club::where('manager_id', $manager->id)->first();
        $userSubscription = UserSubscription::where('id', $request->id)->with('sub')->first();
        if ($userSubscription->sub->club_id == $club->id) {
            // we can delete this subscription now
            $userSubscription->delete();
            return LocalResponse::returnMessage('Subscription Deleted successfully');
        } else return LocalResponse::returnMessage("this subscription doesn't belongs to your club.");
    }
    public function approveCustomerSubscription(ApproveCustomerSubscriptionRequest $request)
    {
        // check if this request is on this manager customers
        $manager = Auth::user();
        $club = Club::where('manager_id', $manager->id)->first();
        $userSubscription = UserSubscription::where('id', $request->id)->with('sub')->first();
        if ($userSubscription->sub->club_id == $club->id) {
            // we can approve this subscription now
            $userSubscription->approve();
            return LocalResponse::returnMessage('Subscription Approved successfully');
        } else return LocalResponse::returnMessage("this subscription doesn't belongs to your club.");
    }

    public function getAllSubscriptions(Request $request)
    {
        $club = $request->user()->club;


        $subscriptions = ClubSubScription::where('club_id', $club->id)->get()->map->format();
        return LocalResponse::returnData('subscriptions', $subscriptions, 'found', 200);
    }

    public function getAllUsersSubscriptions(Request $request)
    {
        $club = $request->user()->club;
        $subs = UserSubscription::with('sub', 'customer')->get()->filter(function ($item) use ($club) {
            return $item->sub->club_id == $club->id;
        })->values()->map->format();
        return LocalResponse::returnData('subscriptions', $subs, 'found', 200);
    }
    public function getAllJoiningRequests()
    {
        $manager = Auth::user();
        $club = $manager->club;
        $user_sub = UserSubscription::whereHas(
            'sub',
            fn ($query) => $query->where('club_id', $club->id)
        )->where('approved', 'waiting')->with('customer')->get();
        return LocalResponse::returnData('requests', $user_sub);
    }
    public function approveCustomerSub(ApproveCustomerSubscriptionRequest $request)
    {
        $sub = UserSubscription::where('id', $request->id)->first();
        if ($sub->approved !== 'waiting')
            return LocalResponse::returnMessage("can't approve this request, Already handled.");
        $sub->approved = $request->approve;
        $sub->update();
        return LocalResponse::returnMessage("subscription has been " . $sub->approved);
    }
    public function getAllManagerRequests()
    {
        $manager = Auth::user();
        $club = Club::where('manager_id', $manager->id)->first();
        $customerRequests = UserSubscription::where('status', 'pending')->whereHas("sub", function ($sub) use ($club) {
            $sub->where('club_id', $club->id);
        })->get()->map->formatOnly();
        return LocalResponse::returnData("requests", $customerRequests);
    }
}
