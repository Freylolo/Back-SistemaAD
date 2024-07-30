<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Alicuota
 * 
 * @property int $id_alicuota
 * @property int $id_residente
 * @property Carbon $fecha
 * @property string $mes
 * @property float $monto_por_cobrar
 * 
 * @property Residente $residente
 *
 * @package App\Models
 */
class Alicuota extends Model
{
	protected $table = 'alicuotas';
	protected $primaryKey = 'id_alicuota';
	public $timestamps = false;

	protected $casts = [
		'id_residente' => 'int',
		'fecha' => 'datetime',
		'monto_por_cobrar' => 'float',
		'pagado' => 'boolean',
	];

	protected $fillable = [
		'id_residente',
		'fecha',
		'mes',
		'monto_por_cobrar',
		'pagado',
	];

	public function residente()
	{
		return $this->belongsTo(Residente::class, 'id_residente');
	}
}
