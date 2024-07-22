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
    Schema::create('control_acceso', function (Blueprint $table) {
        $table->id('id_acceso');
        $table->unsignedBigInteger('id_usuario');
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('cedula');
        $table->enum('sexo', ['M', 'F']);
        $table->string('placas');
        $table->string('direccion');
        $table->enum('ingresante', ['Residente', 'Visitante', 'Delivery']);
        $table->dateTime('fecha_ingreso');
        $table->dateTime('fecha_salida')->nullable();
        $table->text('observaciones')->nullable();
        $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
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
        Schema::dropIfExists('control_accesos');
    }
};
