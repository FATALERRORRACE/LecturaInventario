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
        return view(
            'index.lectura',
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set($id, Request $request){
        $cbarras = $request->cbarras;
        $date = new \DateTime();
        $library = Bibliotecas::where("id", $id)->first();
        
        $username = $request->user()->username;
        try {
            $record = DB::table($library['Tabla'])->where('C_Barras', $cbarras)->first();
        } catch (\Throwable $th) {
            
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
                    'Fecha' => $date,
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
                'Fecha' => $date,
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
                'Fecha' => $date,
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
