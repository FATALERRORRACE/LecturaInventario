<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bibliotecas;
use App\Models\Master;
use App\Services\ValidateInsertion;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\throwException;

class AvancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo($id, Request $request){
        $library = Bibliotecas::where("id", $id)->first();

        $tableExists = true;
        
            DB::table($library['Tabla'])->first();
            $situaciones = DB::table($library['Tabla'])->distinct()->get(['Situacion']);
            $situacionesArray = [];
            $countInventariado = DB::table($library['Tabla'])->where('Estado', 'I')->count();
            $countNoInventariado = DB::table($library['Tabla'])->where('Estado', '<>','I')->count();
            foreach ($situaciones as $key => $value) {
                $situacionesArray[$value->Situacion] = DB::table($library['Tabla'])->where('Situacion', $value->Situacion)->count();
            }
            try {    
        } catch (\Throwable $th) {
            $tableExists = false;
        }
        return view(
            'index.avances',
            [
                'tableExists' => $tableExists,
                'total' => ($countInventariado + $countNoInventariado),
                'inventariado' => $countInventariado,
                'noInventariado' => $countNoInventariado,
                'situaciones' => $situacionesArray
            ]
        );
    }

}
