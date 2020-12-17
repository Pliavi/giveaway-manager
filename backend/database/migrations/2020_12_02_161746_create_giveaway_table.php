<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiveawayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('giveaways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('finish_date');
            $table->boolean('is_notified')->default(false);
            $table->boolean('is_finished')->default(false);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('giveaways');
    }
}
