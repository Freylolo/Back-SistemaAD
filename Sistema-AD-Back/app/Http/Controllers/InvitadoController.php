<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\ControlAcceso;
use App\Models\Invitado;
use App\Http\Controllers\EventoController;

class InvitadoController extends Controller
{
    public function store(Request $request)
{
    // Validar los datos de entrada
    $data = $request->validate([
        'evento_id' => 'required|exists:eventos,id_evento',
        'fecha_evento' => 'nullable|date',
        'hora_evento' => 'nullable|string',
        'invitados' => 'required|array',
        'invitados.*.nombres' => 'nullable|string|max:255',
        'invitados.*.apellidos' => 'nullable|string|max:255',
        'invitados.*.cedula' => 'nullable|string|max:20',
        'invitados.*.placa' => 'nullable|string|max:10',
        'invitados.*.observaciones' => 'nullable|string',
    ]);

    // Asociar el evento a los invitados
    foreach ($data['invitados'] as $invitado) {
        Invitado::create([
            'evento_id' => $data['evento_id'], // Asociar el evento a los invitados
            'fecha_evento' => $data['fecha_evento'],
            'hora_evento' => $data['hora_evento'],
            'nombres' => $invitado['nombres'],
            'apellidos' => $invitado['apellidos'],
            'cedula' => $invitado['cedula'],
            'placa' => $invitado['placa'] ?? null,
            'observaciones' => $invitado['observaciones'] ?? null,
        ]);
    }

    return response()->json(['message' => 'Datos guardados exitosamente'], 201);
}


public function getInvitadosByEvento($evento_id)
{
    // Verificar que el evento exista
    $evento = Evento::find($evento_id);

    if (!$evento) {
        return response()->json(['message' => 'Evento no encontrado'], 404);
    }

    // Obtener los invitados asociados al evento
    $invitados = Invitado::where('evento_id', $evento_id)->get();

    // Actualizar el estado de los invitados basado en ControlAcceso
    foreach ($invitados as $invitado) {
        $existeEnControlAcceso = ControlAcceso::where('cedula', $invitado->cedula)
            ->where('placas', $invitado->placa)
            ->where('nombre', $invitado->nombres)
            ->where('apellidos', $invitado->apellidos)
            ->whereDate('fecha_ingreso', $invitado->fecha_evento)
            ->exists();

        $estado = $existeEnControlAcceso ? 'Ingresado' : 'Sin ingreso';
        $invitado->update(['estado' => $estado]);
    }

    return response()->json($invitados, 200);
}

}

