<?php

use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\SolicitudController;
use App\Http\Controllers\api\SolicitudAdjuntoController;
use App\Http\Controllers\api\ComentarioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::post('/login', [UserController::class, 'login']);
Route::get('/solicitud_adjunto/{adjunto}', [SolicitudAdjuntoController::class, 'show']);
Route::get('/solicitud/image/{path}', [SolicitudController::class, 'showImage']);
Route::get('/solicitud_adjunto/descargar/{solicitudAdjunto}', [SolicitudAdjuntoController::class, 'download']);
Route::get('/solicitud/descargarTodo/{solicitud}', [SolicitudAdjuntoController::class, 'downloadAll']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [UserController::class, 'logout']);
    Route::post('/solicitud_adjunto',  [SolicitudAdjuntoController::class, 'store']);
    Route::get('/prioridades',  [SolicitudController::class, 'prioridades']);
    Route::get('/proyectos',  [SolicitudController::class, 'proyectos']);
    Route::get('/proyecto',  [SolicitudController::class, 'proyecto']);
    Route::get('/proyecto/solicitudes', [SolicitudController::class, 'solicitudesPorProyecto']);
    Route::get('/estados', [SolicitudController::class, 'estados']);
    Route::apiResource('solicitud', SolicitudController::class);
    Route::apiResource('solicitud.comentario', ComentarioController::class);
    Route::get('/usuario/solicitud', [SolicitudController::class, 'solicitudesPorUsuario']);
    Route::delete('/solicitud_adjunto/{adjunto}', [SolicitudAdjuntoController::class, 'delete']);
    Route::get('/usuario/comentarios', [ComentarioController::class, 'comentariosNoLeidosPorUsuario']);
    Route::get('/usuario/nro_comentarios', [ComentarioController::class, 'nroComentariosPorUsuario']);
});



