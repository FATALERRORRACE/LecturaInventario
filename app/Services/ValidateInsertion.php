<?php

namespace App\Services;

use App\Models\Bibliotecas;
use App\Models\Master;
use Illuminate\Support\Facades\DB;

class ValidateInsertion{

    /**
     * Bootstrap services.
     */
    public function set($id, $request){
        $cbarras = $request->cbarras;
        $date = new \DateTime();
        $library = Bibliotecas::where("id", $id)->first();
        
        $username = $request->user()->username;
        try {
            $record = DB::table($library['Tabla'])->where('C_Barras', $cbarras)->first();
        } catch (\Throwable $th) {}
        if(!isset($record)){
            $findInAnotherLibrary = Master::where('C_Barras', $cbarras)->first();
            if ($findInAnotherLibrary) {
                $originalLibrary = Bibliotecas::where("Nombre", $findInAnotherLibrary->Biblioteca)->first();
                $dataRecord = [
                    'C_Barras' => $cbarras,
                    'Biblioteca_L' => $library['Nombre'], //lectora
                    'Biblioteca_O' => $originalLibrary->Nombre,
                    'Fecha' => $date->format('Y-m-d'),
                    'Situacion' => 'Encontrado en otra biblioteca',
                    'Usuario' => $username,
                ];
                DB::table('anexos')
                    ->insert($dataRecord);
                $dataRecord['InsercionEstado'] = 0;
                $dataRecord['Insercion'] = "Fallido, registro de otra biblioteca";
                return $dataRecord;
            } else {
                $dataRecord = [
                    'C_Barras' => $cbarras,
                    'Biblioteca_L' =>  NULL,
                    'Biblioteca_O' => NULL,
                    'Fecha' => $date->format('Y-m-d'),
                    'Situacion' => 'No encontrado',
                    'Usuario' => $username,
                ];
                DB::table('anexos')
                    ->insert($dataRecord);

                $dataRecord['Biblioteca_L'] = 'No encontrado';
                $dataRecord['Biblioteca_O'] = 'No encontrado';
                $dataRecord['InsercionEstado'] = 0;
                $dataRecord['Insercion'] = "Fallido, código de barras no encontrado";
                return $dataRecord;
            };
        } else if ($record->Situacion == 'Normal') {
            DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Situacion' => 'Normal',
                'Estado' => 'I',
                'Usuario' => $username,
                'Fecha' => $date->format('Y-m-d'),
            ]);
            $dataRecord = [
                'InsercionEstado' => 1,
                'Insercion' => "Inventareado exitosamente",
                'C_Barras' => $record->C_Barras,
                'Situacion' => $record->Situacion,
                'Situacion' => $record->Situacion,
                'Biblioteca_L' =>  $library['Nombre'],
                'Biblioteca_O' => $library['Nombre'],
                'Estado' => 'I',
                'Usuario' => $username,
                'Fecha' => $date->format('Y-m-d'),
            ];
            return $dataRecord;
        } else if ($record->Situacion != 'Normal') {
            $dataRecord = [
                'InsercionEstado' => 0,
                'Insercion' => "Fallido, Estado distinto a normal",
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