<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTorneosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('torneos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->nullable();
            $table->string('fecha_inicio')->nullable();
            $table->string('premio')->nullable();
            $table->integer('nro_equipos')->nullable();
            $table->string('direccion')->nullable();
            $table->string('lugar')->nullable();
            $table->integer('duracion')->nullable();
            $table->integer('descanso')->nullable();
            $table->string('hora_inicio')->nullable();
            $table->string('status')->default('pendiente')->nullable();
            $table->boolean('activo')->default(0);

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
        Schema::dropIfExists('torneos');
    }
}
