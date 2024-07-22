<?php
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AlicuotaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\PersonalController;


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
Route::post('/residentes', [ResidenteController::class, 'store']);
Route::put('/residentes/{id}', [ResidenteController::class, 'update']);
Route::delete('/residentes/{id}', [ResidenteController::class, 'destroy']);

// Ruta para verificar la cedula
Route::get('/residentes/check-cedula/{cedula}', [ResidenteController::class, 'checkCedula']);

// Ruta para verificar correo_electronico
Route::get('/residentes/check-correo/{correo_electronico}', [ResidenteController::class, 'checkCorreo']);

// Ruta para obtener el usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
