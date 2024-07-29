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
    $path = public_path('uploads/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return response($file, 200)->header("Content-Type", $type);
});

Route::post('/send-sms', [SmsController::class, 'sendSms']);
Route::post('/send-whatsapp', [WsmsController::class, 'sendWhatsAppMessage']);
