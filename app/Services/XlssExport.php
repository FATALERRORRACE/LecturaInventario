<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use App\Models\Master;
use PhpParser\Node\Stmt\Foreach_;
use Psy\VarDumper\Dumper;

class XlssExport{

    private $indexPage = 1;
    /**
     * Bootstrap services.
     */
    public function execute($data, $nombre){
        $date = new \DateTime();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getColumnDimension('A')->setWidth(15);
        $activeWorksheet->getColumnDimension('B')->setWidth(12);
        $activeWorksheet->getColumnDimension('C')->setWidth(15);
        $activeWorksheet->getColumnDimension('D')->setWidth(12);
        $activeWorksheet->getColumnDimension('E')->setWidth(12);
        $activeWorksheet->getColumnDimension('F')->setWidth(12);
        $activeWorksheet->getStyle("A1:F1")->getFont()->setBold( true );
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDE5ED');
        $activeWorksheet->setCellValue('A1', 'C_Barras');
        $activeWorksheet->setCellValue('B1', 'Usuario');
        $activeWorksheet->setCellValue('C1', 'Situacion');
        $activeWorksheet->setCellValue('D1', 'Comentario');
        $activeWorksheet->setCellValue('E1', 'Fecha');
        $activeWorksheet->setCellValue('F1', 'Estado');

        $xAngle = 2;

        foreach ($data as $key => $value) {
            //array_values((array)$value);
            $activeWorksheet->fromArray([(array)$value], NULL, "A{$xAngle}");
            $xAngle++;
        }
        return $this->returnDonwloadableFile($spreadsheet, $nombre, $date->format('Y-m-d h:s:i') );
    }

    /**
     * Bootstrap services.
     */
    public function executeSecondReport($table){
        $date = new \DateTime();
        $spreadsheet = new Spreadsheet();
        $otros = [];
        $consolidado = [];
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $tableName = $table['Tabla'];

        $data = DB::table($tableName)
            ->select(
                'master.C_Barras', 
                'master.Titulo', 
                'master.Autor', 
                'master.Clasificacion', 
                'master.Isbn', 
                'master.Descripcion', 
                'master.Precio', 
                'master.Estadistica', 
                'master.Biblioteca', 
                'master.Material', 
                'master.Localizacion', 
                'master.Proceso', 
                'master.Creacion', 
                'master.Acervo'
            )
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", 'I')->take(1000)->get()->toArray();
        $spreadsheet->getActiveSheet()->setTitle('INVENTARIADO');
        $this->setNewPageAndData($spreadsheet, $data, 'INVENTARIADO', 0);

        $data = DB::table($tableName)
            ->select(
                'master.C_Barras', 
                'master.Titulo', 
                'master.Autor', 
                'master.Clasificacion', 
                'master.Isbn', 
                'master.Descripcion', 
                'master.Precio', 
                'master.Estadistica', 
                'master.Biblioteca', 
                'master.Material', 
                'master.Localizacion', 
                'master.Proceso', 
                'master.Creacion', 
                'master.Acervo'
            )
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", 'P')->take(1000)->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'PRESTADOS', 1);
        
        $data = DB::table($tableName)
            ->select(
                'master.C_Barras', 
                'master.Titulo', 
                'master.Autor', 
                'master.Clasificacion', 
                'master.Isbn', 
                'master.Descripcion', 
                'master.Precio', 
                'master.Estadistica', 
                'master.Biblioteca', 
                'master.Material', 
                'master.Localizacion', 
                'master.Proceso', 
                'master.Creacion', 
                'master.Acervo'
            )
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->take(1000)->get()->toArray();
            ->where("master.Proceso", 'NIVEL CENTRAL')->take(1000)->get()->toArray();
        
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'NIVEL CENTRAL', 2);

