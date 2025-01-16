<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ValidateInsertion{

    /**
     * Bootstrap services.
     */
    public function set($found, $date, $username, $cbarras, $library, $categoria, $tipoInventario )
    {
        if (!$found) {
            $dataRecord = [
                'InsercionEstado' => 0,
                'Insercion' => "Material No encontrado",
                'C_Barras' => $cbarras,
                'Situacion' => 'No encontrado',
                'Biblioteca_L' => '',
                'Biblioteca_O' => '',
                'Estado' => '',
                'Usuario' => $username,
                'Fecha' => $date,
            ];
        } else {

            if($library->PosInventario == 1 ){
                if($tipoInventario == 'P'){
                    $tipoInventario = 'D';
                }
                if($tipoInventario == 'I' && empty($found->Estado)){
                    $tipoInventario = 'E';
                }
                if($tipoInventario == 'I' && $found->Estado == 'I'){
                    return $dataRecord = [
                        'InsercionEstado' => 1,
                        'Insercion' => "Inventareado exitosamente",
                        'C_Barras' => $found->C_Barras,
                        'Situacion' => $found->Situacion,
                        'Biblioteca_L' =>  $library['Nombre'],
                        'Biblioteca_O' => $library['Nombre'],
                        'Estado' => 'I',
                        'Usuario' => $username,
                        'Fecha' => $date,
                    ];
                }
            };

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
            $dataRecord = [
                'InsercionEstado' => 1,
                'Insercion' => $estadoMatch ? "Inventareado exitosamente" : "Inventareado - Estado del material distinto a {$estado}",
                'C_Barras' => $found->C_Barras,
                'Situacion' => $found->Situacion,
                'Biblioteca_L' =>  $library['Nombre'],
                'Biblioteca_O' => $library['Nombre'],
                'Estado' => $tipoInventario,
                'Usuario' => $username,
                'Fecha' => $date,
            ];
            DB::table($library['Tabla'])->where('Id', $found->Id)
            ->update([
                'Fecha' => $date,
                'Estado' => $tipoInventario,
            ]);
        }
        return $dataRecord;
    }
}
