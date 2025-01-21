<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bibliotecas;
use App\Models\Master;
use App\Services\ValidateInsertion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $library = Bibliotecas::where("id", $request->bbltc)->first();
        $date = new \DateTime();
        $dateFechaInventario = new \DateTime($library['Fecha_Inventario']);
        $dateFechaFinInventario = new \DateTime($library['Fecha_Fin_Inventario']);
        $dateAllowed = false;
        if ($date >= $dateFechaInventario && $date <= $dateFechaFinInventario)
            $dateAllowed = true;

        $tableExists = true;
        try {
            DB::table($library['Tabla'])->first();
        } catch (\Throwable $th) {
            $tableExists = false;
        }
        return view(
            'index.lectura',
            [
                'admin' => DB::table('usuariosadministradores')->where('username', Auth::user()->username)->first() ? 1 : 0,
                'tableExists' => $tableExists,
                'dateAllowed' => $dateAllowed,
                'posInventory' => (bool)$library['PosInventario'],
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setDate($id, Request $request){

        $fecha = explode(' - ', $request->fecha);
        $fechaInicio = new \DateTime($fecha[0]);
        $fechaFin = new \DateTime($fecha[1]);

        $libraryFind = Bibliotecas::where("id", $id)->first();
        $libraryUpdate = Bibliotecas::where("id", $id)->update([
            'Fecha_Inventario' => $fechaInicio->format('Y-m-d'),
            'Fecha_Fin_Inventario' => $fechaFin->format('Y-m-d'),
        ]);
        try {
            DB::table($libraryFind['Tabla'])->first();
        } catch (\Throwable $th) {
            return [
                'message' => 'La tabla de la Biblioteca no ha sido creada',
                'status' => 'fail',
            ];
        }

        return [
            'message' => $libraryUpdate ? "Fecha Actualizada" : "Error al actualizar",
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
        // $request->inventario = 1 // INVENTARIO;
        // $request->inventario = 2 // PRESTAMO;
        $estado = $request->inventario == 1 ? 'I' : 'P';
        $record = false;
        try {
            $record = DB::table($library['Tabla'])->where('C_Barras', $cbarras)->first();
        } catch (\Throwable $th) {}
        if (!$record) {
            $findInAnotherLibrary = Master::where('C_Barras', $cbarras)->first();
            $dataRecord = [];
            if ($findInAnotherLibrary) {
                $originalLibrary = Bibliotecas::where("Nombre", $findInAnotherLibrary->Biblioteca)->first();
                $dataRecord['Biblioteca_O'] = $originalLibrary->Nombre;
                $dataRecord['Situacion'] = $findInAnotherLibrary['Proceso'];
            } else {
                $dataRecord['Biblioteca_O'] = NULL;
                $dataRecord['Situacion'] = 'No encontrado';
            };

            $dataRecord['Fecha'] = $date;
            $dataRecord['C_Barras'] = $cbarras;
            $dataRecord['Biblioteca_L'] = $library['Nombre'];
            $dataRecord['Usuario'] = $username;
            DB::table('anexos')->insert($dataRecord);
            $dataRecord['InsercionEstado'] = 0;
            $dataRecord['Insercion'] = ($findInAnotherLibrary ? "Material de otra biblioteca" : "Código de barras no encontrado");
        } else {
            if($library['PosInventario'] == 1)
                $dataRecord = $this->posInventoryRecord($request, $record, $estado, $username, $date, $library);
            else
                $dataRecord = $this->noPosInventoryRecord($request, $record, $estado, $username, $date, $library);
        }
        return $dataRecord;
    }

    public function setFileData($id, Request $request){
        $date = new \DateTime();
        $dateF = $date->format('Y-m-d h:i:s');
        $validateInsertion = new ValidateInsertion;
        $library = Bibliotecas::where("id", $id)->first();
        $username = $request->user()->username;
        $tipoInventario = $request->inventario == 1 || $request->inventario == 'undefined' ? 'I' : 'P';
        if($library->PosInventario == 1 ){
            if($tipoInventario == 'P'){
                $tipoInventario = 'D';
            }
        };

        try {
            DB::table($library['Tabla'])->first();
        } catch (\Throwable $th) {
            throw new \Exception("Tabla de la bibloteca {$library['nombre']} no ha sido creada", 1);
        }
        $filePathName = $request->file('file')->getPathname();
        $handle = fopen($filePathName, "r");
        $filename = $request->file('file')->getClientOriginalName();
        $newfilename = $date->format('Ymdhsi') . $filename . ".csv";
        $newfileData[] = implode(
            ';',
            [
                'InsercionEstado',
                'Insercion',
                'C_Barras',
                'Situacion',
                'Biblioteca_L',
                'Biblioteca_O',
                'Estado',
                'Usuario',
                'Fecha'
            ]
        );
        if ($filePathName) {
            $temp = tmpfile();
            $count = 0;
            $inserted = 0;
            $failed = 0;
            $allRecords = DB::table($library['Tabla'])
                ->select('Id', 'C_Barras', 'Situacion', 'Estado')
                ->get()->keyBy('C_Barras')->toArray();

            while (($line = fgets($handle)) !== false) {
                $line = str_replace("\n", "", $line);
                $line = str_replace("\r", "", $line);
                $line = str_replace(" ", "", $line);
                $tmpData = $validateInsertion->set(
                    isset($allRecords[$line]) ? $allRecords[$line] : null,
                    $dateF,
                    $username,
                    $line,
                    $library,
                    (int)$request->categoria,
                    $tipoInventario
                );
                if ($tmpData['InsercionEstado'] == 1)
                    $inserted++;
                else
                    $failed++;

                $newfileData[] = implode(';', $tmpData);
            }
            fclose($handle);
            fseek($temp, 0);
        }
        DB::table($library['Tabla'])->where('Fecha', $dateF)
            ->update([
                'Comentario' => 'Origen:' . $filename,
                'Usuario' => $username,
            ]);
        file_put_contents("/tmp/" . $newfilename, implode(PHP_EOL, $newfileData));
        return [
            'filename' => $filename,
            'total' => (int)$failed + (int)$inserted,
            'inserted' => $inserted,
            'failed' => $failed,
            'date' => $date->format('Y-m-d h:s:i'),
            'summaryFile' => $newfilename,
        ];
    }
 
    public function downloadReport(Request $request){
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . basename("/tmp/{$request->name}") . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize("/tmp/{$request->name}"));
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        if(isset($_SERVER['WINDIR'])){
            readfile("c:/tmp/{$request->name}");    
        }else{
            readfile("/tmp/{$request->name}");
        }
        exit();
    }

    public function posInventoryRecord($request, $record, $estado, $username, $date, $library) {
        if($estado == 'P' && empty($record->Estado) || $estado == 'P' && $record->Estado == 'P' ){
            $estado = 'D';
            DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Estado' => $estado,
                'Usuario' => $username,
                'Fecha' => $date,
            ]);
        }
        if($estado == 'I' && empty($record->Estado)){
            $estado = 'E';
            DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Estado' => $estado,
                'Usuario' => $username,
                'Fecha' => $date,
            ]);
        }
        if($estado == 'I' && $record->Estado == 'I'){
            DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Usuario' => $username,
                'Fecha' => $date,
            ]); 
        }
        $situacionMatch = false;
        switch ((int)$request->categoria) {
            case 1:
                $situacion = 'Nivel Central';
                $situacionMatch = $record->Situacion == $situacion;
                break;
            case 2:
                $situacion = 'Normal o No Displonible';
                $situacionMatch = $record->Situacion == 'Normal' || $record->Situacion == 'No Displonible';
                break;
            case 3:
                $situacion = 'En catalogación';
                $situacionMatch = $record->Situacion == $situacion;
                break;
        }
        $dataRecord = [
            'clasificacion' => (int)$request->categoria,
            'InsercionEstado' => 1,
            'Insercion' => $situacionMatch ? 'Inventareado exitosamente' : "Inventareado - Alerta, Estado distinto a $situacion",
            'C_Barras' => $record->C_Barras,
            'Situacion' => $record->Situacion,
            'Biblioteca_L' =>  $library['Nombre'],
            'Biblioteca_O' => $library['Nombre'],
            'Estado' => $estado,
            'Usuario' => $username,
            'Fecha' => $date,
        ];
        return $dataRecord;
    }

    public function noPosInventoryRecord($request, $record, $estado, $username, $date, $library){
        $situacionMatch = false;
        switch ((int)$request->categoria) {
            case 1:
                $situacion = 'Nivel Central';
                $situacionMatch = $record->Situacion == $situacion;
                break;
            case 2:
                $situacion = 'Normal o No Displonible';
                $situacionMatch = $record->Situacion == 'Normal' || $record->Situacion == 'No Displonible';
                break;
            case 3:
                $situacion = 'En catalogación';
                $situacionMatch = $record->Situacion == $situacion;
                break;
        }
        DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Estado' => $estado,
                'Usuario' => $username,
                'Fecha' => $date,
            ]);
        DB::table($library['Tabla'])->where('id', $record->id)
            ->update([
                'Estado' => $estado,
                'Usuario' => $username,
                'Fecha' => $date,
            ]);

        $estadoInsercion = $estado == 'P' ? 'Prestado' : 'Inventareado' ;
        $dataRecord = [
            'clasificacion' => (int)$request->categoria,
            'InsercionEstado' => 1,
            'Insercion' => $situacionMatch ? 
                "$estadoInsercion exitosamente" : 
                "$estadoInsercion - Alerta, Estado distinto a $situacion",
            'C_Barras' => $record->C_Barras,
            'Situacion' => $record->Situacion,
            'Biblioteca_L' =>  $library['Nombre'],
            'Biblioteca_O' => $library['Nombre'],
            'Estado' => $estado,
            'Usuario' => $username,
            'Fecha' => $date,
        ];

        return $dataRecord;
    }
}