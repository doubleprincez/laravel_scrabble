<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partie', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->boolean('typePartie')->nullable();
            $table->text('grille')->nullable();
            $table->datetime('dateDebutPartie')->nullable();
            $table->datetime('dateFin')->nullable();
            $table->text('statutPartie')->nullable()->default('enAttente');
            $table->softDeletes();
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
        Schema::dropIfExists('partie');
    }
}
