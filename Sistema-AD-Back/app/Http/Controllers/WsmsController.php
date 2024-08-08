<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TwilioService;

class WsmsController extends Controller
{
    protected $twilioService;

/**
 * Nombre de la función: `__construct`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Constructor para inicializar el servicio de Twilio. Este servicio es utilizado para enviar mensajes SMS y
 * mensajes de WhatsApp.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial del constructor.
 */
    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }
/**
 * Nombre de la función: `sendSms`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para enviar un SMS utilizando el servicio de Twilio. Obtiene el número de destino y el mensaje del
 * request, y utiliza el servicio de Twilio para enviar el SMS. Devuelve el SID del mensaje como respuesta.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function sendSms(Request $request)
    {
        $to = $request->input('to');
        $message = $request->input('message');

        $sid = $this->twilioService->sendSms($to, $message);

        return response()->json(['sid' => $sid]);
    }

/**
 * Nombre de la función: `sendWhatsAppMessage`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para enviar un mensaje de WhatsApp utilizando el servicio de Twilio. Obtiene el número de destino y
 * el mensaje del request, y utiliza el servicio de Twilio para enviar el mensaje de WhatsApp. Devuelve el SID del mensaje
 * como respuesta.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function sendWhatsAppMessage(Request $request)
    {
        $to = $request->input('to');
        $message = $request->input('message');

        $sid = $this->twilioService->sendWhatsAppMessage($to, $message);

        return response()->json(['sid' => $sid]);
    }

    
}
