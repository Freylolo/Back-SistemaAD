<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlAcceso extends Model
{
    use HasFactory;
    protected $table = 'control_acceso'; // Nombre correcto de la tabla

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellidos',
        'cedula',
        'sexo',
        'placas',
        'direccion',
        'ingresante',
        'fecha_ingreso',
        'fecha_salida',
        'observaciones',
        'username'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
