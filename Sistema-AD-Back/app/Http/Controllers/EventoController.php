<?php

namespace App\Http\Controllers;
use App\Models\Evento;

use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Evento::all(), 200);
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
            'id_usuario' => 'required|integer|exists:usuarios,id_usuario',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'cedula' => 'required|string|max:20',
            'nombre_evento' => 'required|string|max:255',
            'direccion_evento' => 'required|string|max:255',
            'cantidad_vehiculos' => 'required|integer',
            'cantidad_personas' => 'required|integer',
            'tipo_evento' => 'required|string|max:50',
            'fecha_hora' => 'required|date',
            'duracion_evento' => 'required|numeric',
            'listado_evento' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $evento = Evento::create($request->all());
        return response()->json($evento, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $evento = Evento::findOrFail($id);
        return response()->json($evento, 200);
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
            'id_usuario' => 'sometimes|required|integer|exists:usuarios,id_usuario',
            'nombre' => 'sometimes|required|string|max:255',
            'apellidos' => 'sometimes|required|string|max:255',
            'celular' => 'sometimes|required|string|max:20',
            'cedula' => 'sometimes|required|string|max:20',
            'nombre_evento' => 'sometimes|required|string|max:255',
            'direccion_evento' => 'sometimes|required|string|max:255',
            'cantidad_vehiculos' => 'sometimes|required|integer',
            'cantidad_personas' => 'sometimes|required|integer',
            'tipo_evento' => 'sometimes|required|string|max:50',
            'fecha_hora' => 'sometimes|required|date',
            'duracion_evento' => 'sometimes|required|numeric',
            'listado_evento' => 'nullable|string',
            'observaciones' => 'nullable|string',
        ]);

        $evento = Evento::findOrFail($id);
        $evento->update($request->all());
        return response()->json($evento, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Evento::destroy($id);
        return response()->json(null, 204);
     
    }
}
