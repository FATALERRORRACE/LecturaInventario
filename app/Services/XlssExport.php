<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class XlssExport
{

    /**
     * Bootstrap services.
     */
    public function execute($data, $nombre)
    {
        $date = new \DateTime();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->getColumnDimension('A')->setWidth(15);
        $activeWorksheet->getColumnDimension('B')->setWidth(12);
        $activeWorksheet->getColumnDimension('C')->setWidth(15);
        $activeWorksheet->getColumnDimension('D')->setWidth(12);
        $activeWorksheet->getColumnDimension('E')->setWidth(12);
        $activeWorksheet->getColumnDimension('F')->setWidth(12);
        $activeWorksheet->getStyle("A1:F1")->getFont()->setBold(true);
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
        return $this->returnDonwloadableFile($spreadsheet, $nombre, $date->format('Y-m-d h:s:i'));
    }

    /**
     * Bootstrap services.
     */
    public function executeSecondReport($table, $posInventario)
    {
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

        if ($posInventario)
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

        if ($posInventario) {
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
            ->whereIn(
                'master.Localizacion',
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
            ->whereNotIn(
                'master.Localizacion',
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
            ->select('master.Proceso', 'master.Localizacion',  DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", '<>', '')
            ->groupBy('master.Localizacion', 'master.Proceso')
            ->orderBy('master.Localizacion')
            ->get()->toArray();

        $consolidado['InventariadoSuma'] = DB::table($tableName)
            ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", '<>', '')
            ->first();

        $consolidado['Faltante'] = DB::table($tableName)
            ->select('master.Proceso', 'master.Localizacion', DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", '')
            ->whereNotIn('master.Proceso', ['Nivel Central', 'Material en tránsito'])
            ->groupBy('master.Localizacion', 'master.Proceso')
            ->orderBy('master.Localizacion')
            ->get()->toArray();

        $consolidado['FaltanteSuma'] = DB::table($tableName)
            ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", '')
            ->whereNotIn('master.Proceso', ['Nivel Central', 'Material en tránsito'])
            ->first();

        $consolidado['FaltanteNivelCentral'] = DB::table($tableName)
            ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", '')
            ->where('master.Proceso', 'Nivel Central')
            ->first();

        $consolidado['FaltanteTransito'] = DB::table($tableName)
            ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->where("{$tableName}.Estado", '')
            ->where('master.Proceso', 'Material en tránsito')
            ->first();

        $consolidado['sumaTotal'] = DB::table($tableName)
            ->select(DB::raw("COUNT($tableName.id) as total"), DB::raw("SUM(master.Precio) as totalprice"))
            ->join('master', 'master.C_Barras', '=', "{$tableName}.C_Barras")
            ->first();

        $this->createConsilidatedReport($spreadsheet, $table, $position++, $otros, $consolidado);

        $activeWorksheet->getStyle("A1:O1")->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('DDE5ED');

        return $this->returnDonwloadableFile($spreadsheet, $table['nombre'], $date->format('Y-m-d h:s:i'));
    }

    /**
     * Bootstrap services.
     */
    public function setNewPageAndData(&$spreadsheet, $data, $pageTitle, $indexPage)
    {
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
        if ($pageTitle == 'NO ENCONTRADOS')
            $header = ['C_Barras'];

        foreach ($header as $key => $value)
            $spreadsheet->getActiveSheet()->setCellValue("{$range[$key]}1", $value);

        $xAngle = 2;
        foreach ($data as $key => $value) {
            $spreadsheet->getActiveSheet()->fromArray([(array)$value], NULL, "A{$xAngle}");
            $xAngle++;
        }
    }

    public function createConsilidatedReport($spreadsheet, $table, $position, $dataUbicaciones, $consolidado)
    {
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

        $spreadsheet->getActiveSheet()->setCellValue('A1', 'INFORME CONSOLIDADO DEL PROCESO DE INVENTARIO EN PERGAMUM');
        $spreadsheet->getActiveSheet()->mergeCells('A1:D1', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, 'A1:D1');

        $spreadsheet->getActiveSheet()->setCellValue('A3', 'UBICACIÓN');
        $spreadsheet->getActiveSheet()->mergeCells('A3:A4', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, 'A3:A4');


        $spreadsheet->getActiveSheet()->setCellValue('B3', 'PROCESO DE INVENTARIO');
        $this->addHeaderStyle($spreadsheet, 'B3');
        $spreadsheet->getActiveSheet()->setCellValue('B4', 'INVENTARIADOS');
        $this->addHeaderStyle($spreadsheet, "B4");
        $sumtotal = 0;
        foreach ($dataUbicaciones['ubicaciones'] as $key => $value) {
            $spreadsheet->getActiveSheet()->setCellValue("A" . ($key + 5), $value->Localizacion);
            $spreadsheet->getActiveSheet()->setCellValue("B" . ($key + 5), $value->total);
            $sumtotal += $value->total;
        }
        $y = ($key ?? 1) + 5;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'TOTALES');
        $this->addHeaderStyle($spreadsheet, "A{$y}");
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", $sumtotal);
        $this->addHeaderStyle($spreadsheet, "B{$y}");
        $spreadsheet->getActiveSheet()->getStyle("A3:B{$y}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $spreadsheet->getActiveSheet()->setCellValue('D3', 'OTROS');
        $this->addHeaderStyle($spreadsheet, 'D3');
        $spreadsheet->getActiveSheet()->mergeCells('D3:F3', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue('D4', 'BAJA ACUMULADA');
        $this->addHeaderStyle($spreadsheet, 'D4');
        $spreadsheet->getActiveSheet()->setCellValue('E4', 'OTRAS BIBLIOTECAS');
        $this->addHeaderStyle($spreadsheet, 'E4');
        $spreadsheet->getActiveSheet()->setCellValue('F4', 'TOTAL');
        $this->addHeaderStyle($spreadsheet, 'F4');
        $spreadsheet->getActiveSheet()->setCellValue('D5', $dataUbicaciones['Baja']->total);
        $spreadsheet->getActiveSheet()->setCellValue('E5', $dataUbicaciones['otrasBibCount']);
        $spreadsheet->getActiveSheet()->setCellValue('F5', ((int)$dataUbicaciones['Baja']->total + (int)$dataUbicaciones['otrasBibCount']));
        $spreadsheet->getActiveSheet()->setCellValue('D6', $dataUbicaciones['Baja']->total);
        $spreadsheet->getActiveSheet()->setCellValue('E6', $dataUbicaciones['otrasBibCount']);
        $spreadsheet->getActiveSheet()->setCellValue('F6', ((int)$dataUbicaciones['Baja']->total + (int)$dataUbicaciones['otrasBibCount']));
        $spreadsheet->getActiveSheet()->getStyle("D3:F5")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        $y += 2;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'INFORME CONSOLIDADO DE NO INVENTARIADOS Y TOTAL DE COLECCIÓN');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:D{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $y += 2;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'DESCRIPCIÓN');
        $tableStart = "A{$y}";
        $this->addHeaderStyle($spreadsheet, "A{$y}");
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:A" . ($y + 1), \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'RESUMEN DE INVENTARIO EN PERGAMUM');
        $spreadsheet->getActiveSheet()->mergeCells("B{$y}:I{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "B{$y}:I{$y}");
        $y += 1;
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
    public function returnDonwloadableFile($spreadsheet, $nombre, $date)
    {
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $nombre . " " . $date . '.xlsx"');
        $writer->save("php://output");
    }

    public function createSheetConsolidate($spreadsheet, $consolidado, $y, $tableStart)
    {
        $initialMergeUbication = $y;
        foreach ($consolidado['Inventariado'] as $key => $value) {
            $y += 1;
            if ($key == 0) {
                $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Inventariado');
                $spreadsheet->getActiveSheet()->setCellValue("E{$y}", $consolidado['InventariadoSuma']->total);
                $spreadsheet->getActiveSheet()->setCellValue("G{$y}",  '$' . number_format($consolidado['InventariadoSuma']->totalprice, 2));
                $spreadsheet->getActiveSheet()->setCellValue(
                    "I{$y}",
                    number_format(
                        $consolidado['InventariadoSuma']->total ? ($consolidado['InventariadoSuma']->total / $consolidado['sumaTotal']->total) * 100 : 0,
                        2
                    ) . ' %'
                );
                $initialMergeUbication = $y;
            }
            $spreadsheet->getActiveSheet()->setCellValue("B{$y}", $value->Localizacion);
            $spreadsheet->getActiveSheet()->setCellValue("C{$y}", $value->Proceso);
            $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $value->total);
            $spreadsheet->getActiveSheet()->setCellValue("F{$y}",  '$' . number_format($value->totalprice, 2));
            $spreadsheet->getActiveSheet()->setCellValue(
                "H{$y}",
                number_format(
                    $value->total ? ($value->total / $consolidado['sumaTotal']->total) * 100 : 0,
                    2
                ) . ' %'
            );
        }

        $spreadsheet->getActiveSheet()->mergeCells("A{$initialMergeUbication}:A$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->mergeCells("E{$initialMergeUbication}:E$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->mergeCells("G{$initialMergeUbication}:G$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->mergeCells("I{$initialMergeUbication}:I$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $initialMergeUbication = $y;
        foreach ($consolidado['Faltante'] as $key => $value) {
            $y += 1;
            if ($key == 0) {
                $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Faltante');
                $spreadsheet->getActiveSheet()->setCellValue("E{$y}", $consolidado['FaltanteSuma']->total);
                $spreadsheet->getActiveSheet()->setCellValue("G{$y}",  '$' . number_format($consolidado['FaltanteSuma']->totalprice, 2));
                $spreadsheet->getActiveSheet()->setCellValue(
                    "I{$y}",
                    number_format(
                        $consolidado['FaltanteSuma']->total ? ($consolidado['FaltanteSuma']->total / $consolidado['sumaTotal']->total) * 100 : 0,
                        2
                    ) . ' %'
                );
                $initialMergeUbication = $y;
            }
            $spreadsheet->getActiveSheet()->setCellValue("B{$y}", $value->Localizacion);
            $spreadsheet->getActiveSheet()->setCellValue("C{$y}", $value->Proceso);
            $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $value->total);
            $spreadsheet->getActiveSheet()->setCellValue("F{$y}",  '$' . number_format($value->totalprice, 2));
            $spreadsheet->getActiveSheet()->setCellValue(
                "H{$y}",
                number_format(
                    $value->total ? ($value->total / $consolidado['sumaTotal']->total) * 100 : 0,
                    2
                ) . ' %'
            );
        }

        $spreadsheet->getActiveSheet()->mergeCells("A{$initialMergeUbication}:A$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->mergeCells("E{$initialMergeUbication}:E$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->mergeCells("G{$initialMergeUbication}:G$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->mergeCells("I{$initialMergeUbication}:I$y", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);

        $y += 1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Faltante');
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'Nivel Central');
        $spreadsheet->getActiveSheet()->mergeCells("B{$y}:C{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $consolidado['FaltanteNivelCentral']->total);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($consolidado['FaltanteNivelCentral']->totalprice, 2));
        $spreadsheet->getActiveSheet()->setCellValue(
            "H{$y}",
            number_format(
                $consolidado['FaltanteNivelCentral']->total ? ($consolidado['FaltanteNivelCentral']->total / $consolidado['sumaTotal']->total) * 100 : 0,
                2
            ) . ' %' . ' %'
        );
        $y += 1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Faltante');
        $spreadsheet->getActiveSheet()->setCellValue("B{$y}", 'En Tránsito');
        $spreadsheet->getActiveSheet()->mergeCells("B{$y}:C{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $consolidado['FaltanteTransito']->total);
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($consolidado['FaltanteTransito']->totalprice, 2));
        $spreadsheet->getActiveSheet()->setCellValue(
            "H{$y}",
            number_format(
                $consolidado['FaltanteTransito']->total ? ($consolidado['FaltanteTransito']->total / $consolidado['sumaTotal']->total) * 100 : 0,
                2
            ) . ' %' . ' %'
        );
        $y += 1;
        $spreadsheet->getActiveSheet()->setCellValue("A{$y}", 'Colección Total');
        $spreadsheet->getActiveSheet()->mergeCells("A{$y}:C{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "A{$y}:C{$y}");
        $spreadsheet->getActiveSheet()->setCellValue("D{$y}", $consolidado['sumaTotal']->total);
        $spreadsheet->getActiveSheet()->mergeCells("D{$y}:E{$y}", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::MERGE_CELL_CONTENT_MERGE);
        $this->addHeaderStyle($spreadsheet, "D{$y}:E{$y}");
        $spreadsheet->getActiveSheet()->setCellValue("F{$y}", '$' . number_format($consolidado['sumaTotal']->totalprice, 2));
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

    public function addHeaderStyle($spreadsheet, $cell)
    {
        $spreadsheet->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('666699');
        $spreadsheet->getActiveSheet()->getStyle($cell)->getFont()->getColor()->setARGB('ffffff');
    }
}
