<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bibliotecas;
use App\Models\Master;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view(
            'index.lectura',
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set($id, Request $request)
    {
        $cbarras = $request->cbarras;
        $date = new \DateTime();
        $library = Bibliotecas::where("id", $id)->first();
        
        $username = $request->user()->username;
        try {
            $record = DB::table($library['Tabla'])->where('C_Barras', $cbarras)->first();
        } catch (\Throwable $th) {
            return 0;
        }
        if(!$record){
            $findInAnotherLibrary = Master::where('C_Barras', $cbarras)->first();
            if ($findInAnotherLibrary) {
                $originalLibrary = Bibliotecas::where("Nombre", $findInAnotherLibrary->Biblioteca)->first();
                $dataRecord = [
                    'InserciónEstado' => 0,
                    'Inserción' => "Fallido, registro de otra biblioteca",
                    'C_Barras' => $cbarras,
                    'Biblioteca_L' => $library['Nombre'], //lectora
                    'Biblioteca_O' => $originalLibrary->Biblioteca,
                    'Fecha' => $date->format('Y-m-d'),
                    'Situacion' => 'Encontrado en otra biblioteca',
                    'Usuario' => $username,
                ];
                DB::table('anexos')
                    ->insert($dataRecord);
                $findInAnotherLibrary;
                return 2;
            } else {
                $dataRecord = [
                    'InserciónEstado' => 0,
                    'Inserción' => "Fallido, código de barras no encontrado",
                    'C_Barras' => $cbarras,
                    'Biblioteca_L' =>  NULL,
                    'Biblioteca_O' => NULL,
                    'Fecha' => $date->format('Y-m-d'),
                    'Situacion' => 'No encontrado',
                    'Usuario' => $username,
                ];
                DB::table('anexos')
                    ->insert($dataRecord);
                return 3;
            };
        } else if ($record->Situacion == 'Normal') {
            $update = DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Situacion' => 'Normal',
                'Estado' => 'I',
                'Usuario' => $username,
                'Fecha' => $date->format('Y-m-d'),
            ]);
            $dataRecord = [
                'InserciónEstado' => 1,
                'Inserción' => "Inventareado exitosamente",
                'C_Barras' => $record->C_Barras,
                'Situacion' => $record->Situacion,
                'Situacion' => $record->Situacion,
                'Biblioteca_L' =>  NULL,
                'Biblioteca_O' => NULL,
                'Estado' => 'I',
                'Usuario' => $username,
                'Fecha' => $date->format('Y-m-d'),
            ];
            return $dataRecord;
        } else if ($record->Situacion != 'Normal') {
            $dataRecord = [
                'InserciónEstado' => 0,
                'Inserción' => "Fallido, Estado distinto a normal",
                'C_Barras' => $record->C_Barras,
                'Situacion' => $record->Situacion,
                'Situacion' => $record->Situacion,
                'Biblioteca_L' =>  NULL,
                'Biblioteca_O' => NULL,
                'Estado' => $record->Estado,
                'Usuario' => $username,
                'Fecha' => $date->format('Y-m-d'),
            ];
            return $dataRecord;
        }

    }
}
