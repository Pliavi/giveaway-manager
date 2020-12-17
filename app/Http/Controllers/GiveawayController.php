<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiveawayStoreRequest;
use App\Models\Giveaway;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GiveawayController extends Controller
{

    function __construct()
    {
        $this->authorizeResource(Giveaway::class, 'giveaway');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $giveawaysQuery = $request->user()->giveaways();

        return [
            "finished" => $giveawaysQuery->where("finished_data", "<=", $now)->where("is_finished", false),
            "running" => $giveawaysQuery->get()->where("finished_data", ">", $now)
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\GiveawayStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GiveawayStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $giveawayData = $request->get("giveaway_data");
            $followingAccountsDataArray = $request->get("following_accounts", []);

            $giveaway = new Giveaway($giveawayData);
            $giveaway->user()->associate(Auth::user());
            $giveaway->save();

            $followingAccounts = array_map(function ($accountData) use ($request) {
                $user = $request->user();
                $account = $user->followingAccounts()->firstOrNew($accountData);
                $account->user()->associate(Auth::id());
                $account->socialNetwork()->associate($accountData['social_network_id']);

                return $account;
            }, $followingAccountsDataArray);

            $giveaway->followingAccounts()->saveMany($followingAccounts);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Giveaway  $giveaway
     * @return \Illuminate\Http\Response
     */
    public function show(Giveaway $giveaway)
    {
        return $giveaway;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Giveaway  $giveaway
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Giveaway $giveaway)
    {
        DB::transaction(function() use ($request, $giveaway){
            $giveawayData = $request->get("giveaway_data");
            $addedFollowingAccountsDataArray = $request->get("added_following_accounts", []);
            $removedFollowingAccountNames = $request->get("removed_following_accounts", []);

            $giveaway->update($giveawayData);

            $followingAccountsToRemove = $request
                ->user()
                ->followingAccounts()
                ->whereIn($removedFollowingAccountNames)
                ->get();

            foreach($followingAccountsToRemove as $account) {
                $account->user()->dissociate();
                $account->giveaways()->dissociate();
            }

            $followingAccounts = array_map(function ($accountData) use ($request) {
                $user = $request->user();
                $account = $user->followingAccounts()->firstOrNew($accountData);
                $account->user()->associate(Auth::id());
                $account->socialNetwork()->associate($accountData['social_network_id']);

                return $account;
            }, $addedFollowingAccountsDataArray);

            $giveaway->followingAccounts()->saveMany($followingAccounts);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Giveaway  $giveaway
     * @return \Illuminate\Http\Response
     */
    public function destroy(Giveaway $giveaway)
    {
        $giveaway->delete();
    }
}
