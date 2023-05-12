<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipoPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipo_pagos', function (Blueprint $table) {
            $table->id();

            $table->double('registracion')->nullable();
            $table->double('adelanto')->nullable();
            $table->double('saldo')->nullable();
            $table->enum('status', ['pendiente', 'cancelado'])->default('pendiente')->nullable();

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
        Schema::dropIfExists('equipo_pagos');
    }
}
