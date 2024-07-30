<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
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
    // Validación de los datos
    $request->validate([
        'perfil' => 'sometimes|required|string|max:255',
        'username' => 'sometimes|required|string|max:255|unique:usuarios,username,' . $id_usuario . ',id_usuario',
        'nombre' => 'sometimes|required|string|max:255',
        'apellido' => 'sometimes|required|string|max:255',
        'correo_electronico' => 'sometimes|required|email|unique:usuarios,correo_electronico,' . $id_usuario . ',id_usuario',
        'contrasena' => 'sometimes|required|string|min:8',
        'rol' => 'sometimes|required|string|max:50',
    ]);

    // Busca el usuario por ID
    $usuario = Usuario::where('id_usuario', $id_usuario)->firstOrFail();

    // Actualiza los datos
    $data = $request->all();
    if (isset($data['contrasena'])) {
        $data['contrasena'] = Hash::make($data['contrasena']);
    } else {
        unset($data['contrasena']);
    }

    // Guardar los cambios
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
        Usuario::destroy($id);
        return response()->json(null, 204);
    }

    public function usuariosSeguridad()
    {
    $usuariosSeguridad = Usuario::where('perfil', 'Seguridad')
                                ->where('rol', 'Seguridad')
                                ->get();

    // Mensaje de depuración
    if ($usuariosSeguridad->isEmpty()) {
        return response()->json(['message' => 'No se encontraron usuarios con perfil y rol de seguridad.'], 404);
    }

    return response()->json($usuariosSeguridad);
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
    // Validar la solicitud
    $request->validate([
        'correo' => 'required|email',
    ]);

    // Buscar el usuario por correo electrónico
    $usuario = Usuario::where('correo_electronico', $request->correo)->first();

    // Verificar si el usuario existe
    if (!$usuario) {
        \Log::info('Correo electrónico no encontrado:', ['correo' => $request->correo]);
        return response()->json(['error' => 'Correo electrónico no encontrado'], 404);
    }

    // Generar un token usando Str::random()
    $token = Str::random(60);

    // Guardar el token en el usuario
    $usuario->update(['password_reset_token' => $token]);

    // Crear el enlace de restablecimiento
    $resetLink = env('FRONTEND_URL') . "/reset-password?token={$token}";

    // Enviar correo electrónico
    \Mail::to($usuario->correo_electronico)
        ->send(new PasswordResetMail($resetLink, $usuario->nombre));

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
