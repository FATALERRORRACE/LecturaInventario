<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
class XlssExport
{

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
            $activeWorksheet->setCellValue([1, $xAngle], $value->C_Barras);
            $activeWorksheet->setCellValue([2, $xAngle], $value->Usuario);
            $activeWorksheet->setCellValue([3, $xAngle], $value->Situacion);
            $activeWorksheet->setCellValue([4, $xAngle], $value->Comentario);
            $activeWorksheet->setCellValue([5, $xAngle], $value->Fecha);
            $activeWorksheet->setCellValue([6, $xAngle], $value->Estado);
            $xAngle++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $nombre . " ". $date->format('Y-m-d h:s:i') . '.xlsx"');
        $writer->save("php://output");
    }
}
