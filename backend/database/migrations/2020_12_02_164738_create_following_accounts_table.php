<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowingAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('following_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username');

            $table->boolean('is_unfollowed')->default(false);

            $table->timestamps();
            
            $table->foreignId('social_network_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('following_accounts');
    }
}
