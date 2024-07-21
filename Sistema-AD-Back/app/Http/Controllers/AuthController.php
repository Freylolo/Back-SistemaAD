<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'username' => 'required|string',
            'contrasena' => 'required|string', // Cambiar 'password' a 'contrasena'
        ]);

        // Consultar el usuario en la base de datos
        $user = Usuario::where('username', $request->username)->first();

        // Verificar la contraseña usando Hash
        if ($user && Hash::check($request->contrasena, $user->contrasena)) {
            // Autenticación exitosa
            return response()->json(['success' => true, 'message' => 'Inicio de sesión exitoso']);
        }

        // Autenticación fallida
        return response()->json(['success' => false, 'message' => 'Nombre de usuario o contraseña incorrectos'], 401);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
