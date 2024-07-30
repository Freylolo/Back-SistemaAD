<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Usuario;


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
        $validatedData = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:personal,cedula',
            'sexo' => 'required|string|in:Masculino,Femenino',
            'perfil' => 'required|string|in:Seguridad,Administracion',
            'observaciones' => 'nullable|string',
            'celular' => 'required|string|max:20',
            'correo_electronico' => 'sometimes|required|string|email|max:255|unique:personal,correo_electronico',
        ]);

        $user = Usuario::find($validatedData['id_usuario']);
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Formatear el número de celular
        $celular = $validatedData['celular'];
        if (preg_match('/^0\d{9}$/', $celular)) {
            $celular = '+593' . substr($celular, 1);
        } elseif (preg_match('/^\d{9}$/', $celular)) {
            $celular = '+593' . $celular;
        }

        // Verificar si el número de celular ya existe
        if (Personal::where('celular', $celular)->exists()) {
            return response()->json(['error' => 'El número de celular ya está registrado.'], 400);
        }

        try {
            $personal = Personal::create(array_merge($validatedData, ['celular' => $celular]));
            return response()->json($personal, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el personal', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $personal = Personal::findOrFail($id);
            return response()->json($personal, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Personal no encontrado', 'details' => $e->getMessage()], 404);
        }
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
    $validatedData = $request->validate([
        'id_usuario' => 'required|exists:usuarios,id_usuario',
        'nombre' => 'sometimes|required|string|max:50',
        'apellido' => 'sometimes|required|string|max:50',
        'cedula' => 'sometimes|required|string|max:10|unique:personal,cedula,' . $id . ',id_personal',
        'sexo' => 'sometimes|required|string|in:Masculino,Femenino',
        'perfil' => 'sometimes|required|string|in:Seguridad,Administracion',
        'observaciones' => 'nullable|string',
        'celular' => 'sometimes|required|string|max:20',
        'correo_electronico' => 'nullable|string|email|max:100|unique:personal,correo_electronico,' . $id . ',id_personal',
    ]);

    try {
        $personal = Personal::findOrFail($id);

        // Formatear el número de celular
        if (isset($validatedData['celular'])) {
            $celular = $validatedData['celular'];
            if (preg_match('/^0\d{9}$/', $celular)) {
                $celular = '+593' . substr($celular, 1);
            } elseif (preg_match('/^\d{9}$/', $celular)) {
                $celular = '+593' . $celular;
            }
            $validatedData['celular'] = $celular;

            // Verificar si el número de celular ya existe
            if (Personal::where('celular', $celular)->where('id_personal', '!=', $id)->exists()) {
                return response()->json(['error' => 'El número de celular ya está registrado.'], 400);
            }
        }

        $personal->update($validatedData);
        return response()->json($personal, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al actualizar el personal', 'details' => $e->getMessage()], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $personal = Personal::findOrFail($id);
            $personal->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el personal', 'details' => $e->getMessage()], 500);
        }
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