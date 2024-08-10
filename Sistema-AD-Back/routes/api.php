<?php
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlicuotaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ControlAccesoController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\WsmsController;
use App\Http\Controllers\InvitadoController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Ruta para Login
Route::post('/login', [AuthController::class, 'login']);

// Rutas para UsuarioController
Route::get('/usuarios', [UsuarioController::class, 'index']);
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);
Route::post('/usuarios', [UsuarioController::class, 'store']);
Route::put('/usuarios/{id}', [UsuarioController::class, 'update']);
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy']);

// Rutas para PersonalController
Route::get('/personal', [PersonalController::class, 'index']);
Route::get('/personal/{id}', [PersonalController::class, 'show']);
Route::post('/personal', [PersonalController::class, 'store']);
Route::put('/personal/{id}', [PersonalController::class, 'update']);
Route::delete('/personal/{id}', [PersonalController::class, 'destroy']);

// Rutas para AlicuotaController
Route::get('/alicuotas', [AlicuotaController::class, 'index']);
Route::get('/alicuotas/{id}', [AlicuotaController::class, 'show']);
Route::post('/alicuotas', [AlicuotaController::class, 'store']);
Route::put('/alicuotas/{id}', [AlicuotaController::class, 'update']);
Route::delete('/alicuotas/{id}', [AlicuotaController::class, 'destroy']);
Route::get('/alicuotas/residente/{id_residente}', [AlicuotaController::class, 'getAlicuotasByIdResidente']);


// Ruta para obtener el total adeudado por un residente
Route::get('/alicuotas/total/{id_residente}', [AlicuotaController::class, 'getTotalAdeudado']);

// Ruta para marcar una alícuota como pagada
Route::put('/alicuotas/{id_alicuota}/marcar-pago', [AlicuotaController::class, 'marcarPago']);

// Rutas para EventoController
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/eventos/{id}', [EventoController::class, 'show']);
Route::post('/eventos', [EventoController::class, 'store']);
Route::put('/eventos/{id}', [EventoController::class, 'update']);
Route::delete('/eventos/{id}', [EventoController::class, 'destroy']);

// Rutas para ResidenteController
Route::get('/residentes', [ResidenteController::class, 'index']);
Route::get('/residentes/{id}', [ResidenteController::class, 'show']);
Route::get('/eventos/residente/{id_usuario}', [EventoController::class, 'getResidenteByIdUsuario']);
Route::post('/residentes', [ResidenteController::class, 'store']);
Route::put('/residentes/{id}', [ResidenteController::class, 'update']);
Route::delete('/residentes/{id}', [ResidenteController::class, 'destroy']);
Route::get('/residentes/placa/{placa}', [ResidenteController::class, 'getResidentePorPlaca']);


// Rutas para ControlAccesoController
Route::get('/control-acceso', [ControlAccesoController::class, 'index']);
Route::get('/control-acceso/{id}', [ControlAccesoController::class, 'show']);
Route::post('/control-acceso', [ControlAccesoController::class, 'store']);
Route::put('/control-acceso/{id}', [ControlAccesoController::class, 'update']);
Route::delete('/control-acceso/{id}', [ControlAccesoController::class, 'destroy']);

// Ruta para verificar la cedula residentes
Route::get('/residentes/check-cedula/{cedula}', [ResidenteController::class, 'checkCedula']);

// Ruta para verificar correo_electronico residentes
Route::get('/residentes/check-correo/{correo_electronico}', [ResidenteController::class, 'checkCorreo']);

// Ruta para verificar la cedula personal
Route::get('/personal/check-cedula-personal/{cedula}', [PersonalController::class, 'checkCedulaPersonal']);

// Ruta para verificar correo_electronico personal
Route::get('/personal/check-correo-personal/{correo_electronico}', [PersonalController::class, 'checkCorreoPersonal']);

Route::get('/personal/check-celular/{celular}', [PersonalController::class, 'checkCelular']);
Route::get('/residentes/check-celularR/{celular}', [ResidenteController::class, 'checkCelularR']);

// Ruta para verificar correo en usuarios:
Route::get('/usuarios/check-correo-usuarios/{correo_electronico}', [UsuarioController::class, 'checkCorreoUsuarios']);
Route::post('/usuarios/getUserIdByEmail', [UsuarioController::class, 'getUserIdByEmail']);
Route::get('/usuarios/check-username-usuarios/{username}', [UsuarioController::class, 'checkUsernameUsuarios']);


// Ruta para obtener el usuario seguridad
Route::get('/usuarios/seguridad', [UsuarioController::class, 'usuariosSeguridad']);

//rutas emails
Route::post('/enviar-correo', [QRCodeController::class, 'enviarCorreo']);

//ruta para descargar archivo evento
Route::get('/uploads/{filename}', [EventoController::class, 'downloadFile'])->name('downloadFile');

Route::post('/login', [AuthController::class, 'login']);

Route::patch('/eventos/{id}/estado', [EventoController::class, 'updateEstado']);

Route::get('/usuarios/username/{username}', [UsuarioController::class, 'getUserIdByUsername']);

Route::post('/usuarios/solicitar-restablecimiento', [UsuarioController::class, 'requestPasswordReset']);
Route::post('/usuarios/restablecer-contrasena', [UsuarioController::class, 'resetPassword']);

Route::post('/send-sms', [SmsController::class, 'sendSms']);
Route::post('/send-whatsapp', [WsmsController::class, 'sendWhatsAppMessage']);

Route::post('/invitados', [InvitadoController::class, 'store']);
Route::get('/eventos/{evento_id}/invitados', [InvitadoController::class, 'getInvitadosByEvento']);
