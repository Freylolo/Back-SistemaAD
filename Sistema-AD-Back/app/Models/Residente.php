<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Residente
 * 
 * @property int $id_usuario
 * @property int $id_residente
 * @property string $cedula
 * @property string $sexo
 * @property string $perfil
 * @property string $direccion
 * @property string $solar
 * @property string $m2
 * @property string $celular
 * @property int $cantidad_vehiculos
 * @property string|null $vehiculo1_placa
 * @property string|null $vehiculo1_observaciones
 * @property string|null $vehiculo2_placa
 * @property string|null $vehiculo2_observaciones
 * @property string|null $vehiculo3_placa
 * @property string|null $vehiculo3_observaciones
 * @property string|null $observaciones
 * 
 * @property Collection|Alicuota[] $alicuotas
 *
 * @package App\Models
 */
class Residente extends Model
{
	protected $table = 'residentes';
	protected $primaryKey = 'id_residente';
	public $timestamps = false;

	protected $casts = [
		'cantidad_vehiculos' => 'int'
	];

	protected $fillable = [
        'id_usuario',
        'cedula',
        'sexo',
        'perfil',
        'direccion',
        'solar',
        'm2',
        'celular',
        'cantidad_vehiculos',
        'vehiculo1_placa',
        'vehiculo1_observaciones',
        'vehiculo2_placa',
        'vehiculo2_observaciones',
        'vehiculo3_placa',
        'vehiculo3_observaciones',
        'observaciones'
    ];

	public function alicuotas()
	{
		return $this->hasMany(Alicuota::class, 'id_residente');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
	}
}
