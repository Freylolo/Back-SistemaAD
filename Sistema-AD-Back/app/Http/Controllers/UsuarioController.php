<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

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
        return Usuario::findOrFail($id);
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
    public function update(Request $request, string $id)
    {
        $request->validate([
            'perfil' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:usuarios,username,' . $id,
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'correo_electronico' => 'sometimes|required|email|unique:usuarios,correo_electronico,' . $id,
            'contrasena' => 'sometimes|required|string|min:8',
            'rol' => 'sometimes|required|string|max:50',
        ]);
    
        $usuario = Usuario::findOrFail($id);
        $data = $request->all();
        if (isset($data['contrasena'])) {
            $data['contrasena'] = Hash::make($data['contrasena']);
        } else {
            unset($data['contrasena']);
        }
        $usuario->update($data);
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
        $request->validate([
            'correo' => 'required|email'
        ]);
    
        $user = Usuario::where('correo_electronico', $request->correo)->first();
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        $token = Str::random(60);
        $user->update(['password_reset_token' => $token]);
    
        $resetLink = url('/reset-password?token=' . $token . '&email=' . urlencode($request->correo));
    
        // Enviar correo
        Mail::to($request->correo)->send(new PasswordResetMail($resetLink));
    
        return response()->json(['message' => 'Se ha enviado un enlace para restablecer la contraseña.']);
    }
    

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'new_password' => 'required|string|min:8'
        ]);

        $user = Usuario::where('correo_electronico', $request->email)
                       ->where('password_reset_token', $request->token)
                       ->first();

        if (!$user) {
            return response()->json(['error' => 'Token o correo electrónico inválidos'], 400);
        }

        $user->update([
            'contrasena' => Hash::make($request->new_password),
            'password_reset_token' => null
        ]);

        return response()->json(['message' => 'Contraseña restablecida exitosamente']);
    }

    public function showResetPasswordForm(Request $request)
    {
    $token = $request->query('token');
    return view('auth.reset_password', ['token' => $token]);
    }



}
