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
        
        $eventos = Evento::orderBy('fecha_hora', 'desc')->get();
         return response()->json($eventos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Log del tipo de evento recibido para depuración
    \Log::info('Tipo de evento recibido:', ['tipo_evento' => $request->tipo_evento]);

    // Si el tipo de evento es 'Hogar', omitir validaciones adicionales
    if (strtolower($request->tipo_evento) === 'hogar') {
        $data = $request->except('listado_evento');

        if ($request->hasFile('listado_evento')) {
            $file = $request->file('listado_evento');
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['listado_evento'] = $filename;
        } else {
            $data['listado_evento'] = null;
        }

        $evento = Evento::create($data);

        return response()->json($evento, 201);
    }

    // Realizar validaciones para otros tipos de evento
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
        'duracion_evento' => 'required|numeric|lte:5',
        'listado_evento' => 'nullable|file|mimes:pdf,docx,xlsx|max:2048',
        'observaciones' => 'nullable|string',
        'estado' => 'required|string|in:En proceso de aceptacion,Aceptado,Denegado'
    ]);

    // Obtener los eventos del mismo día
    $date = \Carbon\Carbon::parse($request->fecha_hora)->format('Y-m-d');
    $eventsToday = Evento::whereDate('fecha_hora', $date)->get();

    // Verificar el número de eventos para el mismo día
    if ($eventsToday->count() >= 2) {
        return response()->json(['error' => 'Ya se han registrado dos eventos para este día.'], 422);
    }

    // Verificar la superposición de horarios
    $startTime = \Carbon\Carbon::parse($request->fecha_hora);
    $endTime = $startTime->copy()->addHours($request->duracion_evento);

    foreach ($eventsToday as $event) {
        $eventStartTime = \Carbon\Carbon::parse($event->fecha_hora);
        $eventEndTime = $eventStartTime->copy()->addHours($event->duracion_evento);
        if ($startTime->lt($eventEndTime) && $endTime->gt($eventStartTime)) {
            return response()->json(['error' => 'El horario seleccionado se superpone con otro evento existente.'], 422);
        }
    }

    // Continúa con la creación del evento
    $data = $request->except('listado_evento');

    if ($request->hasFile('listado_evento')) {
        $file = $request->file('listado_evento');
        $filename = time() . '-' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        $data['listado_evento'] = $filename;
    } else {
        $data['listado_evento'] = null;
    }

    $evento = Evento::create($data);

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
            'estado' => 'required|string|in:En proceso de aceptacion,Aceptado,Denegado'
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
