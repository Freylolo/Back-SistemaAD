<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ControlAcceso
 * 
 * @property int $id_acceso
 * @property int $id_usuario
 * @property string $nombre
 * @property string $apellidos
 * @property string $cedula
 * @property string $sexo
 * @property string $placas
 * @property string $direccion
 * @property string $ingresante 
 * @property string $fecha_ingreso
 * @property string|null $fecha_salida
 * @property string|null $observaciones
 * @property string|null $username
 * 
 * @property Usuario $usuario
 *
 * @package App\Models
 */

class ControlAcceso extends Model
{
    use HasFactory;
    
    protected $table = 'control_acceso'; // Nombre correcto de la tabla
    protected $primaryKey = 'id_acceso'; // Clave primaria personalizada
    public $timestamps = true; // Si usas timestamps, ajusta según tu esquema

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
