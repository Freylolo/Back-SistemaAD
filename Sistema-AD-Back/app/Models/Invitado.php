<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    use HasFactory;

    protected $table = 'invitados'; // Asegúrate de que el nombre de la tabla sea correcto
    protected $primaryKey = 'id_invitado'; // Asegúrate de que el nombre de la columna primaria sea correcto
    public $timestamps = true; // Si estás utilizando timestamps

    protected $fillable = [
        'evento_id',
        'control_acceso_id',
        'nombres',
        'apellidos',
        'cedula',
        'placa',
        'observaciones',
        'estado',
    ];

   // Definir las relaciones con otros modelos
   public function evento()
   {
       return $this->belongsTo(Evento::class, 'evento_id');
   }

   public function controlAcceso()
   {
       return $this->belongsTo(ControlAcceso::class, 'control_acceso_id');
   }
}