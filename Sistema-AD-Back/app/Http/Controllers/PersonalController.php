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
        $personal = Personal::with('usuario')->get(); 
        return response()->json($personal);
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
            'cedula' => 'required|string|max:20|unique:personal,cedula',
            'sexo' => 'required|string|in:Masculino,Femenino',
            'perfil' => 'required|string|in:Seguridad,Administracion',
            'observaciones' => 'nullable|string',
            'celular' => 'required|string|max:20',
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
            return response()->json($personal->load('usuario'), 201); // Cargar la relación de usuario
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el personal', 'details' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $personal = Personal::with('usuario') // Asegúrate de que la relación esté correctamente definida
        ->where('id_personal', $id)
        ->firstOrFail();

        return response()->json($personal);
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
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'cedula' => 'sometimes|required|string|max:20|unique:personal,cedula,' . $id . ',id_personal',
            'sexo' => 'sometimes|required|string|max:10',
            'perfil' => 'sometimes|required|string|max:255',
            'celular' => 'sometimes|required|string|max:20',
            'correo_electronico' => 'sometimes|required|string|email|max:255',
            'observaciones' => 'nullable|string',
        ]);
    
        try {
            // Encontrar el personal por ID
            $personal = Personal::findOrFail($id);
    
            // Actualizar la información del personal
            $personal->update($validatedData);
    
            // Actualizar la información del usuario asociado
            $usuario = Usuario::findOrFail($validatedData['id_usuario']);
            $usuario->update([
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'correo_electronico' => $request->input('correo_electronico'),
            ]);
    
            // Retornar la respuesta con la información actualizada
            return response()->json([
                'personal' => $personal,
                'usuario' => $usuario
            ], 200);
    
        } catch (\Exception $e) {
            // Registrar el error y retornar una respuesta con el error
            \Log::error('Error al actualizar el personal: ' . $e->getMessage());
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
    
            // Elimina el usuario asociado
            if ($personal->id_usuario) {
                Personal::where('id_usuario', $personal->id_usuario)->delete();
            }
            $personal->delete();
    
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el personal', 'details' => $e->getMessage()], 500);
        }
    }

/**
 * Nombre de la función: `checkCedulaPersonal`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para verificar si un número de cédula ya existe en la base de datos para la entidad `Personal`.
 * Devuelve un JSON indicando si la cédula existe o no.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function checkCedulaPersonal($cedula)
    {
        $exists = Personal::where('cedula', $cedula)->exists();
        return response()->json(['exists' => $exists]);
    }

/**
 * Nombre de la función: `checkCorreoPersonal`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para verificar si un correo electrónico ya existe en la base de datos para la entidad `Personal`.
 * Devuelve un JSON indicando si el correo electrónico existe o no.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function checkCorreoPersonal($correo_electronico)
    {
        $exists = Personal::where('correo_electronico', $correo_electronico)->exists();
        return response()->json(['exists' => $exists]);
    }

/**
 * Nombre de la función: `checkCelular`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para verificar si un número de celular ya existe en la base de datos para la entidad `Personal`.
 * Devuelve un JSON indicando si el número de celular existe o no.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function checkCelular($celular)
    {
        $exists = Personal::where('celular', $celular)->exists();
        return response()->json(['exists' => $exists]);
    }
}