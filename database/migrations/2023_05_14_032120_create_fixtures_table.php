<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();

            //FK
            $table->unsignedBigInteger('torneo_id');
            $table->foreign('torneo_id')->references('id')->on('torneos');

            $table->unsignedBigInteger('campo_id')->nullable();
            $table->foreign('campo_id')->references('id')->on('campos');

            $table->unsignedBigInteger('equipo_1');
            $table->foreign('equipo_1')->references('id')->on('equipos');

            $table->unsignedBigInteger('equipo_2');
            $table->foreign('equipo_2')->references('id')->on('equipos');

            $table->unsignedBigInteger('arbitro_id')->nullable();
            $table->foreign('arbitro_id')->references('id')->on('arbitros');

            $table->integer('fecha_nro');
            $table->date('partido_fecha')->nullable();
            $table->string('partido_hora')->nullable();
            $table->integer('equipo_1_goles')->default(0);
            $table->integer('equipo_2_goles')->default(0);

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('fixtures');
    }
}
