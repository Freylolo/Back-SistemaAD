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
        Schema::table('residentes', function (Blueprint $table) {
            // Elimina la clave foránea si existe
            $table->dropForeign(['id_usuario']);
            
            // Agrega la clave foránea con ON DELETE CASCADE
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('residentes', function (Blueprint $table) {
            // Elimina la clave foránea si existe
            $table->dropForeign(['id_usuario']);
            
            // Reagrega la clave foránea sin ON DELETE CASCADE si es necesario
            $table->foreign('id_usuario')
                  ->references('id_usuario')
                  ->on('usuarios');
        });
    }
};
