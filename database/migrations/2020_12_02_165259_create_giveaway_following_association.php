<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiveawayFollowingAssociation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('following_account_giveaway', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->foreignId('following_account_id')->constrained();
            $table->foreignId('giveaway_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('following_account_giveaway');
    }
}
