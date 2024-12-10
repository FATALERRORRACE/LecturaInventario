<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ValidateInsertion{

    /**
     * Bootstrap services.
     */
    public function set($tabla, $date, $username, $cbarras, $library, $categoria, $filename)
    {
        $record = DB::table($tabla)->where('C_Barras', $cbarras)->first();
        $found = [];
        foreach ($record as $key => $value) {
            if($value->C_Barras == $cbarras){
                $found = $value;
                unset($record[$key]);
                return;
            }
        }
        if (!$found) {
            $dataRecord = [
                'C_Barras' => $cbarras,
                'Biblioteca_L' => '',
                'Biblioteca_O' => '',
                'Fecha' => $date->format('Y-m-d'),
                'Situacion' => 'No encontrado',
                'Usuario' => $username,
                'InsercionEstado' => 0,
                'Insercion' => "Material No encontrado",
            ];
        } else {
            $estadoMatch = false;
            switch ((int)$categoria) {
                case 1:
                    $estado = 'Nivel Central';
                    $estadoMatch = $found->Situacion == $estado;
                    break;
                case 2:
                    $estado = 'Normal o No Disponible';
                    $estadoMatch = $found->Situacion == 'Normal' || $found->Situacion == 'No Displonible';
                    break;
                case 3:
                    $estado = 'En catalogación';
                    $estadoMatch = $found->Situacion == $estado;
                    break;
            }
            if ($estadoMatch) {
                DB::table($library['Tabla'])->where('id', $found->id)
                    ->update([
                        'Situacion' => 'Normal',
                        'Estado' => 'I',
                        'Comentario' => "Cargado por archivo: {$filename}, {$date->format('Y-m-d h:s:i')}",
                        'Usuario' => $username,
                        'Fecha' => $date->format('Y-m-d'),
                    ]);
                $dataRecord = [
                    'InsercionEstado' => 1,
                    'Insercion' => "Inventareado exitosamente",
                    'C_Barras' => $found->C_Barras,
                    'Situacion' => $found->Situacion,
                    'Situacion' => $found->Situacion,
                    'Biblioteca_L' =>  $library['Nombre'],
                    'Biblioteca_O' => $library['Nombre'],
                    'Estado' => 'I',
                    'Usuario' => $username,
                    'Fecha' => $date->format('Y-m-d'),
                ];
            } else if (!$estadoMatch) {
                $dataRecord = [
                    'InsercionEstado' => 0,
                    'Insercion' => "Estado del material distinto a $estado",
                    'C_Barras' => $found->C_Barras,
                    'Situacion' => $found->Situacion,
                    'Situacion' => $found->Situacion,
                    'Biblioteca_L' =>  $library['Nombre'],
                    'Biblioteca_O' => $library['Nombre'],
                    'Estado' => $found->Estado,
                    'Usuario' => $username,
                    'Fecha' => $date->format('Y-m-d'),
                ];
            }
        }
        return $dataRecord;
    }
}
