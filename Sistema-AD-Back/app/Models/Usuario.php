<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * 
 * @property int $id_usuario
 * @property string $perfil
 * @property string $username
 * @property string $nombre
 * @property string $apellido
 * @property string $correo_electronico
 * @property string $contrasena
 * @property string $rol
 * @property string $password_reset_token
 * 
 * @property Collection|Evento[] $eventos
 *
 * @package App\Models
 */
class Usuario extends Model
{
	protected $table = 'usuarios';
	protected $primaryKey = 'id_usuario';
	public $timestamps = false;

	protected $fillable = [
		'perfil',
		'username',
		'nombre',
		'apellido',
		'correo_electronico',
		'contrasena',
		'rol',
		'password_reset_token'
		
	];

	public function eventos()
	{
		return $this->hasMany(Evento::class, 'id_usuario');
	}

}
