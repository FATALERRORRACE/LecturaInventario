<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bibliotecas;
use App\Models\Master;
use App\Services\ValidateInsertion;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $library = Bibliotecas::where("id", $request->bbltc)->first();
        $tableExists = true;
        try {
            DB::table($library['Tabla'])->first();
        } catch (\Throwable $th) {
            $tableExists = false;
        }
        return view(
            'index.lectura',
            [
                'tableExists' => $tableExists
            ]
        );
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setDate($id, Request $request){
        $library = Bibliotecas::where("id", $id)->update([
            'Fecha_Inventario' => $request->fecha,
        ]);
    
        return [
            'message' => $library ? "Fecha Actualizada" : "Error al actualizar",
            'status' => 'ok',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set($id, Request $request){
        $cbarras = $request->cbarras;
        $date = new \DateTime();
        $date = $date->format("Y-m-d");
        $library = Bibliotecas::where("id", $id)->first();
        
        $username = $request->user()->username;
        try {
            $record = DB::table($library['Tabla'])->where('C_Barras', $cbarras)->first();
        } catch (\Throwable $th) {}
        $estado = false;
        switch ((int)$request->categoria) {
            case 1:
                $estado = $record->Situacion == 'Nivel Central';
                break;
            case 2:
                $estado = $record->Situacion == 'Normal' || $record->Situacion == 'No Displonible';
                break;
            case 3:
                $estado = $record->Situacion == 'En catalogación';
                break;
        }
        if(!isset($record)){
            $findInAnotherLibrary = Master::where('C_Barras', $cbarras)->first();
            if ($findInAnotherLibrary) {
                $originalLibrary = Bibliotecas::where("Nombre", $findInAnotherLibrary->Biblioteca)->first();
                $dataRecord = [ 
                    'C_Barras' => $cbarras,
                    'Biblioteca_L' => $library['Nombre'], //lectora
                    'Biblioteca_O' => $originalLibrary->Nombre,
                    'Fecha' => $date,
                    'Situacion' => $findInAnotherLibrary['Proceso'],
                    'Usuario' => $username,
                ];
                DB::table('anexos')
                    ->insert($dataRecord);
                $dataRecord['InsercionEstado'] = 0;
                $dataRecord['Insercion'] = "Material de otra biblioteca";
                return $dataRecord;
            } else {
                $dataRecord = [
                    'C_Barras' => $cbarras,
                    'Biblioteca_L' =>  NULL,
                    'Biblioteca_O' => NULL,
                    'Fecha' => $date,
                    'Situacion' => '',
                    'Usuario' => $username,
                ];
                DB::table('anexos')
                    ->insert($dataRecord);

                $dataRecord['Biblioteca_L'] = 'No encontrado';
                $dataRecord['Biblioteca_O'] = 'No encontrado';
                $dataRecord['InsercionEstado'] = 0;
                $dataRecord['Insercion'] = "Código de barras no encontrado";
                return $dataRecord;
            };
        } else if ($estado) {
            DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Situacion' => $record->Situacion,
                'Estado' => 'I',
                'Usuario' => $username,
                'Fecha' => $date,
            ]);
            $dataRecord = [
                'InsercionEstado' => 1,
                'Insercion' => "Inventareado exitosamente",
                'C_Barras' => $record->C_Barras,
                'Situacion' => $record->Situacion,
                'Biblioteca_L' =>  $library['Nombre'],
                'Biblioteca_O' => $library['Nombre'],
                'Estado' => 'I',
                'Usuario' => $username,
                'Fecha' => $date,
            ];
            return $dataRecord;
        } else if ($estado) {
            $dataRecord = [
                'InsercionEstado' => 0,
                'Insercion' => "Alerta, Estado distinto a $estado",
                'C_Barras' => $record->C_Barras,
                'Situacion' => $record->Situacion,
                'Biblioteca_L' =>  $library['Nombre'],
                'Biblioteca_O' => NULL,
                'Estado' => $record->Estado,
                'Usuario' => $username,
                'Fecha' => $date,
            ];
            return $dataRecord;
        }
    }

    public function setFileData($id, Request $request){
        $date = new \DateTime();
        $date = $date;
        $data = [];
        $validateInsertion = new ValidateInsertion;
        $library = Bibliotecas::where("id", $id)->first();
        $username = $request->user()->username;
        try {
            DB::table($library['Tabla'])->first();
        } catch (\Throwable $th) {
            throw new \Exception("Tabla de la bibloteca {$library['nombre']} no ha sido creada", 1);
        }
        try {
            $filePathName = $request->file('file')->getPathname();
            $handle = fopen($filePathName, "r");
            if ($filePathName) {
                while (($line = fgets($handle)) !== false) {
                    $line = str_replace("\n", "", $line);
                    $line = str_replace("\r", "", $line);
                    $line = str_replace(" ", "", $line);
                    
                    $data[] = $validateInsertion->set($library['Tabla'], $date, $username, $line, $library);
                }
                fclose($handle);
            }
        } catch (\Throwable $th) {}
        return (array)$data;
    }
}
