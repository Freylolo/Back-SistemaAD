<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function login(Request $request)
    {
    // Validar la solicitud
    $request->validate([
        'login' => 'required|string',
        'contrasena' => 'required|string',
    ]);

    // Consultar el usuario en la base de datos
    $user = Usuario::where('username', $request->login)
                   ->orWhere('correo_electronico', $request->login)
                   ->first();

    // Verificar la contraseña usando Hash
    if ($user && Hash::check($request->contrasena, $user->contrasena)) {
        // Autenticación exitosa
        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'username' => $user->username,
            'role' => $user->rol  
        ]);
    }
    // Autenticación fallida
    return response()->json([
        'success' => false,
        'message' => 'Nombre de usuario o contraseña incorrectos'
    ], 401);
  }

}
