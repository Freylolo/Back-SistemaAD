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
 * @property int $id_residente
 * @property string $nombre_evento
 * @property string $direccion_evento
 * @property int $cantidad_vehiculos
 * @property int $cantidad_personas
 * @property string $tipo_evento
 * @property Carbon $fecha_hora
 * @property float $duracion_evento
 * @property string|null $listado_evento
 * @property string|null $observaciones
 * @property string $estado
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
		'cantidad_vehiculos' => 'int',
		'cantidad_personas' => 'int',
		'fecha_hora' => 'datetime',
		'duracion_evento' => 'float'
	];
 
	protected $fillable = [
        'id_usuario',
        'id_residente',
		'nombre_evento',
		'direccion_evento',
		'cantidad_vehiculos',
		'cantidad_personas',
		'tipo_evento',
		'fecha_hora',
		'duracion_evento',
		'listado_evento',
		'observaciones',
		'estado'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
	}

	public function invitados()
{
    return $this->hasMany(Invitado::class, 'evento_id', 'id_evento');
}
}
