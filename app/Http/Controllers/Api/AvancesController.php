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
        try {
            DB::table($library['Tabla'])->first();
            $situaciones = DB::table($library['Tabla'])->where('Estado', 'I')->distinct()->get(['Situacion']);
            $countInventariado = DB::table($library['Tabla'])->where('Estado', 'I')->count();
            foreach ($situaciones as $value) {
                $situacionesArray[$value->Situacion] = DB::table($library['Tabla'])->where('Estado', 'I')->where('Situacion', $value->Situacion)->count();
            }
            $situaciones = DB::table($library['Tabla'])->where('Estado', '<>', 'I')->distinct()->get(['Situacion']);
            $countNoInventariado = DB::table($library['Tabla'])->where('Estado', '<>', 'I')->count();
            foreach ($situaciones as $value) {
                $situacionesArrayNI[$value->Situacion] = DB::table($library['Tabla'])->where('Situacion', $value->Situacion)->where('Estado', '<>', 'I')->count();
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
                'noInventariado' => $tableExists ? $countNoInventariado : null,
                'situacionesI' => $situacionesArray,
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
            ->where('Localizacion', $request->lcl);
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
}