        $data = DB::table($tableName)
            ->select(
                'master.C_Barras', 
                'master.Titulo', 
                'master.Autor', 
                'master.Clasificacion', 
                'master.Isbn', 
                'master.Descripcion', 
                'master.Precio', 
                'master.Estadistica', 
                'master.Biblioteca', 
                'master.Material', 
                'master.Localizacion', 
                'master.Proceso', 
                'master.Creacion', 
                'master.Acervo'
            )
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->take(1000)->get()->toArray();
            ->where("{$tableName}.Situacion", 'En catalogación')->take(1000)->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'EN CATALOGACIÓN', 3);

        $data = DB::table('anexos')
            ->select(
                'master.C_Barras', 
                'master.Titulo', 
                'master.Autor', 
                'master.Clasificacion', 
                'master.Isbn', 
                'master.Descripcion', 
                'master.Precio', 
                'master.Estadistica', 
                'master.Biblioteca', 
                'master.Material', 
                'master.Localizacion', 
                'master.Proceso', 
                'master.Creacion', 
                'master.Acervo'
            )
            ->join('master', 'master.C_Barras', '=', "anexos.C_Barras")
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->take(1000)->get()->toArray();
            ->where("anexos.Biblioteca_L", $table['Nombre'])->take(1000)->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'EN OTRAS BIB', 4);

        $data = DB::table('anexos')
            ->select(
                'master.C_Barras', 
                'master.Titulo', 
                'master.Autor', 
                'master.Clasificacion', 
                'master.Isbn', 
                'master.Descripcion', 
                'master.Precio', 
                'master.Estadistica', 
                'master.Biblioteca', 
                'master.Material', 
                'master.Localizacion', 
                'master.Proceso', 
                'master.Creacion', 
                'master.Acervo'
            )
            ->join('master', 'master.C_Barras', '=', "anexos.C_Barras")
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->take(1000)->get()->toArray();
            ->where("anexos.Biblioteca_O", $table['Nombre'])->take(1000)->get()->toArray();
        $spreadsheet->createSheet();

        $otros['otrasBibCount'] = count($data);
        $this->setNewPageAndData($spreadsheet, $data, 'EN MI BIB', 5);

        $data = DB::table('anexos')
            ->select(
                'C_Barras', 
            )
            ->whereNull("anexos.Biblioteca_O")
            ->where("anexos.Biblioteca_L", $table['Nombre'])->take(1000)->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'NO ENCONTRADOS', 6);
        unset($data);
        
        $otros['ubicaciones'] = DB::table($tableName)
        ->select('master.Localizacion', DB::raw("COUNT($tableName.id) as total"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", 'I')
        ->whereIn('master.Localizacion' ,
            [
                'Distrito Gráfico',
                'General',
                'Infantil',
                'Sonoteca',
                'Videoteca',
            ]
        )
        ->groupBy('master.Localizacion')
        ->take(1000)->get()->toArray();

        $otros['Baja'] = DB::table($tableName)
        ->select(DB::raw("COUNT($tableName.id) as total"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", 'I')
        ->where("{$tableName}.Estado", 'P')
        ->whereNotIn('master.Localizacion' ,
            [
                'Distrito Gráfico',
                'General',
                'Infantil',
                'Sonoteca',
                'Videoteca',
            ]
        )
        ->take(1000)->first();
        
        $consolidado['Inventariado'] = DB::table($tableName)
        ->select('master.Localizacion', DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','')
        ->whereIn('master.Localizacion' ,['General','Infantil'])
        ->groupBy('master.Localizacion')
        ->take(1000)->get()->toArray();

        $consolidado['Faltante'] = DB::table($tableName)
        ->select('master.Localizacion', DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','I')
        ->where("{$tableName}.Estado", '<>','P')
        ->where('master.Proceso', '!=', 'Nivel Central')
        ->whereIn('master.Localizacion' ,['General','Infantil'])
        ->groupBy('master.Localizacion')
        ->take(1000)->get()->toArray();

        $consolidado['FaltanteNivelCentral'] = DB::table($tableName)
        ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','I')
        ->where("{$tableName}.Estado", '<>','P')
        ->where('master.Proceso', 'Nivel Central')
        ->take(1000)->first();
        
        $consolidado['FaltanteTransito'] = DB::table($tableName)
        ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','I')
        ->where("{$tableName}.Estado", '<>','P')
        ->where('master.Proceso', 'Material en tránsito')
        ->take(1000)->first();
        $this->createConsilidatedReport($spreadsheet ,$table, 7, $otros, $consolidado);

        $activeWorksheet->getStyle("A1:O1")->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDE5ED');

        return $this->returnDonwloadableFile($spreadsheet, $table['nombre'], $date->format('Y-m-d h:s:i') ); 
    }

    /**
     * Bootstrap services.
     */
    public function setNewPageAndData(&$spreadsheet, $data, $pageTitle, $indexPage){
        $spreadsheet->setActiveSheetIndex($indexPage);
        $spreadsheet->getActiveSheet()->setTitle($pageTitle);
        $range = range('A', 'Ñ');
        $header = [
                'C_Barras', 
                'Titulo', 
                'Autor', 
                'Clasificacion', 
                'Isbn', 
                'Descripcion', 
                'Precio', 
                'Estadistica', 
                'Biblioteca', 
                'Material', 
                'Localizacion', 
                'Proceso', 
                'Creacion', 
                'Acervo'
        ];
        if($pageTitle == 'NO ENCONTRADOS')
            $header = ['C_Barras'];

        foreach ($header as $key => $value)
            $spreadsheet->getActiveSheet()->setCellValue("{$range[$key]}1" , $value);

        $xAngle = 2;
        foreach ($data as $key => $value) {
            $spreadsheet->getActiveSheet()->fromArray([(array)$value], NULL, "A{$xAngle}");
            $xAngle++;
        }
    }    

    public function createConsilidatedReport($spreadsheet, $table, $position, $dataUbicaciones, $consolidado){
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex($position);
        $spreadsheet->getActiveSheet()->setTitle('ACUMULADO');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $spreadsheet->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDE5ED');
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'INFORME CONSOLIDADO DE TOMA FISICA');
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $spreadsheet->getActiveSheet()->setCellValue('A3', 'UBICACIÓN');
        $spreadsheet->getActiveSheet()->mergeCells('A3:A4', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue('B3', 'TOMA FíSICA');
        $spreadsheet->getActiveSheet()->mergeCells('B3:D3', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $spreadsheet->getActiveSheet()->setCellValue('B4', 'CAPTURADOS');
        $spreadsheet->getActiveSheet()->setCellValue('C4', 'REPETIDOS');
        $spreadsheet->getActiveSheet()->setCellValue('D4', 'TOTAL');

        $spreadsheet->getActiveSheet()->setCellValue('A5', $table['Nombre']);
        $spreadsheet->getActiveSheet()->setCellValue('B5', '10.000');
        $spreadsheet->getActiveSheet()->setCellValue('C5', '10.000');
        $spreadsheet->getActiveSheet()->setCellValue('D5', '10.000');

        $spreadsheet->getActiveSheet()->setCellValue('A6', 'TOTALES');
        $spreadsheet->getActiveSheet()->setCellValue('B6', '10.000');
        $spreadsheet->getActiveSheet()->setCellValue('C6', '10.000');
        $spreadsheet->getActiveSheet()->setCellValue('D6', '10.000');

        $spreadsheet->getActiveSheet()->setCellValue('A8', 'INFORME CONSOLIDADO DEL PROCESO DE INVENTARIO EN PERGAMUM');
        $spreadsheet->getActiveSheet()->mergeCells('A8:D8', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $spreadsheet->getActiveSheet()->setCellValue('A10', 'UBICACIÓN');
        $spreadsheet->getActiveSheet()->mergeCells('A10:A11', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $spreadsheet->getActiveSheet()->setCellValue('B10', 'PROCESO DE INVENTARIO');
        $spreadsheet->getActiveSheet()->setCellValue('B11', 'INVENTARIADOS');
        $sumtotal = 0;
        foreach ($dataUbicaciones['ubicaciones'] as $key => $value) {
            $spreadsheet->getActiveSheet()->setCellValue("A".($key+12), $value->Localizacion);
            $spreadsheet->getActiveSheet()->setCellValue("B".($key+12), $value->total);
            $sumtotal += $value->total;
        }
        $y = $key + 13;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'TOTALES');
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", $sumtotal);
        $spreadsheet->getActiveSheet()->setCellValue('D10', 'OTROS');
        $spreadsheet->getActiveSheet()->mergeCells('D10:F10', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue('D11', 'BAJA ACUMULADA');
        $spreadsheet->getActiveSheet()->setCellValue('E11', 'OTRAS BIBLIOTECAS');
        $spreadsheet->getActiveSheet()->setCellValue('F11', 'TOTAL');
        $spreadsheet->getActiveSheet()->setCellValue('D12', $dataUbicaciones['Baja']->total);
        $spreadsheet->getActiveSheet()->setCellValue('E12', $dataUbicaciones['otrasBibCount']);
        $spreadsheet->getActiveSheet()->setCellValue('F12', ((int)$dataUbicaciones['Baja']->total + (int)$dataUbicaciones['otrasBibCount']));
        $spreadsheet->getActiveSheet()->setCellValue('D13', $dataUbicaciones['Baja']->total);
        $spreadsheet->getActiveSheet()->setCellValue('E13', $dataUbicaciones['otrasBibCount']);
        $spreadsheet->getActiveSheet()->setCellValue('F13', ((int)$dataUbicaciones['Baja']->total + (int)$dataUbicaciones['otrasBibCount']));
        $y+=2;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'INFORME CONSOLIDADO DE NO INVENTARIADOS Y TOTAL DE COLECCIÓN');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:D{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $y+=2;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'DESCRIPCIÓN');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:A".($y+1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'RESUMEN DE INVENTARIO EN PERGAMUM');
        $spreadsheet->getActiveSheet()->mergeCells("B{$y}:G{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $y+=1;
        $rangeAngleX = range('B', 'I');
        foreach (['UBICACIÓN', 'ESTADO', 'TOTAL', 'PRECIO', 'PRECIO TOTAL', '%', '%TOTAL'] as $key => $value) {
            $spreadsheet->getActiveSheet()->setCellValue("{$rangeAngleX[$key]}{$y}", $value);
        }
        $y+=1;

        $totalInventariado = $consolidado['Inventariado'][0]->total + $consolidado['Inventariado'][1]->total;
        $totalInventariado = $consolidado['Faltante'][0]->total + $consolidado['Faltante'][1]->total;
        $totalSuma = $totalInventariado + $totalInventariado + $consolidado['FaltanteNivelCentral']->total + $consolidado['FaltanteTransito']->total;
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'General');
        $spreadsheet->getActiveSheet()->setCellValue("C{$y}", 'No Disponible');
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $consolidado['Inventariado'][0]->total);
        $spreadsheet->getActiveSheet()->setCellValue("E{$y}", $totalInventariado);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", $consolidado['Inventariado'][0]->totalprice);
        $spreadsheet->getActiveSheet()->setCellValue("G{$y}", $consolidado['Inventariado'][0]->totalprice + $consolidado['Inventariado'][1]->totalprice);
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", ($consolidado['Inventariado'][0]->total / $totalSuma) * 100 . ' %');
        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'Infantil');
        $spreadsheet->getActiveSheet()->setCellValue("C{$y}", 'No Disponible');
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $consolidado['Inventariado'][1]->total);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", $consolidado['Inventariado'][1]->totalprice);
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", ($consolidado['Inventariado'][1]->total / $totalSuma) * 100 . ' %');
        
        //$consolidado
        //$consolidado['Inventariado'][0]['total'] //general
        //$consolidado['Inventariado'][1]['totalprice'] //infamtiol
        //$consolidado['Faltante'][0]['total'] //general
        //$consolidado['Faltante'][1]['totalprice'] //infamtiol

        //$spreadsheet->getActiveSheet()->setCellValue('A{$key}', 'FALTANTES');
    }

    /**
     * Bootstrap services.
     */
    public function returnDonwloadableFile($spreadsheet, $nombre, $date){
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $nombre . " ". $date . '.xlsx"');
        $writer->save("php://output");
    }
}