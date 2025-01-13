<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use App\Models\Master;

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
        $this->setNewPageAndData($spreadsheet, $data, 'EN MI BIB', 5);


        $data = DB::table('anexos')
            ->select(
                'C_Barras', 
            )
            ->whereNull("anexos.Biblioteca_O")
            ->where("anexos.Biblioteca_L", $table['Nombre'])->take(1000)->get()->toArray();
        $spreadsheet->createSheet();
        $this->setNewPageAndData($spreadsheet, $data, 'NO ENCONTRADOS', 6);

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

    public function createConsilidatedReport($data){
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
        $activeWorksheet->setCellValue('A1', 'INFORME CONSOLIDADO DE TOMA FISICA');
        $spreadsheet->getActiveSheet()->mergeCells('A1:B1', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $activeWorksheet->setCellValue('A3', 'UBICACIÓN');
        $spreadsheet->getActiveSheet()->mergeCells('A3:A4', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $activeWorksheet->setCellValue('B3', 'TOMA FíSICA');
        $spreadsheet->getActiveSheet()->mergeCells('B3:D3', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $activeWorksheet->setCellValue('B4', 'CAPTURADOS');
        $activeWorksheet->setCellValue('C4', 'REPETIDOS');
        $activeWorksheet->setCellValue('D4', 'TOTAL');

        $activeWorksheet->setCellValue('A5', 'la pena');
        $activeWorksheet->setCellValue('B5', '10.000');
        $activeWorksheet->setCellValue('C5', '10.000');
        $activeWorksheet->setCellValue('D5', '10.000');

        $activeWorksheet->setCellValue('A6', 'TOTALES');
        $activeWorksheet->setCellValue('B6', '10.000');
        $activeWorksheet->setCellValue('C6', '10.000');
        $activeWorksheet->setCellValue('D6', '10.000');

        $activeWorksheet->setCellValue('A8', 'INFORME CONSOLIDADO DEL PROCESO DE INVENTARIO EN PERGAMUM');
        $spreadsheet->getActiveSheet()->mergeCells('A8:D3', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $activeWorksheet->setCellValue('A10', 'UBICACIÓN');
        $spreadsheet->getActiveSheet()->mergeCells('A10:A11', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $activeWorksheet->setCellValue('B10', 'PROCESO DE INVENTARIO');
        $activeWorksheet->setCellValue('B11', 'INVENTARIADOS');
        
        $activeWorksheet->setCellValue('D10', 'OTROS');
        $spreadsheet->getActiveSheet()->mergeCells('D10:F10', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $activeWorksheet->setCellValue('D11', 'BAJA ACUMULADA');
        $activeWorksheet->setCellValue('E11', 'OTRAS BIBLIOTECAS');
        $activeWorksheet->setCellValue('F11', 'TOTAL');

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