<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\AdministracionController;
use App\Http\Controllers\Api\AvancesController;

Route::get('/user', function (Request $request) { return $request->user(); })->middleware('auth:sanctum');

// USERS MODULE
Route::middleware('auth:api')->get('/inventario',                   [InventarioController::class, 'index']);
Route::middleware('auth:api')->post('/inventario/{espacio}/new',    [InventarioController::class, 'set']);
Route::middleware('auth:api')->post('/inventario/{espacio}/date',   [InventarioController::class, 'setDate']);
Route::middleware('auth:api')->post('/inventario/{id}/datafile',    [InventarioController::class, 'setFileData']);
Route::middleware('auth:api')->post('/inventario/report',           [InventarioController::class, 'getDataAdvance']);
Route::get('/inventario/report',                                    [InventarioController::class, 'downloadReport']);

// ADMIN MODULE
Route::middleware('auth:api')->get('/admin/{id}',                   [AdministracionController::class, 'index']);
Route::middleware('auth:api')->post('/admin/biblioteca/set',        [AdministracionController::class, 'createJob']);
Route::middleware('auth:api')->post('/admin/{espacio}/data',        [AdministracionController::class, 'getData']);
Route::middleware('auth:api')->post('/admin/{espacio}/dataadvance', [AdministracionController::class, 'getDataAdvance']);
Route::middleware('auth:api')->put('/admin/{espacio}/posinventario', [AdministracionController::class, 'updatePosInventario']);


//AVANCES
Route::middleware('auth:api')->get('/avances/{espacio}/info',               [AvancesController::class, 'getInfo']);
Route::get('/avances/{espacio}/inventareados',      [AvancesController::class, 'getInventariados']);
Route::get('/avances/{espacio}/no-inventareados',   [AvancesController::class, 'getNoInventariados']);

Route::middleware('auth:api')->get('/avances/{id}/tree',                    [AvancesController::class, 'getTreeTemplate']);
Route::middleware('auth:api')->get('/avances/{id}/tree/clasificacion',      [AvancesController::class, 'getClasificacionData']);