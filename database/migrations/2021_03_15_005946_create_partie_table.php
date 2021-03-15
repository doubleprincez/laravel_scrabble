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
            $table->unsignedBigInteger('idJoueur1')->nullable();
            $table->unsignedBigInteger('idJoueur2')->nullable();
            $table->unsignedBigInteger('idJoueur3')->nullable();
            $table->unsignedBigInteger('idJoueur4')->nullable();
            $table->boolean('typePartie')->nullable();
            $table->text('grille')->nullable();
            $table->datetime('dateCreation')->nullable()->default(now());
            $table->datetime('dateDebutPartie')->nullable();
            $table->datetime('dateFin')->nullable();
            $table->text('statutPartie')->nullable()->default('enAttente');
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
