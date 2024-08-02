<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;
use App\Models\Residente;


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
        \Log::info('Tipo de evento recibido:', ['tipo_evento' => $request->tipo_evento]);

        // Validación de datos
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_residente' => 'required|exists:residentes,id_residente',
            'nombre_evento' => 'required|string|max:255',
            'direccion_evento' => 'required|string|max:255',
            'cantidad_vehiculos' => 'required|integer',
            'cantidad_personas' => 'required|integer',
            'tipo_evento' => 'required|string|max:50',
            'fecha_hora' => 'required|date',
            'duracion_evento' => 'required|numeric|lte:5',
            'listado_evento' => 'nullable|file|mimes:pdf,docx,xlsx|max:2048',
            'observaciones' => 'nullable|string',
            'estado' => 'required|string|in:En proceso de aceptación,Aceptado,Denegado'
        ]);

        // Obtener la información del usuario
        $usuario = Usuario::findOrFail($request->id_usuario);

        // Si el tipo de evento no es "Hogar", verificar el número de eventos para el mismo día
        if ($request->tipo_evento !== 'Hogar') {
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
        }

        // Preparar datos para crear el evento
        $data = $request->except('listado_evento');
        $data['nombre'] = $usuario->nombre;
        $data['apellido'] = $usuario->apellido;

        // Obtener celular y cedula según el rol del usuario
        if ($usuario->rol == 'Residente') {
            $residente = Residente::where('id_usuario', $usuario->id_usuario)->first();
            $data['celular'] = $residente->celular;
            $data['cedula'] = $residente->cedula;
        } elseif ($usuario->rol == 'Administración') {
            $personal = Personal::where('id_usuario', $usuario->id_usuario)->first();
            $data['celular'] = $personal->celular;
            $data['cedula'] = $personal->cedula;
        } else {
            return response()->json(['error' => 'Rol de usuario no válido'], 422);
        }

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
            'id_usuario' => 'required|integer',
            'id_residente' => 'required|integer',
            'nombre_evento' => 'sometimes|required|string|max:255',
            'direccion_evento' => 'sometimes|required|string|max:255',
            'cantidad_vehiculos' => 'sometimes|required|integer',
            'cantidad_personas' => 'sometimes|required|integer',
            'tipo_evento' => 'sometimes|required|string|max:50',
            'fecha_hora' => 'sometimes|required|date',
            'duracion_evento' => 'sometimes|required|numeric',
            'listado_evento' => 'nullable|file|mimes:pdf,docx,xlsx|max:2048',
            'observaciones' => 'nullable|string',
            'estado' => 'required|string|in:En proceso de aceptación,Aceptado,Denegado'
        ]);

        $evento = Evento::findOrFail($id);
        $data = $request->except('listado_evento');

        // Obtener la información del usuario
        $usuario = Usuario::findOrFail($request->id_usuario);
        $data['nombre'] = $usuario->nombre;
        $data['apellido'] = $usuario->apellido;

        // Obtener celular y cedula según el rol del usuario
        if ($usuario->rol == 'Residente') {
            $residente = Residente::where('id_usuario', $usuario->id_usuario)->first();
            $data['celular'] = $residente->celular;
            $data['cedula'] = $residente->cedula;
        } elseif ($usuario->rol == 'Administración') {
            $personal = Personal::where('id_usuario', $usuario->id_usuario)->first();
            $data['celular'] = $personal->celular;
            $data['cedula'] = $personal->cedula;
        } else {
            return response()->json(['error' => 'Rol de usuario no válido'], 422);
        }

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

    public function getFileUrl($filename)
    {
        return asset('uploads/' . $filename);
    }

    public function downloadFile($filename)
    {
        $filename = urldecode($filename); // Decodifica el nombre del archivo
        $path = public_path('uploads/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['error' => 'Archivo no encontrado.'], 404);
        }

        return response()->download($path);
    }

    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:En proceso de aceptación,Aceptado,Denegado'
        ]);

        $evento = Evento::findOrFail($id);
        $evento->estado = $request->estado;
        $evento->save();

        return response()->json($evento, 200);
    }

    public function getResidenteByIdUsuario($id_usuario)
   {
    $residente = Residente::where('id_usuario', $id_usuario)->first();

    if (!$residente) {
        return response()->json(['message' => 'Residente no encontrado'], 404);
    }

    return response()->json($residente);
    }

}