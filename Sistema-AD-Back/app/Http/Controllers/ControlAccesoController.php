<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControlAcceso;
use App\Models\Usuario;

class ControlAccesoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $controlAccesos = ControlAcceso::all();
        return response()->json($controlAccesos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Este método no se usa en API, puedes dejarlo vacío o eliminarlo
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Datos recibidos en el controlador:', $request->all());
    
        $data = $request->validate([
            'id_usuario' => 'required|integer',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => 'required|string|max:10',
            'sexo' => 'required|in:M,F,Indefinido',
            'placas' => 'nullable|string|max:10',
            'direccion' => 'required|string|max:255',
            'ingresante' => 'required|in:Residente,Visitante,Delivery',
            'fecha_ingreso' => 'required|date',
            'fecha_salida' => 'nullable|date',
            'observaciones' => 'nullable|string',
            'username' => 'nullable|string|max:50'
        ]);
    
        // Asegúrate de que el usuario con el id proporcionado exista
        $user = Usuario::find($data['id_usuario']);
    
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    
        // Crear el registro en la tabla control_acceso
        $controlAcceso = ControlAcceso::create($data);
    
        return response()->json($controlAcceso, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $controlAcceso = ControlAcceso::find($id);

        if (!$controlAcceso) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
    
        return response()->json($controlAcceso);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Este método no se usa en API, puedes dejarlo vacío o eliminarlo
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Buscar el registro de ControlAcceso por ID
        $controlAcceso = ControlAcceso::find($id);
    
        // Verificar si el registro existe
        if (!$controlAcceso) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
    
        // Validar los datos recibidos
        $validated = $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'cedula' => 'required|string|max:10',
            'sexo' => 'required|in:M,F,Indefinido',
            'placas' => 'nullable|string|max:10',
            'direccion' => 'required|string|max:255',
            'ingresante' => 'required|in:Residente,Visitante,Delivery',
            'fecha_ingreso' => 'required|date',
            'fecha_salida' => 'nullable|date',
            'observaciones' => 'nullable|string',
            'username' => 'required|string|max:50',
        ]);
    
        // Actualizar el registro con los datos validados
        $controlAcceso->update($validated);
    
        // Devolver la respuesta con el registro actualizado
        return response()->json($controlAcceso);
    }
    


    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_acceso)
    {
        try {
            $controlAcceso = ControlAcceso::find($id_acceso);

            if (!$controlAcceso) {
                return response()->json(['message' => 'Registro no encontrado'], 404);
            }

            $controlAcceso->delete();

            return response()->json(['message' => 'Registro eliminado con éxito']);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar el registro de control de acceso: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el registro'], 500);
        }
    }
}
