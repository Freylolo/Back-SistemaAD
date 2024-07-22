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

}
