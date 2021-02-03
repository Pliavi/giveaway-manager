<?php

namespace Database\Factories;

use App\Models\FollowingAccount;
use App\Models\User;
use App\Models\SocialNetwork;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowingAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FollowingAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $socialNetwork = SocialNetwork::factory()->create();

        return [
            "username" => $this->faker->userName,
            "social_network_id" => $socialNetwork->id,
        ];
    }
}
