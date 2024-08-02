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

    public function checkUsernameUsuarios($username)
    {
        $exists = Usuario::where('username', $username)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkCorreoUsuarios($correo_electronico)
    {
        $exists = Usuario::where('correo_electronico', $correo_electronico)->exists();
        return response()->json(['exists' => $exists]);
    }

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

    public function getUserIdByUsername($username)
    {
        $usuario = Usuario::where('username', $username)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json(['id_usuario' => $usuario->id_usuario], 200);
    }

    public function requestPasswordReset(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
        ]);

        $usuario = Usuario::where('correo_electronico', $request->correo)->first();

        if (!$usuario) {
            \Log::info('Correo electrónico no encontrado:', ['correo' => $request->correo]);
            return response()->json(['error' => 'Correo electrónico no encontrado'], 404);
        }

        $token = Str::random(60);
        $usuario->update(['password_reset_token' => $token]);

        $resetLink = env('FRONTEND_URL') . "/reset-password?token={$token}";

        try {
            \Mail::to($usuario->correo_electronico)
                ->send(new PasswordResetMail($resetLink, $usuario->nombre));
        } catch (\Exception $e) {
            \Log::error('Error al enviar el correo de restablecimiento:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al enviar el correo de restablecimiento'], 500);
        }

        \Log::info('Enlace de restablecimiento enviado:', ['correo' => $usuario->correo_electronico, 'resetLink' => $resetLink]);

        return response()->json(['message' => 'Enlace de restablecimiento de contraseña enviado']);
    }

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
