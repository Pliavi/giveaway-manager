<?php

namespace Database\Seeders;

use App\Models\FollowingAccount;
use App\Models\Giveaway;
use App\Models\SocialNetwork;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(["email" => "seed@seed.com"])->create();
        $user = User::factory()->create();
        SocialNetwork::factory(2)->create();
        FollowingAccount::factory(2)->create();

        $user->giveaways()->saveMany(Giveaway::factory(2)->make());
    }
}
