<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bibliotecas;
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
        try {
            DB::table($library['Tabla'])->first();
            $situaciones = DB::table($library['Tabla'])->distinct()->get(['Situacion']);
            $situacionesArray = [];
            $countInventariado = DB::table($library['Tabla'])->where('Estado', 'I')->count();
            $countNoInventariado = DB::table($library['Tabla'])->where('Estado', '<>', 'I')->count();
            foreach ($situaciones as $value) {
                $situacionesArray[$value->Situacion] = DB::table($library['Tabla'])->where('Situacion', $value->Situacion)->count();
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
                'situaciones' => $tableExists ? $situacionesArray : null
            ]
        );
    }
}
