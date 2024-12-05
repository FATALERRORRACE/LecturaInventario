<?php

namespace App\Services;

use App\Models\Bibliotecas;
use App\Models\Master;
use Illuminate\Support\Facades\DB;

class ValidateInsertion{

    /**
     * Bootstrap services.
     */
    public function set($tabla, $date, $username, $cbarras, $library){

        $record = DB::table($tabla)->where('C_Barras', $cbarras)->first();
        if( !$record ){
            $dataRecord = [
                'C_Barras' => $cbarras,
                'Biblioteca_L' => '',
                'Biblioteca_O' => '',
                'Fecha' => $date->format('Y-m-d'),
                'Situacion' => 'No encontrado',
                'Usuario' => $username,
                'InsercionEstado' => 0,
                'Insercion' => "Fallido, No encontrado",
            ];
        }else if ($record->Situacion == 'Normal') {
            //dump('Situacion == Normal');
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
        } else if ($record->Situacion != 'Normal') {
            $dataRecord = [
                'InsercionEstado' => 0,
                'Insercion' => "Fallido, Estado distinto a normal",
                'C_Barras' => $record->C_Barras,
                'Situacion' => $record->Situacion,
                'Situacion' => $record->Situacion,
                'Biblioteca_L' =>  $library['Nombre'],
                'Biblioteca_O' => $library['Nombre'],
                'Estado' => $record->Estado,
                'Usuario' => $username,
                'Fecha' => $date->format('Y-m-d'),
            ];
            
        }
        return $dataRecord;
    }
}