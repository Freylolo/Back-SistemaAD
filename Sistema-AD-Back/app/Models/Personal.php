<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Personal
 * 
 * @property int $id_personal
 * @property string $nombre
 * @property string $apellido
 * @property string $cedula
 * @property string $sexo
 * @property string $perfil
 * @property string|null $observaciones
 *
 * @package App\Models
 */
class Personal extends Model
{
	protected $table = 'personal';
	protected $primaryKey = 'id_personal';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'apellido',
		'cedula',
		'sexo',
		'perfil',
		'observaciones',
		'celular',

	];
}
