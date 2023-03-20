<?php

namespace App\Http\Controllers;

use App\Http\Requests\Clubs\CreateClubRequest;
use App\Http\Requests\Clubs\DeleteClubRequest;
use App\Http\Requests\clubs\DeleteClubSubscrpitionRequest;
use App\Http\Requests\clubs\MakeClubSubscrpitionRequest;
use App\Http\Requests\Clubs\UpdateClubRequest;
use App\Models\Club;
use Illuminate\Http\Request;
use App\Http\Traits\LocalResponse;
use App\Models\ClubSubScription;
use App\Models\User;
use App\Models\UserSubscription;

class ClubController extends Controller
{
    public function createNewClub(CreateClubRequest $request)
    {
        $manager = User::create($request->getManager());
        $club = Club::create($request->values($manager->id));
        $club->manager = $manager;
        return LocalResponse::returnData('club', $club, 'created successfully.', 201);
    }
    public function deleteClub(DeleteClubRequest $request)
    {
        $club = Club::findOrFail($request->club_id);
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
    public function searchClub(Request $request)
    {

        $clubs = Club::where('name', 'LIKE', '%' . $request->input('search') . '%')
            ->orWhere('location', 'LIKE', '%' . $request->input('search') . '%')
            ->get();
        return LocalResponse::returnData('clubs', $clubs, 'found', 200);
    }
    public function showClub(int $id)
    {
        $club = Club::findOrFail($id);
        return LocalResponse::returnData('club', $club, 'found', 200);
    }
    public function showAllClub()
    {
        $clubs = Club::all();
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

    public function getAllSubscriptions(Request $request)
    {
        $club = $request->user()->club;
        $subscriptions = ClubSubScription::where('club_id', $club->id)->get();
        return LocalResponse::returnData('subscriptions', $subscriptions, 'found', 200);
    }

    public function getAllUsersSubscriptions(Request $request)
    {
        $club = $request->user()->club;
        $subs = UserSubscription::with('sub', 'customer')->get()->filter(function ($item) use ($club) {
            return $item->sub->club_id == $club->id;
        })->map->format();
        return LocalResponse::returnData('subscriptions', $subs, 'found', 200);
    }
}
