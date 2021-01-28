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
        User::factory(["email" => "teste@teste.com"])->create();
        User::factory(1)->create();
        SocialNetwork::factory(2)->create();
        FollowingAccount::factory(2)->create();
        Giveaway::factory(2)->create();
    }
}
