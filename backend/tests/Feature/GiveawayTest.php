<?php

namespace Tests\Feature;

use App\Models\Giveaway;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GiveawayTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::all()->random();
    }

    public function testPostGiveaway()
    {
        $response = $this->actingAs($this->user)->json('POST', '/api/giveaways', [
            "name" =>  "teste",
            "link" =>  "teste",
            "description" =>  "teste",
            "finish_date" =>  "2012-12-11",
            "following_accounts"  =>  [
                [
                    "username" =>  "teste_user",
                    "social_network_id" =>  1
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    public function testDeleteGiveaway()
    {
        $response = $this->actingAs($this->user)->json('DELETE', '/api/giveaways/1');

        $response->assertStatus(404);
    }
}
