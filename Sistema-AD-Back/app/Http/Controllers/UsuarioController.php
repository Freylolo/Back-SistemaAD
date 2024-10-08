<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Residente;
use App\Models\Personal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Usuario::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'perfil' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:usuarios,username',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:usuarios,correo_electronico',
            'contrasena' => 'required|string|min:8',
            'rol' => 'required|string|max:50',
        ]);
    
        $data = $request->all();
        $data['contrasena'] = Hash::make($data['contrasena']);
        $usuario = Usuario::create($data);
        return response()->json($usuario, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Usuario::where('id_usuario', $id)->firstOrFail();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_usuario)
    {
        $request->validate([
            'perfil' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:usuarios,username,' . $id_usuario . ',id_usuario',
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'correo_electronico' => 'sometimes|required|email|unique:usuarios,correo_electronico,' . $id_usuario . ',id_usuario',
            'contrasena' => 'sometimes|required|string|min:8',
            'rol' => 'sometimes|required|string|max:50',
        ]);

        $usuario = Usuario::where('id_usuario', $id_usuario)->firstOrFail();

        $data = $request->all();
        if (isset($data['contrasena'])) {
            $data['contrasena'] = Hash::make($data['contrasena']);
        } else {
            unset($data['contrasena']);
        }

        try {
            $usuario->update($data);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar el usuario:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al actualizar el usuario'], 500);
        }

        return response()->json($usuario, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Encuentra el usuario
            $usuario = Usuario::findOrFail($id);
    
            // Encuentra el residente asociado (si existe) y elimínalo
            $residente = Residente::where('id_usuario', $id)->first();
            if ($residente) {
                $residente->delete();
            }
    
            // Encuentra el personal asociado (si existe) y elimínalo
            $personal = Personal::where('id_usuario', $id)->first();
            if ($personal) {
                $personal->delete();
            }
    
            // Elimina el usuario
            $usuario->delete();
    
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el usuario', 'details' => $e->getMessage()], 500);
        }
    }    
/**
 * Nombre de la función: `usuariosSeguridad`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para obtener todos los usuarios con perfil y rol de "Seguridad". Si no se encuentran usuarios,
 * devuelve un mensaje de error con un código de estado 404.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function usuariosSeguridad()
    {
        $usuariosSeguridad = Usuario::where('perfil', 'Seguridad')
                                    ->where('rol', 'Seguridad')
                                    ->get();

        if ($usuariosSeguridad->isEmpty()) {
            return response()->json(['message' => 'No se encontraron usuarios con perfil y rol de seguridad.'], 404);
        }

        return response()->json($usuariosSeguridad);
    }

/**
 * Nombre de la función: `checkUsernameUsuarios`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para verificar si un nombre de usuario ya existe en la base de datos para la entidad `Usuario`.
 * Devuelve un JSON indicando si el nombre de usuario existe o no.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function checkUsernameUsuarios($username)
    {
        $exists = Usuario::where('username', $username)->exists();
        return response()->json(['exists' => $exists]);
    }

/**
 * Nombre de la función: `checkCorreoUsuarios`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para verificar si un correo electrónico ya existe en la base de datos para la entidad `Usuario`.
 * Devuelve un JSON indicando si el correo electrónico existe o no.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function checkCorreoUsuarios($correo_electronico)
    {
        $exists = Usuario::where('correo_electronico', $correo_electronico)->exists();
        return response()->json(['exists' => $exists]);
    }

/**
 * Nombre de la función: `getUserIdByEmail`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para obtener el ID de un usuario basado en su correo electrónico. Si el usuario no se encuentra,
 * devuelve un mensaje de error con un código de estado 404.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */    
    public function getUserIdByEmail(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
        ]);

        $usuario = Usuario::where('correo_electronico', $request->correo_electronico)->first();

        if ($usuario) {
            return response()->json(['id_usuario' => $usuario->id_usuario]);
        } else {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

/**
 * Nombre de la función: `getUserIdByUsername`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para obtener el ID de un usuario basado en su nombre de usuario. Si el usuario no se encuentra,
 * devuelve un mensaje de error con un código de estado 404.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function getUserIdByUsername($username)
    {
        $usuario = Usuario::where('username', $username)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json(['id_usuario' => $usuario->id_usuario], 200);
    }

/**
 * Nombre de la función: `requestPasswordReset`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para solicitar el restablecimiento de la contraseña de un usuario. Valida el correo electrónico,
 * genera un token de restablecimiento y envía un correo con el enlace de restablecimiento. Registra el token en la base de datos.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function requestPasswordReset(Request $request)
{
    $request->validate([
        'correo' => 'required|email'
    ]);

    $user = Usuario::where('correo_electronico', $request->correo)->first();

    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    $token = Str::random(60);
    $user->update(['password_reset_token' => $token]);

    // Construir el enlace de restablecimiento usando la variable de entorno FRONTEND_URL
    $resetLink =('https://sistema-camino-real.vercel.app/') . '/reset-password?token=' . $token;
    \Log::info('FRONTEND_URL:', ['url' => env('FRONTEND_URL')]);
    \Log::info('Reset Link:', ['link' => $resetLink]);

    // Enviar el correo
    Mail::to($request->correo)->send(new PasswordResetMail($resetLink, $user->nombre));

    return response()->json(['message' => 'Se ha enviado un enlace para restablecer la contraseña.']);

}

/**
 * Nombre de la función: `resetPassword`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para restablecer la contraseña de un usuario utilizando un token de restablecimiento. Valida el token y
 * la nueva contraseña, luego actualiza la contraseña del usuario en la base de datos y elimina el token.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        $usuario = Usuario::where('password_reset_token', $request->token)->first();

        if (!$usuario) {
            \Log::info('Token inválido o usuario no encontrado:', ['token' => $request->token]);
            return response()->json(['error' => 'Token inválido'], 400);
        }

        $usuario->update([
            'contrasena' => Hash::make($request->new_password),
            'password_reset_token' => null
        ]);

        \Log::info('Contraseña restablecida exitosamente:', ['correo' => $usuario->correo_electronico]);

        return response()->json(['message' => 'Contraseña restablecida exitosamente']);
    }
}
