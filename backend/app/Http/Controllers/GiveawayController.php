<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiveawayStoreRequest;
use App\Models\FollowingAccount;
use App\Models\Giveaway;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        /** @var User */
        $user = $request->user();
        $now = Carbon::now();

        return [
            "finished" => $user
                ->giveaways()
                ->whereDate("finish_date", "<", $now)
                ->orWhere("is_finished", true)
                ->get()->toArray(),
            "running" => $user
                ->giveaways()
                ->whereDate("finish_date", ">=", $now)
                ->get()->toArray(),
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
        return DB::transaction(
            function () use ($request) {
                $sessionUser = $request->user();
                $giveawayData = $request->except("following_accounts");
                $followingAccountsDataArray = $request->get("following_accounts", []);

                $giveaway = new Giveaway($giveawayData);
                $giveaway->user()->associate($sessionUser);
                $giveaway->save();

                $followingAccounts = array_map(function ($accountData) {
                    /** @var FollowingAccount */
                    $account = FollowingAccount::firstOrNew($accountData);
                    if (!$account->exists) {
                        $account->socialNetwork()->associate($accountData['social_network_id']);
                    }

                    return $account;
                }, $followingAccountsDataArray);

                $giveaway->followingAccounts()->saveMany($followingAccounts);

                return $giveaway->load("followingAccounts");
            }
        );
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
        return DB::transaction(function () use ($request, $giveaway) {
            $giveawayData = $request->all();
            $followingAccountsToAdd = $request->get("added_following_accounts", []);
            $followingAccountsToRemove = $request->get("removed_following_accounts", []);

            $giveaway->update($giveawayData);
            $giveaway->followingAccounts()->detach($followingAccountsToRemove);

            $followingAccounts = array_map(function ($accountData) {
                /** @var FollowingAccount */
                $account = FollowingAccount::firstOrNew($accountData);
                if (!$account->exists) {
                    $account->socialNetwork()->associate($accountData['social_network_id']);
                }

                return $account;
            }, $followingAccountsToAdd);

            $giveaway->followingAccounts()->saveMany($followingAccounts);

            return $giveaway->load("followingAccounts");
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
