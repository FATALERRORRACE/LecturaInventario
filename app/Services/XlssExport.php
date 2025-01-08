<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        return $this->returnDonwloadableFile($spreadsheet, $nombre, $date->format('Y-m-d h:s:i') );
    }

    /**
     * Bootstrap services.
     */
    public function executeSecondReport($data, $nombre){
        $date = new \DateTime();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
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
        foreach ($header as $key => $value) 
            $activeWorksheet->setCellValue( "{$range[$key]}1" , $value);

        $xAngle = 2;
        foreach ($data as $key => $value) {
            $activeWorksheet->fromArray([(array)$value], NULL, "A{$xAngle}");
            $xAngle++;
        }
        $activeWorksheet->getStyle("A1:O1")->getFont()->setBold( true );
        $activeWorksheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDE5ED');
        
        return $this->returnDonwloadableFile($spreadsheet, $nombre, $date->format('Y-m-d h:s:i') ); 
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