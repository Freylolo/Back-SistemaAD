<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Evento
 * 
 * @property int $id_evento
 * @property int $id_usuario
 * @property string $nombre
 * @property string $apellidos
 * @property string $celular
 * @property string $cedula
 * @property string $nombre_evento
 * @property string $direccion_evento
 * @property int $cantidad_vehiculos
 * @property int $cantidad_personas
 * @property string $tipo_evento
 * @property Carbon $fecha_hora
 * @property float $duracion_evento
 * @property string|null $listado_evento
 * @property string|null $observaciones
 * 
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Evento extends Model
{
	protected $table = 'eventos';
	protected $primaryKey = 'id_evento';
	public $timestamps = false;

	protected $casts = [
		'id_usuario' => 'int',
		'cantidad_vehiculos' => 'int',
		'cantidad_personas' => 'int',
		'fecha_hora' => 'datetime',
		'duracion_evento' => 'float'
	];

	protected $fillable = [
		'id_usuario',
		'nombre',
		'apellidos',
		'celular',
		'cedula',
		'nombre_evento',
		'direccion_evento',
		'cantidad_vehiculos',
		'cantidad_personas',
		'tipo_evento',
		'fecha_hora',
		'duracion_evento',
		'listado_evento',
		'observaciones'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_usuario');
	}
}
