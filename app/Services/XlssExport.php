<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
class XlssExport{

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
        $activeWorksheet->getStyle("A1:F{$xAngle}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        return $this->returnDonwloadableFile($spreadsheet, $nombre, $date->format('Y-m-d h:s:i') );
    }

    /**
     * Bootstrap services.
     */
    public function executeSecondReport($table,$posInventario){
        $date = new \DateTime();
        $spreadsheet = new Spreadsheet();
        $position = 0;
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
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras");

            if($posInventario)
                $data->whereIn("{$tableName}.Estado", ['I', 'D']);
            else 
                $data->where("{$tableName}.Estado", 'I');

            $data = $data->get()->toArray();
        $spreadsheet->getActiveSheet()->setTitle('INVENTARIADO');
        $this->setNewPageAndData($spreadsheet, $data, 'INVENTARIADO', $position++);

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
            ->where("{$tableName}.Estado", 'P')->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'PRESTADOS', $position++);
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
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->get()->toArray();
            ->where("master.Proceso", 'NIVEL CENTRAL')->get()->toArray();
        
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'NIVEL CENTRAL', $position++);

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
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->get()->toArray();
            ->where("{$tableName}.Situacion", 'En catalogación')->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'EN CATALOGACIÓN', $position++);

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
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->get()->toArray();
            ->where("anexos.Biblioteca_L", $table['Nombre'])->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'EN OTRAS BIB', $position++);


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
            //->where("{$tableName}.Estado", '<>', 'I')->where("{$tableName}.Estado", '<>','P')->get()->toArray();
            ->where("anexos.Biblioteca_O", $table['Nombre'])->get()->toArray();
        
        $otros['otrasBibCount'] = count($data);
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'EN MI BIB', $position++);

        $data = DB::table('anexos')
            ->select(
                'C_Barras', 
            )
            ->whereNull("anexos.Biblioteca_O")
            ->where("anexos.Biblioteca_L", $table['Nombre'])->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'NO ENCONTRADOS', $position++);
        unset($data);
        
        
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
            ->whereNotIn("{$tableName}.Estado", ['I', 'E'])
            ->where("master.Proceso", 'Baja')
            ->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'BAJA', $position++);

        if($posInventario){
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
                ->where("{$tableName}.Estado", 'E')
                ->get()->toArray();
            $spreadsheet->createSheet();
            $this->setNewPageAndData($spreadsheet, $data, 'ENCONTRADOS', $position++);
        }

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
        ->get()->toArray();

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
        ->first();
        
        $consolidado['Inventariado'] = DB::table($tableName)
        ->select('master.Localizacion', DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','')
        ->whereIn('master.Localizacion' ,['General','Infantil'])
        ->groupBy('master.Localizacion')
        ->get()->toArray();

        $consolidado['Faltante'] = DB::table($tableName)
        ->select('master.Localizacion', DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','I')
        ->where("{$tableName}.Estado", '<>','P')
        ->where('master.Proceso', '!=', 'Nivel Central')
        ->whereIn('master.Localizacion' ,['General','Infantil'])
        ->groupBy('master.Localizacion')
        ->get()->toArray();

        $consolidado['FaltanteNivelCentral'] = DB::table($tableName)
        ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','I')
        ->where("{$tableName}.Estado", '<>','P')
        ->where('master.Proceso', 'Nivel Central')
        ->first();
        
        $consolidado['FaltanteTransito'] = DB::table($tableName)
        ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
        ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
        ->where("{$tableName}.Estado", '<>','I')
        ->where("{$tableName}.Estado", '<>','P')
        ->where('master.Proceso', 'Material en tránsito')
        ->first();
        $this->createConsilidatedReport($spreadsheet ,$table, $position++, $otros, $consolidado);

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
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25); // Set column A width to 20
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20); // Set column A width to 20
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20); // Set column A width to 20
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(16);
        $spreadsheet->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDE5ED');
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'INFORME CONSOLIDADO DE TOMA FÍSICA');
        $spreadsheet->getActiveSheet()->mergeCells('A1:F1', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, 'A1:F1');

        $spreadsheet->getActiveSheet()->getStyle("A3:D6")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $spreadsheet->getActiveSheet()->setCellValue('A3', 'UBICACIÓN');
        $spreadsheet->getActiveSheet()->mergeCells('A3:A4', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, 'A3:A4');
        $spreadsheet->getActiveSheet()->setCellValue('B3', 'TOMA FíSICA');
        $spreadsheet->getActiveSheet()->mergeCells('B3:D3', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, 'B3:D3');

        $spreadsheet->getActiveSheet()->setCellValue('B4', 'CAPTURADOS');
        $spreadsheet->getActiveSheet()->setCellValue('C4', 'REPETIDOS');
        $spreadsheet->getActiveSheet()->setCellValue('D4', 'TOTAL');
        $this->addHeaderStyle($spreadsheet, 'B4:D4');
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
        $this->addHeaderStyle($spreadsheet, 'A8:D8');

        $spreadsheet->getActiveSheet()->setCellValue('A10', 'UBICACIÓN');
        $spreadsheet->getActiveSheet()->mergeCells('A10:A11', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, 'A10:A11');


        $spreadsheet->getActiveSheet()->setCellValue('B10', 'PROCESO DE INVENTARIO');
        $this->addHeaderStyle($spreadsheet, 'B10');
        $spreadsheet->getActiveSheet()->setCellValue('B11', 'INVENTARIADOS');
        $this->addHeaderStyle($spreadsheet, "B11");
        $sumtotal = 0;
        foreach ($dataUbicaciones['ubicaciones'] as $key => $value) {
            $spreadsheet->getActiveSheet()->setCellValue("A".($key+12), $value->Localizacion);
            $spreadsheet->getActiveSheet()->setCellValue("B".($key+12), $value->total);
            $sumtotal += $value->total;
        }
        $y = ($key ?? 1) + 13;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'TOTALES');
        $this->addHeaderStyle($spreadsheet, "A{$y}");
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", $sumtotal);
        $this->addHeaderStyle($spreadsheet, "B{$y}");
        $spreadsheet->getActiveSheet()->getStyle("A10:B{$y}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $spreadsheet->getActiveSheet()->setCellValue('D10', 'OTROS');
        $this->addHeaderStyle($spreadsheet, 'D10');
        $spreadsheet->getActiveSheet()->mergeCells('D10:F10', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue('D11', 'BAJA ACUMULADA');
        $this->addHeaderStyle($spreadsheet, 'D11');
        $spreadsheet->getActiveSheet()->setCellValue('E11', 'OTRAS BIBLIOTECAS');
        $this->addHeaderStyle($spreadsheet, 'E11');
        $spreadsheet->getActiveSheet()->setCellValue('F11', 'TOTAL');
        $this->addHeaderStyle($spreadsheet, 'F11');
        $spreadsheet->getActiveSheet()->setCellValue('D12', $dataUbicaciones['Baja']->total);
        $spreadsheet->getActiveSheet()->setCellValue('E12', $dataUbicaciones['otrasBibCount']);
        $spreadsheet->getActiveSheet()->setCellValue('F12', ((int)$dataUbicaciones['Baja']->total + (int)$dataUbicaciones['otrasBibCount']));
        $spreadsheet->getActiveSheet()->setCellValue('D13', $dataUbicaciones['Baja']->total);
        $spreadsheet->getActiveSheet()->setCellValue('E13', $dataUbicaciones['otrasBibCount']);
        $spreadsheet->getActiveSheet()->setCellValue('F13', ((int)$dataUbicaciones['Baja']->total + (int)$dataUbicaciones['otrasBibCount']));
        $spreadsheet->getActiveSheet()->getStyle("D10:F13")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $y+=2;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'INFORME CONSOLIDADO DE NO INVENTARIADOS Y TOTAL DE COLECCIÓN');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:D{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $y+=2;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'DESCRIPCIÓN');
        $tableStart = "A{$y}";
        $this->addHeaderStyle($spreadsheet, "A{$y}");
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:A".($y+1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'RESUMEN DE INVENTARIO EN PERGAMUM');
        $spreadsheet->getActiveSheet()->mergeCells("B{$y}:I{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "B{$y}:I{$y}");
        $y+=1;
        $rangeAngleX = range('B', 'I');
        foreach (['UBICACIÓN', 'ESTADO', 'CANTIDAD', 'TOTAL', 'PRECIO', 'PRECIO TOTAL', '%', '%TOTAL'] as $key => $value) {
            $this->addHeaderStyle($spreadsheet, "{$rangeAngleX[$key]}{$y}");
            $spreadsheet->getActiveSheet()->setCellValue("{$rangeAngleX[$key]}{$y}", $value);
        }

        $this->createSheetConsolidate($spreadsheet, $consolidado, $y, $tableStart);
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

    public function createSheetConsolidate($spreadsheet, $consolidado, $y, $tableStart) {

        if(sizeof($consolidado['Inventariado'])){
            $totalInventariadoGeneral = $consolidado['Inventariado'][0]->total;
            $totalInventariadoInfantil = $consolidado['Inventariado'][1]->total;
            $consolidadoInventariadoGeneralPrecio = $consolidado['Inventariado'][0]->totalprice;
            $consolidadoInventariadoInfantilPrecio = $consolidado['Inventariado'][1]->totalprice;
        } else {
            $totalInventariadoGeneral = 0;
            $totalInventariadoInfantil = 0;
            $consolidadoInventariadoGeneralPrecio = 0;
            $consolidadoInventariadoInfantilPrecio = 0;
        }

        if(sizeof($consolidado['Faltante'])){
            $totalFaltanteGeneral = $consolidado['Faltante'][0]->total;
            $totalFaltanteInfantil = $consolidado['Faltante'][1]->total;
            $consolidadoFaltanteGeneralPrecio = $consolidado['Faltante'][0]->totalprice;
            $consolidadoFaltanteInfantilPrecio = $consolidado['Faltante'][1]->totalprice;
        } else {
            $totalFaltanteGeneral = 0;
            $totalFaltanteInfantil = 0;
            $consolidadoFaltanteGeneralPrecio = 0;
            $consolidadoFaltanteInfantilPrecio = 0;
        }

        if(sizeof((array)$consolidado['FaltanteNivelCentral'])){
            $totalFaltanteNivelCentral = $consolidado['FaltanteNivelCentral']->total;
            $consolidadoFaltanteNivelCentralPrecio = $consolidado['FaltanteNivelCentral']->totalprice;
        } else {
            $totalFaltanteNivelCentral = 0;
            $consolidadoFaltanteNivelCentralPrecio = 0;
        }

        if(sizeof((array)$consolidado['FaltanteTransito'])){
            $totalFaltanteTransito = $consolidado['FaltanteTransito']->total;
            $consolidadoFaltanteTransitoPrecio = $consolidado['FaltanteTransito']->totalprice;
        } else {
            $totalFaltanteTransito = 0;
            $consolidadoFaltanteTransitoPrecio = 0;
        }

        $totalInventariado = $totalInventariadoGeneral + $totalInventariadoInfantil;
        $totalInventariadoPrecio = $consolidadoInventariadoGeneralPrecio + $consolidadoInventariadoInfantilPrecio;
        $totalFaltante = $totalFaltanteGeneral + $totalFaltanteInfantil;
        $totalFaltantePrecio = $consolidadoFaltanteGeneralPrecio + $consolidadoFaltanteInfantilPrecio;
        $totalSuma = $totalInventariado + $totalFaltante + $totalFaltanteNivelCentral + $totalFaltanteTransito;
        $totalSumaPrecio = $totalInventariadoPrecio + $totalFaltantePrecio + $consolidadoFaltanteNivelCentralPrecio + $consolidadoFaltanteTransitoPrecio;
        $porcentajeFaltanteGeneral = $totalFaltanteGeneral ? ($totalFaltanteGeneral / $totalSuma) * 100 : 0;
        $porcentajeFaltanteInfantil = $totalFaltanteInfantil ? ($totalFaltanteInfantil / $totalSuma) * 100 : 0;
        $porcentajeFaltanteNivelCentral = $totalFaltanteNivelCentral ? ($totalFaltanteNivelCentral / $totalSuma) * 100 : 0;
        $porcentajeFaltanteTransito = $totalFaltanteTransito ? ($totalFaltanteTransito / $totalSuma) * 100 : 0;
        $porcentajeInventariadoGeneral = $totalInventariadoGeneral ? ($totalInventariadoGeneral / $totalSuma) * 100 : 0;
        $porcentajeInventariadoInfantil = $totalInventariadoInfantil ? ($totalInventariadoInfantil / $totalSuma) * 100 : 0;

        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Inventariado');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:A".$y+1, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'General');
        $spreadsheet->getActiveSheet()->setCellValue("C{$y}", 'No Disponible');
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $totalInventariadoGeneral);
        $spreadsheet->getActiveSheet()->setCellValue("E{$y}", $totalInventariado);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}",  '$' . number_format($consolidadoInventariadoGeneralPrecio,2));
        $spreadsheet->getActiveSheet()->setCellValue("G{$y}",  '$' . number_format($consolidadoInventariadoGeneralPrecio + $consolidadoInventariadoInfantilPrecio,2));
        $spreadsheet->getActiveSheet()->mergeCells("G{$y}:G".$y+1, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", number_format($porcentajeInventariadoGeneral, 2) . ' %');
        $spreadsheet->getActiveSheet()->setCellValue("I{$y}", number_format($porcentajeInventariadoGeneral + $porcentajeInventariadoInfantil, 2) . ' %');

        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'Infantil');
        $spreadsheet->getActiveSheet()->setCellValue("C{$y}", 'No Disponible');
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $totalInventariadoInfantil);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}",  '$' . number_format($consolidadoInventariadoInfantilPrecio, 2));
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", number_format($porcentajeInventariadoInfantil, 2) . ' %');

        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Faltante');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:A".$y+1, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'General');
        $spreadsheet->getActiveSheet()->setCellValue("C{$y}", 'No Disponible');
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $totalFaltanteGeneral);
        $spreadsheet->getActiveSheet()->setCellValue("E{$y}", $totalFaltante);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($consolidadoFaltanteGeneralPrecio, 2));
        $spreadsheet->getActiveSheet()->setCellValue("G{$y}", '$' . number_format($consolidadoFaltanteGeneralPrecio + $consolidadoFaltanteInfantilPrecio, 2));
        $spreadsheet->getActiveSheet()->mergeCells("G{$y}:G".$y+1, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", number_format($porcentajeFaltanteGeneral, 2) . ' %');
        $spreadsheet->getActiveSheet()->setCellValue("I{$y}", number_format($porcentajeFaltanteGeneral + $porcentajeFaltanteInfantil, 2) . ' %');

        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'Infantil');
        $spreadsheet->getActiveSheet()->setCellValue("C{$y}", 'No Disponible');
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $totalFaltanteInfantil);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($consolidadoFaltanteInfantilPrecio,2));
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", number_format($porcentajeFaltanteInfantil, 2) . ' %');

        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Faltante');
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'Nivel Central');
        $spreadsheet->getActiveSheet()->mergeCells("B{$y}:C{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $totalFaltanteNivelCentral);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($consolidadoFaltanteNivelCentralPrecio,2));
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", number_format($porcentajeFaltanteNivelCentral, 2) . ' %');

        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Faltante');
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'Tránsito');
        $spreadsheet->getActiveSheet()->mergeCells("B{$y}:C{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $totalFaltanteTransito);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($consolidadoFaltanteTransitoPrecio,2));
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", number_format($porcentajeFaltanteTransito, 2) . ' %');

        $y+=1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Colección Total');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:C{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "A{$y}:C{$y}");
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $totalSuma);
        $spreadsheet->getActiveSheet()->mergeCells("D{$y}:E{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "D{$y}:E{$y}");
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($totalSumaPrecio, 2));
        $spreadsheet->getActiveSheet()->mergeCells("F{$y}:G{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "F{$y}:G{$y}");
        $spreadsheet->getActiveSheet()->setCellValue("H{$y}", '100 %');
        $spreadsheet->getActiveSheet()->mergeCells("H{$y}:I{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "H{$y}:I{$y}");
        $spreadsheet->getActiveSheet()->getStyle("$tableStart:I{$y}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
    }

    public function addHeaderStyle($spreadsheet, $cell){
        $spreadsheet->getActiveSheet()->getStyle($cell)->getFont()->setBold( true );
        $spreadsheet->getActiveSheet()->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('666699');
        $spreadsheet->getActiveSheet()->getStyle($cell)->getFont()->getColor()->setARGB('ffffff');
    }
}
