<?php

namespace Database\Seeders;

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
        User::factory(2)->create();
        SocialNetwork::factory(2)->create();
    }
}
