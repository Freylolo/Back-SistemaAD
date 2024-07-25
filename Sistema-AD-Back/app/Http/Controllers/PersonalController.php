<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Personal::all(); 
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
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:personal,cedula',
            'sexo' => 'required|string|in:Masculino,Femenino',
            'perfil' => 'required|string|in:Seguridad,Administracion',
            'observaciones' => 'nullable|string',
            'celular' => 'required|string|max:20',
        ]);

        // Formatear el número de celular
        $celular = $request->input('celular');
            if (preg_match('/^0\d{9}$/', $celular)) {
        $celular = '+593' . substr($celular, 1);
            } elseif (preg_match('/^\d{9}$/', $celular)) {
         $celular = '+593' . $celular;
            }

      // Verificar si el número de celular ya existe
        if (Personal::where('celular', $celular)->exists()) {
        return response()->json(['error' => 'El número de celular ya está registrado.'], 400);
        }

        $personal = Personal::create($request->all());
        $personal->celular = $celular;
        return response()->json($personal, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $personal = Personal::findOrFail($id);
        return response()->json($personal, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'cedula' => 'sometimes|required|string|max:20|unique:personal,cedula,' . $id . ',id_personal',
            'sexo' => 'sometimes|required|string|in:Masculino,Femenino',
            'perfil' => 'sometimes|required|string|in:Seguridad,Administracion',
            'observaciones' => 'nullable|string',
            'celular' => 'required|string|max:20',
        ]);
 
        $personal = Personal::findOrFail($id);
        $personal->update($request->all());
        return response()->json($personal, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $personal = Personal::findOrFail($id);
        $personal->delete();
        return response()->json(null, 204);
    }

    public function checkCedulaPersonal($cedula)
    {
    $exists = Personal::where('cedula', $cedula)->exists();
    return response()->json(['exists' => $exists]);
    }

    public function checkCorreoPersonal($correo_electronico)
    {
        $exists = Personal::where('correo_electronico', $correo_electronico)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkCelular($celular)
     {
    $exists = Personal::where('celular', $celular)->exists();
    return response()->json(['exists' => $exists]);
     }
}
