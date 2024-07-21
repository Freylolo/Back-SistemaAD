<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Define los campos que se pueden asignar masivamente
    protected $fillable = [
        'username', 'contrasena', 
    ];

    // Ocultar atributos del modelo (como la contraseña) cuando se convierte a un array o JSON
    protected $hidden = [
        'contrasena', 
    ];
}