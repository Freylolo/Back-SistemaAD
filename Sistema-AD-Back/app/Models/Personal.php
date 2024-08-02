<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Personal
 * 
 * @property int $id_personal
 * @property int $id_usuario
 * @property string $cedula
 * @property string $sexo
 * @property string $perfil
 * @property string|null $observaciones
 * @property string $celular
 *
 * @package App\Models
 */
class Personal extends Model
{
    protected $table = 'personal';
    protected $primaryKey = 'id_personal';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'cedula',
        'sexo',
        'perfil',
        'observaciones',
        'celular',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
