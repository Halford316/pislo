<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkToEquipoPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipo_pagos', function (Blueprint $table) {
            //FK
            $table->unsignedBigInteger('equipo_id')->after('id')->nullable();
            $table->foreign('equipo_id')->references('id')->on('equipos');

            $table->unsignedBigInteger('torneo_id')->after('equipo_id')->nullable();
            $table->foreign('torneo_id')->references('id')->on('torneos');

            $table->unsignedBigInteger('user_id')->after('status')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipo_pagos', function (Blueprint $table) {
            //
            $table->dropColumn(['user_id', 'equipo_id', 'torneo_id']);
            $table->dropForeign(['user_id', 'equipo_id', 'torneo_id']);
        });
    }
}
