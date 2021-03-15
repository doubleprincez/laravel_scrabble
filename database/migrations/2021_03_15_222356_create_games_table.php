<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id_1');
            $table->unsignedBigInteger('user_id_2')->nullable();
            $table->unsignedBigInteger('user_id_3')->nullable();
            $table->unsignedBigInteger('user_id_4')->nullable();
            $table->text('user_1_chavolet')->nullable();
            $table->text('user_2_chavolet')->nullable();
            $table->text('user_3_chavolet')->nullable();
            $table->text('user_4_chavolet')->nullable();
            $table->integer('user_1_score')->nullable()->default(0);
            $table->integer('user_2_score')->nullable()->default(0);
            $table->integer('user_3_score')->nullable()->default(0);
            $table->integer('user_4_score')->nullable()->default(0);
            $table->boolean('game_status')->nullable();
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
        Schema::dropIfExists('games');
    }
}
