<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJuecesToFixturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fixtures', function (Blueprint $table) {
            //FK
            $table->unsignedBigInteger('juez_linea_1')->after('arbitro_id')->nullable();
            $table->foreign('juez_linea_1')->references('id')->on('juez_lineas');

            $table->unsignedBigInteger('juez_linea_2')->after('juez_linea_1')->nullable();
            $table->foreign('juez_linea_2')->references('id')->on('juez_lineas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fixtures', function (Blueprint $table) {
            //
            $table->dropColumn(['juez_linea_1', 'juez_linea_2']);
            $table->dropForeign(['juez_linea_1', 'juez_linea_2']);
        });
    }
}
