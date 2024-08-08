<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail as MailFacade; // Alias para la fachada Mail
use App\Mail\Mail as MailClass; // Alias para tu clase Mail

class QRCodeController extends Controller
{

/**
 * Nombre de la función: `enviarCorreo`
 * Autor: Freya López - Flopezl@ug.edu.ec
 * Versión: 1.0
 * Fecha: 2024-08-07
 * 
 * Resumen: Método para enviar un correo electrónico con un asunto y texto proporcionados. La función valida la solicitud y,
 * si es exitosa, envía el correo. Maneja excepciones y devuelve un mensaje de éxito o error según el resultado.
 * 
 * Cambios:
 * - Versión 1.0: Creación inicial de la función.
 */
    public function enviarCorreo(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'text' => 'required|string',
        ]);

        $email = $validated['email'];
        $subject = $validated['subject'];
        $text = $validated['text'];

        try {
            // Enviar el correo sin archivos adjuntos
            MailFacade::to($email)->send(new MailClass($subject, $text, []));
            return response()->json(['message' => 'Correo enviado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar el correo', 'error' => $e->getMessage()], 500);
        }
    }
}
