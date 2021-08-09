<?php

namespace App\assets;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\GridLines;
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ArrayObject;


class WorkXlsx
{
    public $sheet;
    public $workBookName;

    public function __construct($workBookName, $sheetsNames)
    {
        $this->sheet = new Spreadsheet();
        $this->workBookName = $workBookName . '.xlsx';
        $this->setSheets($sheetsNames);
    }
    private function setSheets($sheetsNames)
    {

        for ($i = 0; $i < count($sheetsNames); $i++) {
            if ($i == 0) {
                $this->sheet->getActiveSheet()->setTitle($sheetsNames[0]);
            } else {
                $this->sheet->createSheet();
                $this->sheet->setActiveSheetIndex($i);
                $this->sheet->getActiveSheet()->setTitle($sheetsNames[$i]);
            }
        }
    }
    public function getSheet($index)
    {
        $this->sheet->setActiveSheetIndex($index);
        $currentSheet = $this->sheet->getActiveSheet();
        return $currentSheet;
    }
    public function estadisticasConsumo($data, $jsonkeys, $mainheader)
    {
        $arrayData = $data;
        $dataBook = [];
        $currentDay = NUll;
        $frontier = NULL;
        $key = NULL;
        $header = ['No. Cuenta Contrato', 'Fecha/Hora', 'Hora 0', 'Hora 1', 'Hora 2', 'Hora 3', 'Hora 4', 'Hora 5', 'Hora 6', 'Hora 7', 'Hora 8', 'Hora 9', 'Hora 10', 'Hora 11', 'Hora 12', 'Hora 13', 'Hora 14', 'Hora 15', 'Hora 16', 'Hora 17', 'Hora 18', 'Hora 19', ' Hora 20', ' Hora 21', 'Hora 22', 'Hora 23'];

        for ($i = 0; $i < count($jsonkeys); $i++) {
            $current_mainH = [$mainheader[$i]];
            array_push($dataBook, [$current_mainH, $header]);
        }
        $maxrowStyle = 2;

        $rowSheets = array();
        for ($i = 0; $i < count($jsonkeys); $i++) {
            array_push($rowSheets, array());
        }

        for ($i = 0; $i < count($arrayData); $i++) {
            if ($key == NULL) {
                //cuando empieza asignar a currentday a la primera fecha
                //y agregar esta fecha para la fila cero primera columna
                $currentDay = str_replace("-", "/", $arrayData[$i]['Fecha']);
                $frontier = $arrayData[$i]['NoFrontera'];
                $key = $frontier . '_' . $currentDay;

                for ($k = 0; $k < count($jsonkeys); $k++) {
                    array_push($rowSheets[$k], $frontier);
                    array_push($rowSheets[$k], $currentDay);
                }

                $maxrowStyle = $maxrowStyle + 1;
            }
            $newKey = $arrayData[$i]['NoFrontera'] . '_' . str_replace("-", "/", $arrayData[$i]['Fecha']);
            if ($key != $newKey) {

                //añade la fila a la matriz (hoja) correspondiente
                for ($k = 0; $k < count($jsonkeys); $k++) {
                    $rowcopy = new ArrayObject($rowSheets[$k]);
                    array_push($dataBook[$k], $rowcopy);
                }

                //se añade la nueva fecha ala nueva fila y se cambia el valor de currentday frontier y key
                $currentDay = str_replace("-", "/", $arrayData[$i]['Fecha']);
                $frontier = $arrayData[$i]['NoFrontera'];
                $key = $frontier . '_' . $currentDay;

                // coloca el valor inicial frontera y fecha para la sigiente fila
                for ($k = 0; $k < count($jsonkeys); $k++) {
                    $rowSheets[$k] = [$frontier, $currentDay];
                }

                $maxrowStyle = $maxrowStyle + 1;
            }
            // mientras no cambia de fila agrege el valor ala hoja
            //$jsonkeys es el array de referencia para buscar la llave, viene por parametro
            for ($k = 0; $k < count($jsonkeys); $k++) {
                $jsonKey = $jsonkeys[$k];
                array_push($rowSheets[$k], $arrayData[$i][$jsonKey]);
            }
        }

        for ($k = 0; $k < count($jsonkeys); $k++) {
            // añade la ultima fila y escribe la hoja
            array_push($dataBook[$k], $rowSheets[$k]);
            $this->writeSheet($k, $dataBook[$k], $start = 'A1');
        }

        $styles = $this->setBoringStyles('0555FA');
        $styleshead = $styles[0];
        $stylesbody = $styles[1];
        $stylesright = $this->setBoringStyles('FFFFFF', '000000')[0];
        for ($i = 0; $i < count($jsonkeys); $i++) {
            $this->getSheet($i)->getStyle('B2:B' . (string)$maxrowStyle)->applyFromArray($stylesright);
            $this->getSheet($i)->getStyle('A1:Z2')->applyFromArray($styleshead);
            $this->getSheet($i)->getStyle('B3:Z' . (string)$maxrowStyle)->applyFromArray($stylesbody);
            $this->getSheet($i)->getStyle('A3:Z' . (string)$maxrowStyle)->applyFromArray($stylesbody);
            $this->getSheet($i)->getColumnDimension('A')->setWidth(20);
            $this->getSheet($i)->getColumnDimension('B')->setWidth(15);
            $this->getSheet($i)->getRowDimension(2)->setRowHeight(20);
            $this->getSheet($i)->getStyle('B2:B' . (string)$maxrowStyle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $this->getSheet($i)->getStyle('A2:A' . (string)$maxrowStyle)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $this->getSheet($i)->mergeCells("A1:Z1");
        }

        return $this->writeBook();
    }    

    public function desempenoContrato($fechas_rango, $json, $headerOr)
    {
        $headers = [
            "Mes",
            "Consumos(KWh)",
            "IPP",
            "Precio de bolsa TXR($/KWh)",
            "Precio contratado G+C($/KWh)",
            "Cargos regulados($/KWh)",
            "Contribución ($/KWh)",
            "Tarifa media($/KWh)",
            $headerOr."($/KWh)"
        ];

        $data = array_values($json);
        $this->writeSheet(0, $headers, 'A26');
        $this->writeSheet(0, $data, 'A27');

        $dataLength = sizeof(array_keys($data)) + 1 + (25);

        $sheetName = $this->getSheet(0)->getTitle();
        $this->getSheet(0)->getStyle("A27:I" . $dataLength)->getNumberFormat()->setFormatCode('0.00');

        $styles = $this->setBoringStyles('55BE5A');
        $styleshead = $styles[0];
        $stylesbody = $styles[1];

        $this->getSheet(0)->getStyle('A27:A' . (string)$dataLength)->applyFromArray($stylesbody);
        $this->getSheet(0)->getStyle('A27:A' . (string)$dataLength)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $this->getSheet(0)->getStyle('A26:I26')->applyFromArray($styleshead);
        $this->getSheet(0)->getStyle('B27:I' . (string)$dataLength)->applyFromArray($stylesbody);

        $this->getSheet(0)->getColumnDimension('A')->setWidth(25);
        $this->getSheet(0)->getColumnDimension('B')->setWidth(18);
        $this->getSheet(0)->getColumnDimension('C')->setWidth(18);
        $this->getSheet(0)->getColumnDimension('D')->setWidth(18);
        $this->getSheet(0)->getColumnDimension('E')->setWidth(18);
        $this->getSheet(0)->getColumnDimension('F')->setWidth(18);
        $this->getSheet(0)->getColumnDimension('G')->setWidth(18);
        $this->getSheet(0)->getColumnDimension('H')->setWidth(18);
        $this->getSheet(0)->getColumnDimension('I')->setWidth(30);
        $this->getSheet(0)->getRowDimension(26)->setRowHeight(35);
        $this->getSheet(0)->getStyle('A26:I26' . (string)$dataLength)->getAlignment()->setWrapText(true);

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetName . '!$D$26', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetName . '!$E$26', null, 1),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetName . '!$I$26', null, 1),
        ];

        // set x axis tick values
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $sheetName . '!$A$27:$A$' . $dataLength, null, 4),
        ];

        // set data series values
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetName . '!$D$27:$D$' . $dataLength, null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetName . '!$E$27:$E$' . $dataLength, null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $sheetName . '!$I$27:$I$' . $dataLength, null, 4),
        ];

        // set the series
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType   line
            null,   //pie is null
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        // Set the series in the plot area
        $layout1 = new Layout();
        $plotArea = new PlotArea($layout1, [$series]);

        // Set the chart legend
        $legend = new Legend(Legend::POSITION_BOTTOM, null, false);

        $title = new Title('Informe de Desempeño de Contrato ' . $fechas_rango);
        $yaxis = new Axis();
        $xaxis = new Axis();
        $yaxis->setAxisOptionsProperties('low', null, null, null, null, null, null, null);
        $xaxis->setAxisOptionsProperties('low', null, null, null, null, null, 0, 0, null, null);
        $yAxisLabel = new Title('$/KWh');
        $grid = new GridLines();
        $grid->setLineColorProperties("white");

        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            'gap', // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel, // yAxisLabel
            $yaxis,
            $xaxis,
            $grid
        );

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('B3');
        $chart->setBottomRightPosition('I23');

        // Add the chart to the worksheet
        $this->getSheet(0)->addChart($chart);
        $filename = 'Desempeño de Contratos';
        $fileType = 'Xlsx';
        return $this->writeBook();
    }

    public function descargaUsuario($json)
    {
        $data = $json;

        $data = array_values($data);
        $header = ['Nombres', 'Apellidos', 'Empresa', 'Cuenta', 'Nombre de usuario', 'Perfil', 'Estado'];

        $this->writeSheet(0, $header, 'A1');
        $this->writeSheet(0, $data, 'A2');
        $dataLength = sizeof(array_keys($data)) + 1;

        $styles = $this->setBoringStyles('0555FA');
        $styleshead = $styles[0];
        $stylesbody = $styles[1];
        $stylesright = $this->setBoringStyles('FFFFFF', '000000')[0];
        $this->getSheet(0)->getStyle('A1:A' . (string)$dataLength)->applyFromArray($stylesright);
        $this->getSheet(0)->getStyle('A1:G1')->applyFromArray($styleshead);
        $this->getSheet(0)->getStyle('A2:G' . (string)$dataLength)->applyFromArray($stylesbody);
        $this->getSheet(0)->getStyle('A2:G' . (string)$dataLength)->applyFromArray($stylesbody);

        $this->getSheet(0)->getStyle('A1:G1')->applyFromArray($styleshead);
        $this->getSheet(0)->getStyle('A2:G' . (string)$dataLength)->applyFromArray($stylesbody);

        $this->getSheet(0)->getStyle('A2:G' . (string)$dataLength)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $this->getSheet(0)->getStyle("A2:A" . (string)$dataLength)->getFont()->setBold(false);

        $this->getSheet(0)->getColumnDimension('A')->setWidth(27);
        $this->getSheet(0)->getColumnDimension('B')->setWidth(27);
        $this->getSheet(0)->getColumnDimension('C')->setWidth(39);
        $this->getSheet(0)->getColumnDimension('D')->setWidth(16);
        $this->getSheet(0)->getColumnDimension('E')->setWidth(39);
        $this->getSheet(0)->getColumnDimension('F')->setWidth(16);
        $this->getSheet(0)->getColumnDimension('G')->setWidth(16);

        return $this->writeBook();
    }

    public function setBoringStyles($fillHeaderColor =  '0555FA', $fontColor = 'FFFFFF')
    {
        $styleHeaders = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('rgb' => $fillHeaderColor)
            ],
            'font' => [
                'bold'  => true,
                'color' => array('rgb' => $fontColor),
                'size'  => 11,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => '000000'),
                ],
            ],
        ];
        $stylebody = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('rgb' => '000000'),
                ],
            ],
        ];
        return [$styleHeaders, $stylebody];
    }

    public function writeSheet($index, $data, $start = 'A1')
    {
        $this->getSheet($index)->fromArray(
            $data,  // The data to set
            null,        // Array values with this value will not be set
            $start,         // Top left coordinate of the worksheet range where
            true              //    we want to set these values (default is A1)
        );
    }
    public function writeBook()
    {
        $writer = new Xlsx($this->sheet);
        $writer->setIncludeCharts(true);
        $writer->save($this->workBookName);
        $b64 = base64_encode(file_get_contents(public_path() . "/{$this->workBookName}"));
        unlink($this->workBookName); // deletes the temporary file
        return $b64;
    }
}