<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InventarioController;
use App\Http\Controllers\Api\AdministracionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// USERS MODULE
Route::middleware('auth:api')->get('/inventario',                   [InventarioController::class, 'index']);
Route::middleware('auth:api')->post('/inventario/{espacio}/new',    [InventarioController::class, 'set']);
Route::middleware('auth:api')->post('/inventario/{id}/datafile',    [InventarioController::class, 'setFileData']);
// ADMIN MODULE
Route::middleware('auth:api')->get('/admin/{id}',            [AdministracionController::class, 'index']);
Route::middleware('auth:api')->post('/admin/biblioteca/set', [AdministracionController::class, 'createJob']);
Route::middleware('auth:api')->post('/admin/{espacio}/data', [AdministracionController::class, 'getData']);
Route::middleware('auth:api')->get('/admin/data/{id}/xls',   [AdministracionController::class, 'createXls']);


