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
        'invitados' => 'required|array',
        'invitados.*.nombres' => 'required|string|max:255',
        'invitados.*.apellidos' => 'required|string|max:255',
        'invitados.*.cedula' => 'required|string|max:20|unique:invitados,cedula',
        'invitados.*.placa' => 'nullable|string|max:10',
        'invitados.*.observaciones' => 'nullable|string',
    ]);

    // Asociar el evento a los invitados
    foreach ($data['invitados'] as $invitado) {
        Invitado::create([
            'evento_id' => $data['evento_id'], // Asociar el evento a los invitados
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

    return response()->json($invitados, 200);
}


}

