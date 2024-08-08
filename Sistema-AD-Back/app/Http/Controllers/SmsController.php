<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class SmsController extends Controller
{
    protected $twilio;

/**
 * Nombre de la función: `__construct`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Constructor para inicializar el servicio de Twilio.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial del constructor.
 */
    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }
    
/**
 * Nombre de la función: `sendSms`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para enviar un SMS utilizando el servicio de Twilio. Valida el número de teléfono y el mensaje,
 * luego utiliza el servicio de Twilio para enviar el SMS. Devuelve el SID del mensaje como respuesta.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function sendSms(Request $request)
    {
        $request->validate([
            'to' => 'required|regex:/^\+\d{10,15}$/',
            'message' => 'required|string'
        ]);

        $messageSid = $this->twilio->sendSms($request->to, $request->message);

        return response()->json(['sid' => $messageSid]);
    }
}
