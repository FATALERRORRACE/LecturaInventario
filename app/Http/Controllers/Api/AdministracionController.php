<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Bibliotecas;
use App\Models\Master;
use App\Services\XlssExport;

class AdministracionController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $id ,Request $request){
        $data = Bibliotecas::where('id', $id)->first();
        $tableExists = true;
        try {
            DB::table($data['Tabla'])->first();
        } catch (\Throwable $th) {
            $tableExists = false;
        }
        
        return view(
            'index.administracion',
            [
                'tableExists' => $tableExists,
                'date' => "{$data['Fecha_Inventario']} - {$data['Fecha_Fin_Inventario']}",
            ]
        );
    }

    public function createJob(Request $request){
        $username = $request->user()->username;
        $tableName = $request->table;
        $this->processTable($tableName, $username);
        return [
            'message' => '¡creación de registros realizada con exito!',
            'status' => 200
        ];
    }


    public function getData($id ,Request $request){
        $data = Bibliotecas::where('id', $id)->first();
        //$query = DB::table($data['Tabla']);
        //switch ((int)$request->categoria) {
        //    case 1:
        //        $query->where('Estado');
        //        'Nivel Central';
        //        break;
        //    case 2:
        //        $query->where();
        //        'Normal No Displonible';
        //        break;
        //    case 3:
        //        $query->where();
        //        'En catalogación';
        //        break;
        //}
        $fechaInventario = new \DateTime($data['Fecha_Inventario']);
        $fechaFinInventario = new \DateTime($data['Fecha_Fin_Inventario']);

        return [
            'fechaInicio' => $fechaInventario->format('Y-m-d'), 
            'fechaFin' => $fechaFinInventario->format('Y-m-d'),
            'data' => DB::table($data['Tabla'])->get()->toArray()
        ];
    }

    public function getDataAdvance($id ,Request $request){
        $data = Bibliotecas::where('id', $id)->first();
        return [
            "data" => DB::table($data['Tabla'])->where('Estado',"!=", "")->get()->toArray()
        ];
    }

    public function createXls(int $id, Request $request){
        $data = Bibliotecas::select()->where('id', $id)->first();
        $xlssExportInstance = new XlssExport;
        $xlssExportInstance->execute(
            DB::table($data['Tabla'])->select(
                'C_Barras',
                'Usuario',
                'Situacion',
                'Comentario',
                'Fecha',
                'Estado',
            )->get()->toArray(), 
            $data
        );
    }

    public function createXlsMaster(int $id){
        $data = Bibliotecas::where('id', $id)->first();
        $xlssExportInstance = new XlssExport;
        $xlssExportInstance->executeSecondReport($data);
    }

    public function processTable($tableName, $username){
        $date = new \DateTime();
        $insert = [];
        $data = Bibliotecas::where("Nombre", $tableName)->first();
        Schema::create($data['Tabla'], function (Blueprint $table) {
            $table->id();
            $table->string('C_Barras', 20);
            $table->string('Situacion', 60);
            $table->string('Comentario', 75);
            $table->string('Usuario', 15);
            $table->string('Estado', 1);
            $table->dateTime('Fecha')->nullable();
        });

        $registrosCount = Master::where("Biblioteca", $tableName)->count();
        for ($i=0; $i < (($registrosCount / 10000)+1); $i++) {
            $registros = Master::where("Biblioteca", $tableName)->skip($i*10000)->take(10000)->get()->toArray();
            $sum = 1;
            foreach ($registros as $key => $val) {
                $sum++;
                $insert[] = [
                    'C_Barras' => $val['C_Barras'],
                    'Situacion' => $val['Proceso'],
                    'Comentario' => '',
                    'Usuario' => $username,
                    'Estado' => '',
                    'Fecha' => $date->format('Y-m-d'),
                ];
                if ($sum % 1000 == 0) {
                    DB::table($data['Tabla'])
                        ->insert($insert);
                    $insert = [];
                }
            }
        }
        if ($insert)
            DB::table($data['Tabla'])->insert($insert);

    }
}