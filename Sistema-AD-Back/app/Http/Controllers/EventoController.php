<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
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
        'listado_evento' => 'nullable|file|mimes:pdf,docx,xlsx|max:2048', 
        'observaciones' => 'nullable|string',
    ]);

    $data = $request->except('listado_evento');

    if ($request->hasFile('listado_evento')) {
        $file = $request->file('listado_evento');
        $filename = time() . '-' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        $data['listado_evento'] = $filename;
    }

    $evento = Evento::create($data);
    // Verifica que el archivo ha sido guardado correctamente
    if ($data['listado_evento'] && !file_exists(public_path('uploads/' . $data['listado_evento']))) {
        return response()->json(['error' => 'Archivo no encontrado en el servidor.'], 500);
    }
    
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
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
            'listado_evento' => 'nullable|file|mimes:pdf,docx,xlsx|max:2048',
            'observaciones' => 'nullable|string',
        ]);

        $evento = Evento::findOrFail($id);
        $data = $request->except('listado_evento');

        if ($request->hasFile('listado_evento')) {
            // Eliminar archivo antiguo
            if ($evento->listado_evento && file_exists(public_path('uploads/' . $evento->listado_evento))) {
                unlink(public_path('uploads/' . $evento->listado_evento));
            }

            $file = $request->file('listado_evento');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['listado_evento'] = $filename;
        }

        $evento->update($data);

        return response()->json($evento, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $evento = Evento::findOrFail($id);

        // Eliminar archivo asociado
        if ($evento->listado_evento && file_exists(public_path('uploads/' . $evento->listado_evento))) {
            unlink(public_path('uploads/' . $evento->listado_evento));
        }

        $evento->delete();
        return response()->json(null, 204);
    }
}
