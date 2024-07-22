<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('control_acceso', function (Blueprint $table) {
            $table->string('username')->nullable()->after('observaciones'); // Agregar la columna 'username'
        });
    }

    public function down()
    {
        Schema::table('control_acceso', function (Blueprint $table) {
            $table->dropColumn('username'); // Eliminar la columna 'username'
        });
    }
};
