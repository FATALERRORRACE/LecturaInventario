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
    public function setDate($id, Request $request)
    {
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
    public function set($id, Request $request)
    {
        $cbarras = $request->cbarras;
        $date = new \DateTime();
        $date = $date->format("Y-m-d");
        $library = Bibliotecas::where("id", $id)->first();
        $username = $request->user()->username;

        try {
            $record = DB::table($library['Tabla'])->where('C_Barras', $cbarras)->first();
        } catch (\Throwable $th) {}
        if (!$record) {
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
            };
        } else {
            $estadoMatch = false;
            switch ((int)$request->categoria) {
                case 1:
                    $estado = 'Nivel Central';
                    $estadoMatch = $record->Situacion == $estado;
                    break;
                case 2:
                    $estado = 'Normal o No Displonible';
                    $estadoMatch = $record->Situacion == 'Normal' || $record->Situacion == 'No Displonible';
                    break;
                case 3:
                    $estado = 'En catalogación';
                    $estadoMatch = $record->Situacion == $estado;
                    break;
            }
            if ($estadoMatch) {
                DB::table($library['Tabla'])->where('id', $record->id)
                    ->update([
                        'Situacion' => $record->Situacion,
                        'Estado' => 'I',
                        'Usuario' => $username,
                        'Fecha' => $date,
                    ]);
                $dataRecord = [
                    'clasificacion' => (int)$request->categoria,
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
            } else if (!$estadoMatch) {
                $dataRecord = [
                    'clasificacion' => (int)$request->categoria,
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
            }
        }
        return $dataRecord;
    }

    public function setFileData($id, Request $request){
        $date = new \DateTime();

        $validateInsertion = new ValidateInsertion;
        $library = Bibliotecas::where("id", $id)->first();
        $username = $request->user()->username;

        try {
            DB::table($library['Tabla'])->first();
        } catch (\Throwable $th) {
            throw new \Exception("Tabla de la bibloteca {$library['nombre']} no ha sido creada", 1);
        }
        $record = (array)DB::table($library['Tabla'])->select('C_Barras', 'Estado', 'Situacion', 'id')->get()->toArray();
        $filePathName = $request->file('file')->getPathname();
        $handle = fopen($filePathName, "r");
        $filename = $request->file('file')->getClientOriginalName();
        $newfilename = $date->format('Ymdhsi').$filename.".csv";
        $newfileData = '';
        
        if ($filePathName) {
            $temp = tmpfile();
            $inserted = 0;
            $failed = 0;
            while (($line = fgets($handle)) !== false) {
                $line = str_replace("\n", "", $line);
                $line = str_replace("\r", "", $line);
                $line = str_replace(" ", "", $line);
                $tmpData = $validateInsertion->set($library['Tabla'], $date, $username, $line, $library, (int)$request->categoria, $filename);
                if ($tmpData['InsercionEstado'] == 1)
                    $inserted++;
                else
                    $failed++;

                $newfileData.= (empty($newfileData) ? '' : '\r\n' );
                $newfileData.= implode(';', $tmpData);
            }
            fclose($handle);
            fseek($temp, 0);
        }
        file_put_contents("/tmp/".$newfilename, $newfileData);
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
        readfile("/tmp/{$request->name}");
        exit();
    }
}
