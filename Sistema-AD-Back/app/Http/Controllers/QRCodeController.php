<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail as MailFacade; // Alias para la fachada Mail
use App\Mail\Mail as MailClass; // Alias para tu clase Mail

class QRCodeController extends Controller
{
    /**
     * Enviar correo con el QR y PDF adjuntos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
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
