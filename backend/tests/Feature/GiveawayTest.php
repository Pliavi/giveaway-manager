<?php

namespace Tests\Feature;

use App\Models\FollowingAccount;
use App\Models\Giveaway;
use App\Models\SocialNetwork;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GiveawayTest extends TestCase
{

    use RefreshDatabase;
    use WithFaker;

    /**
     * @test
     */
    public function it_successfully_create_a_giveaway()
    {
        $this->withoutExceptionHandling();

        /**
         * @var FollowingAccount
         */
        $followingAccount = FollowingAccount::factory()->create();

        $giveawayData = [
            "name" =>  "test name",
            "link" =>  "http://www.test.com/giveaway",
            "description" =>  "a test description",
            "finish_date" =>  "2012-12-11",
            "following_accounts"  =>  [
                [
                    "username" =>  $followingAccount->username,
                    "social_network_id" =>  $followingAccount->socialNetwork->id
                ]
            ]
        ];

        $this->actingAs($this->loggedInUser)
            ->postJson('/api/giveaway', $giveawayData)
            ->assertStatus(201)
            ->assertJson(
                collect($giveawayData)
                    ->except(["finish_date"])
                    ->toArray()
            );
    }

    /**
     * @test
     */
    public function it_successfully_update_a_giveaway()
    {
        /** @var Giveaway */
        $giveaway = $this->loggedInUser
            ->giveaways()
            ->save(Giveaway::factory()->make());
        $socialNetwork = SocialNetwork::factory()->create();

        $oldFollowingAccount = FollowingAccount::factory()->create();
        $giveaway->followingAccounts()->attach($oldFollowingAccount);

        $giveawayUpdateData = [
            "name" =>  "updated name",
            "finish_date" =>  "2014-11-12",
            "added_following_accounts" => [
                [
                    "username" => $this->faker->userName,
                    "social_network_id" => $socialNetwork->id
                ]
            ],
            "removed_following_accounts" => [$oldFollowingAccount->id]
        ];

        $this->actingAs($this->loggedInUser)
            ->putJson("/api/giveaway/{$giveaway->id}", $giveawayUpdateData)
            ->assertStatus(200)
            ->assertJson(
                collect($giveawayUpdateData)
                    ->except(
                        [
                            "finish_date",
                            "added_following_accounts",
                            "removed_following_accounts"
                        ]
                    )
                    ->toArray()
            );
    }
    /** @test */
    public function it_unauthorize_and_fail_to_update_another_user_giveaway()
    {
        /** @var Giveaway */
        $giveaway = $this->simpleUser
            ->giveaways()
            ->save(Giveaway::factory()->make());

        $giveawayUpdateData = [
            "name" =>  "will not update name",
        ];

        $this->actingAs($this->loggedInUser)
            ->putJson("/api/giveaway/{$giveaway->id}", $giveawayUpdateData)
            ->dump()
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function it_successfully_deletes_a_giveaway()
    {
        $giveaway = $this->loggedInUser
            ->giveaways()
            ->save(Giveaway::factory()->make());

        $this->actingAs($this->loggedInUser)
            ->delete("/api/giveaway/{$giveaway->id}")
            ->assertSuccessful();

        $this->expectException(ModelNotFoundException::class);
        $giveaway->refresh();
    }

    /**
     * @test
     */
    public function it_successfully_shows_a_giveaway()
    {
        $giveaway = $this->loggedInUser
            ->giveaways()
            ->save(Giveaway::factory()->make());

        $this->actingAs($this->loggedInUser)
            ->get("/api/giveaway/{$giveaway->id}")
            ->assertSuccessful()
            ->assertJson($giveaway->toArray());
    }

    /**
     * @test
     */
    public function it_successfully_shows_all_user_giveaways()
    {
        $this->loggedInUser
            ->giveaways()
            ->saveMany(Giveaway::factory(3)->make());
        $this->loggedInUser
            ->giveaways()
            ->saveMany(Giveaway::factory(2)->make([
                "finish_date" => Carbon::now()->subDay()
            ]));

        $this->actingAs($this->loggedInUser)
            ->get("/api/giveaway")
            ->assertSuccessful()
            ->assertJsonStructure([
                "finished",
                "running"
            ])
            ->assertJsonCount(2, "finished")
            ->assertJsonCount(3, "running");
    }
}
