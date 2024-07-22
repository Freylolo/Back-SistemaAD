<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToControlAccesoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('control_acceso', function (Blueprint $table) {
            $table->timestamps(); // Agrega created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('control_acceso', function (Blueprint $table) {
            $table->dropTimestamps(); // Elimina created_at y updated_at
        });
    }
}
