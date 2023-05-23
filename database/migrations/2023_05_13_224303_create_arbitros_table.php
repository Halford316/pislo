<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArbitrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arbitros', function (Blueprint $table) {
            $table->id();

            $table->string('ape_paterno');
            $table->string('ape_materno');
            $table->string('nombres');
            $table->date('fecha_nac');
            $table->enum('sexo', ['M', 'F']);
            $table->string('foto')->nullable();
            $table->double('telefono')->nullable();
            $table->string('status')->default('activo')->nullable();
            $table->boolean('activo')->default(1);

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
        Schema::dropIfExists('arbitros');
    }
}
