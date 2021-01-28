<?php

namespace Database\Factories;

use App\Models\FollowingAccount;
use App\Models\Giveaway;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class GiveawayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Giveaway::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::all();

        return [
            "name" =>  $this->faker->name,
            "link" =>  $this->faker->text(50),
            "description" =>  $this->faker->text(),
            "finish_date" =>  Carbon::now()->addDay(1)->toString(),
            "user_id" => $users->random()->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Giveaway $giveaway) {
            $followingAccounts = FollowingAccount::all();
            $giveaway->followingAccounts()->sync($followingAccounts->modelKeys());
        });
    }
}
