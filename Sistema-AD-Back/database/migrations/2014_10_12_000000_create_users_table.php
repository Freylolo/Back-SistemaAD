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
        // Primero, crea las tablas que no tienen claves foráneas.
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email');
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            $table->primary(['email', 'token']);
        });

        // Luego, crea las tablas con claves foráneas que no tienen dependencias circulares.
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->enum('perfil', ['Seguridad', 'Administracion', 'Residente', 'Propietario'])->nullable();
            $table->string('username');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo_electronico');
            $table->string('contrasena');
            $table->string('password_reset_token')->nullable();
            $table->enum('rol', ['Administracion', 'Seguridad', 'Residente']);
            $table->timestamps();

            $table->unique('correo_electronico');
            $table->unique('username');
        });

        Schema::create('residentes', function (Blueprint $table) {
            $table->id('id_residente');
            $table->unsignedBigInteger('id_usuario');
            $table->string('cedula');
            $table->enum('sexo', ['Masculino', 'Femenino']);
            $table->enum('perfil', ['Residente', 'Propietario']);
            $table->string('direccion');
            $table->string('solar');
            $table->decimal('m2', 10, 2);
            $table->string('celular');
            $table->integer('cantidad_vehiculos');
            $table->string('vehiculo1_placa')->nullable();
            $table->text('vehiculo1_observaciones')->nullable();
            $table->string('vehiculo2_placa')->nullable();
            $table->text('vehiculo2_observaciones')->nullable();
            $table->string('vehiculo3_placa')->nullable();
            $table->text('vehiculo3_observaciones')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique('cedula');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });

        Schema::create('control_acceso', function (Blueprint $table) {
            $table->id('id_acceso');
            $table->unsignedBigInteger('id_usuario');
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('cedula', 10);
            $table->enum('sexo', ['M', 'F', 'Indefinido'])->default('Indefinido');
            $table->string('placas', 10)->nullable();
            $table->string('direccion');
            $table->enum('ingresante', ['Residente', 'Visitante', 'Delivery']);
            $table->dateTime('fecha_ingreso');
            $table->dateTime('fecha_salida')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('username')->nullable();
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });

        Schema::create('eventos', function (Blueprint $table) {
            $table->id('id_evento');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_residente');
            $table->string('nombre_evento');
            $table->string('direccion_evento');
            $table->integer('cantidad_vehiculos');
            $table->integer('cantidad_personas');
            $table->enum('tipo_evento', ['Evento social', 'Hogar', 'Cancha de futbol', 'Parque comunitario', 'Club Acuatico', 'Club Residencial']);
            $table->dateTime('fecha_hora');
            $table->decimal('duracion_evento', 3, 1);
            $table->string('listado_evento')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['En proceso de aceptación', 'Aceptado', 'Denegado'])->default('En proceso de aceptación');
            $table->timestamps();

            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_residente')->references('id_residente')->on('residentes')->onDelete('cascade');
        });

        Schema::create('invitados', function (Blueprint $table) {
            $table->id('id_invitado');
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('control_acceso_id')->nullable();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('cedula');
            $table->string('placa')->nullable();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['Sin ingreso', 'Ingresado', 'Salido'])->default('Sin ingreso');
            $table->timestamps();
            $table->date('fecha_evento')->nullable();
            $table->time('hora_evento')->nullable();

            $table->foreign('evento_id')->references('id_evento')->on('eventos')->onDelete('cascade');
            $table->foreign('control_acceso_id')->references('id_acceso')->on('control_acceso')->onDelete('set null');
        });

        Schema::create('personal', function (Blueprint $table) {
            $table->id('id_personal');
            $table->unsignedBigInteger('id_usuario');
            $table->string('cedula');
            $table->enum('sexo', ['Masculino', 'Femenino']);
            $table->enum('perfil', ['Seguridad', 'Administracion']);
            $table->text('observaciones')->nullable();
            $table->string('celular')->nullable();
            $table->timestamps();

            $table->unique('cedula');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });

        Schema::create('alicuotas', function (Blueprint $table) {
            $table->id('id_alicuota');
            $table->unsignedBigInteger('id_residente');
            $table->date('fecha');
            $table->enum('mes', ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']);
            $table->decimal('monto_por_cobrar', 10, 2);
            $table->tinyInteger('pagado')->default(0);
            $table->timestamps();

            $table->foreign('id_residente')->references('id_residente')->on('residentes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alicuotas');
        Schema::dropIfExists('control_acceso');
        Schema::dropIfExists('eventos');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('invitados');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('personal');
        Schema::dropIfExists('residentes');
        Schema::dropIfExists('usuarios');
    }
};
