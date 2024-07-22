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
        Schema::table('alicuotas', function (Blueprint $table) {
            $table->boolean('pagado')->default(false); // Campo para indicar si se ha pagado
        });
    }

    public function down()
    {
        Schema::table('alicuotas', function (Blueprint $table) {
            $table->dropColumn('pagado');
        });
    }
};
