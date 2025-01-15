<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bibliotecas;
use App\Models\Master;
use Illuminate\Support\Facades\DB;


class AvancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo($id, Request $request)
    {
        $library = Bibliotecas::where("id", $id)->first();
        $tableExists = true;
        $situacionesArrayNI = [];
        $situacionesArray = [];
        $situacionesArrayP = [];
        try {
            DB::table($library['Tabla'])->first();
            $situaciones = DB::table($library['Tabla'])->where('Estado', 'P')->distinct()->get(['Situacion']);
            $countPrestado = DB::table($library['Tabla'])->where('Estado', 'P')->count();
            foreach ($situaciones as $value) {
                $situacionesArrayP[$value->Situacion] = DB::table($library['Tabla'])->where('Estado', 'P')->where('Situacion', $value->Situacion)->count();
            }
            $situaciones = DB::table($library['Tabla'])->where('Estado', 'I')->distinct()->get(['Situacion']);
            $countInventariado = DB::table($library['Tabla'])->where('Estado', 'I')->count();
            foreach ($situaciones as $value) {
                $situacionesArray[$value->Situacion] = DB::table($library['Tabla'])->where('Estado', 'I')->where('Situacion', $value->Situacion)->count();
            }
            $situaciones = DB::table($library['Tabla'])->where('Estado', '<>', 'I')->where('Estado', '<>', 'P')->distinct()->get(['Situacion']);
            $countNoInventariado = DB::table($library['Tabla'])->where('Estado', '<>', 'I')->where('Estado', '<>', 'P')->count();
            foreach ($situaciones as $value) {
                $situacionesArrayNI[$value->Situacion] = DB::table($library['Tabla'])->where('Situacion', $value->Situacion)->where('Estado', '<>', 'I')->where('Estado', '<>', 'P')->count();
            }
        } catch (\Throwable $th) {
            $tableExists = false;
        }

        return view(
            'index.avances',
            [
                'tableExists' => $tableExists,
                'total' => $tableExists ? ($countInventariado + $countNoInventariado) : null,
                'inventariado' => $tableExists ? $countInventariado : null,
                'prestado' => $tableExists ? $countPrestado : null,
                'noInventariado' => $tableExists ? $countNoInventariado : null,
                'situacionesI' => $situacionesArray,
                'situacionesP' => $situacionesArrayP,
                'situacionesNI' => $situacionesArrayNI
            ]
        );
    }

    public function getMasterDirectoryTreeData($biblioteca){
        return Master::select('Localizacion')
            ->where('Biblioteca', $biblioteca)
            ->distinct('Localizacion')
            ->take(100)
            ->get()
            ->toArray();
    }

    public function getTreeTemplate($id, Request $request){
        $library = Bibliotecas::where("id", $id)->first();
        return view(
            'index.tree',
            [
                'dataTree' => $this->getMasterDirectoryTreeData($library['Nombre']),
            ]
        );
    }

    public function getClasificacionData($id, Request $request){
        $library = Bibliotecas::where("id", $id)->first();
        $clasificacion = Master::select('Clasificacion')
            ->where('Biblioteca', $library['Nombre'])
            ->where('Localizacion', $request->lcl)
            ->whereNull('Estado');
        if($request->search)
            $clasificacion->where('Clasificacion', 'LIKE', "{$request->search}%");
        return view(
            'index.tree2',
            [
                'dataTree' => $clasificacion->distinct('Clasificacion')
                    ->take(100)
                    ->get()
                    ->toArray(),
            ]
        );
    }

    public function getInventariados($id ,Request $request){
        $data = Bibliotecas::where('id', $id)->first();
        $qryGetData = DB::
            table($data['Tabla'])
            ->select(
                "{$data['Tabla']}.C_Barras",
                "{$data['Tabla']}.Usuario",
                "{$data['Tabla']}.Situacion",
                "{$data['Tabla']}.Comentario",
                "{$data['Tabla']}.Fecha",
                "{$data['Tabla']}.Estado",
            )
            ->where('Estado','');
        if($request->search){
            $qryGetData
                ->where(function($q) use ($data, $request) {
                    $q->where("{$data['Tabla']}.C_Barras", 'LIKE', "{$request->search}%")
                    ->orWhere("{$data['Tabla']}.Situacion", 'LIKE', "{$request->search}%")
                    ->orWhere("{$data['Tabla']}.Usuario", 'LIKE', "{$request->search}%");
                    //->orWhere("{$data['Tabla']}.Fecha", 'LIKE', "{$request->search}%")
                });
            
        }
        $countData = $qryGetData->count();
        return [
            'data' => $qryGetData
                ->take($request->limit)
                ->skip($request->offset)
                ->get()
                ->toArray(),
            'total' => $countData
        ];
    }
    public function getNoInventariados($id ,Request $request){
        $data = Bibliotecas::where('id', $id)->first();
        $qryGetData = DB::
            table($data['Tabla'])
            ->select(
                'master.C_Barras',
                'master.Titulo',
                'master.Clasificacion',
                "{$data['Tabla']}.Usuario",
                "{$data['Tabla']}.Situacion",
                "{$data['Tabla']}.Comentario",
                "{$data['Tabla']}.Fecha",
                "{$data['Tabla']}.Estado",
            )
            ->join('master', 'master.C_Barras', '=', "{$data['Tabla']}.C_Barras")
            ->where('Estado','');
        if($request->search){
            $qryGetData
                ->where(function($q) use ($data, $request) {
                    $q->where("{$data['Tabla']}.C_Barras", 'LIKE', "{$request->search}%")
                    ->orWhere('master.Titulo', 'LIKE', "{$request->search}%")
                    ->orWhere('master.Clasificacion', 'LIKE', "{$request->search}%")
                    ->orWhere("{$data['Tabla']}.Situacion", 'LIKE', "{$request->search}%")
                    ->orWhere("{$data['Tabla']}.Usuario", 'LIKE', "{$request->search}%");
                    //->orWhere("{$data['Tabla']}.Fecha", 'LIKE', "{$request->search}%")
                    //->orWhere('master.Titulo', 'LIKE', "{$request->search}%")
                    //->orWhere('master.Clasificacion', 'LIKE', "{$request->search}%");
                });
            
        }
        $countData = $qryGetData->count();
        return [
            'data' => $qryGetData
                ->take($request->limit)
                ->skip($request->offset)
                ->get()
                ->toArray(),
            'total' => $countData
        ];
    }
}
