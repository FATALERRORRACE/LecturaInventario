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
        $dataTable = [];
        $data = Bibliotecas::where('id', $id)->first();

        $tableExists = true;
        try {
            $dataTable = DB::table($data['Tabla'])->first();
        } catch (\Throwable $th) {
            $tableExists = false;
        }
        
        return view(
            'index.administracion',
            [
                'tableExists' => $tableExists,
                
            ]
        );
    }

    public function createJob(Request $request){
        $request->validate([
            'table' => ['required'],
        ]);
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
        return DB::table($data['Tabla'])->get()->toArray();
    }


    public function createXls(int $id, Request $request){
        $data = Bibliotecas::where('id', $id)->first();
        $xlssExportInstance = new XlssExport;
        $xlssExportInstance
        ->execute(
            DB::table($data['Tabla'])->get()->toArray(), 
            $data['Nombre']
        );
    }

    public function processTable($tableName, $username){
        $date = new \DateTime();
        $insert = [];
        $data = Bibliotecas::where("Nombre", $tableName)->first();
        Schema::create($data['Tabla'], function (Blueprint $table) {
            $table->id();
            $table->string('C_Barras', 20);
            $table->string('Situacion', 60);
            $table->string('Comentario', 250);
            $table->string('Usuario', 15);
            $table->string('Estado', 1);
            $table->date('Fecha')->nullable();
        });
        $registros = Master::where("Biblioteca", $tableName)->get()->toArray();
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
        if ($insert)
            DB::table($data['Tabla'])->insert($insert);

    }
}