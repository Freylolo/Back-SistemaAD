<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitados', function (Blueprint $table) {
            $table->id('id_invitado');
            $table->unsignedInteger('evento_id'); // Relación con la tabla eventos
            $table->unsignedInteger('control_acceso_id')->nullable(); // Relación con la tabla control_acceso
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('cedula')->unique();
            $table->string('placa')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('estado')->default('Sin ingreso');
            $table->timestamps();

            // Definir las relaciones
            $table->foreign('evento_id')->references('id_evento')->on('eventos')->onDelete('cascade');
            $table->foreign('control_acceso_id')->references('id_acceso')->on('control_acceso')->onDelete('set null');
        });       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitados');
    }
};
