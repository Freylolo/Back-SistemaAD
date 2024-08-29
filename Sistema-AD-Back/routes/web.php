<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\WsmsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Ruta para ver los documentos
Route::get('uploads/{filename}', function ($filename) {
    // Intenta obtener el archivo desde el disco 'public'
    $file = Storage::disk('public')->get('uploads/' . $filename);

    if (!$file) {
        abort(404, 'Archivo no encontrado.');
    }

    // Obtén el tipo MIME del archivo
    $type = Storage::disk('public')->mimeType('uploads/' . $filename);

    // Devuelve la respuesta con el archivo y el tipo MIME
    return response($file, 200)->header("Content-Type", $type);
});

Route::post('/send-sms', [SmsController::class, 'sendSms']);
Route::post('/send-whatsapp', [WsmsController::class, 'sendWhatsAppMessage']);
