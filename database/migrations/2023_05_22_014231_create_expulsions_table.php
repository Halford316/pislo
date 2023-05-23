<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpulsionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expulsions', function (Blueprint $table) {
            $table->id();

            //FK
            $table->unsignedBigInteger('torneo_id');
            $table->foreign('torneo_id')->references('id')->on('torneos');

            $table->unsignedBigInteger('campo_id')->nullable();
            $table->foreign('campo_id')->references('id')->on('campos');

            $table->unsignedBigInteger('fixture_id')->nullable();
            $table->foreign('fixture_id')->references('id')->on('fixtures');

            $table->unsignedBigInteger('jugador_id')->nullable();
            $table->foreign('jugador_id')->references('id')->on('jugadors');

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
        Schema::dropIfExists('expulsions');
    }
}
