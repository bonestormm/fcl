<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Color\BIFF5;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Write\Dompdf;

class DocumentoController extends Controller
{

    public function crearDocumento(Request $request)
    {
        $datos = $request->input();
        $reglas = [
            'format_number' => 'required | numeric',
            'title' => array(
                'required',
                //'regex:/^[a-zA-Z áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'paper_event' => array(
                'required',
                //'regex:/^[a-zA-Z áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'year' => 'required | numeric',
            'authors' => array(
                'required',
                //'regex:/^[a-zA-Z, áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'univ_inst' => array(
                'required',
                //'regex:/^[a-zA-Z áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'reference' => array(
                'required',
                //'regex:/^[a-zA-Z0-9:.,\/\- áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'keyword' => array(
                'required',
                //'regex:/^[a-zA-Z, áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'resume' => array(
                'required',
                //'regex:/^[a-zA-Z, áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'tools' => array(
                'required',
                //'regex:/^[a-zA-Z, áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'comments' => array(
                'nullable',
                //'regex:/^[a-zA-Z, áéíóúÁÉÍÓÚñÑ]+$/'
            ),
            'rate' => 'required | numeric',
        ];

        $validador = Validator::make($datos, $reglas);

        if (!$validador->fails()) {
            //$this->pdfCrear($datos);
            $this->excelCrear($datos);
        } else {
            echo "Rellena todos los campos de forma correcta en caso de ser requeridos.";
        }

        //return view('formularios.done', array('datos' => $validatedData));
    }
    public function excelCrear($datos)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $styleDefaultArray = array(
            'font'  => array(
                'bold'  => false,
                'size'  => 13,
                'name'  => 'Helvetica'
            )
        );

        $styleArray = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 13,
                'name'  => 'Helvetica'
            )
        );

        $spreadsheet->getDefaultStyle()->applyFromArray($styleDefaultArray);
        $spreadsheet->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY);

        $spreadsheet->getActiveSheet()->mergeCells("A8:B8");
        $spreadsheet->getActiveSheet()->mergeCells("A9:B9");
        $spreadsheet->getActiveSheet()->mergeCells("A10:B10");
        $spreadsheet->getActiveSheet()->mergeCells("A11:B11");
        $spreadsheet->getActiveSheet()->mergeCells("A12:B12");
        $spreadsheet->getActiveSheet()->mergeCells("A13:B13");
        $spreadsheet->getActiveSheet()->mergeCells("A14:B14");
        $spreadsheet->getActiveSheet()->mergeCells("A15:B15");

        $spreadsheet->getActiveSheet()->getStyle('A1:A8')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1:A8')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A9:A10')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A10')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A12')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A12')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A12')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A12')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A14')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A14')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('A16')
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A16')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getStyle('B2:B7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $spreadsheet->getActiveSheet()->getStyle('B2:B7')->getAlignment()->setIndent(1);

        $spreadsheet->getActiveSheet()->getStyle('B11')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $spreadsheet->getActiveSheet()->getStyle('B11')->getAlignment()->setIndent(1);

        $spreadsheet->getActiveSheet()->getStyle('B13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $spreadsheet->getActiveSheet()->getStyle('B13')->getAlignment()->setIndent(1);

        $spreadsheet->getActiveSheet()->getStyle('B15')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $spreadsheet->getActiveSheet()->getStyle('B15')->getAlignment()->setIndent(1);

        $spreadsheet->getActiveSheet()->getStyle('B16')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A1', 'Nº: ' . $datos['format_number']);
        $sheet->setCellValue('A2', 'Título del Artículo');
        $sheet->setCellValue('B2', $datos['title']);
        $sheet->setCellValue('A3', 'Revista y/o evento');
        $sheet->setCellValue('B3', $datos['paper_event']);
        $sheet->setCellValue('A4', 'Año');
        $sheet->setCellValue('B4', $datos['year']);
        $sheet->setCellValue('A5', 'Autores');
        $sheet->setCellValue('B5', $datos['authors']);
        $sheet->setCellValue('A6', 'Universidad o institución');
        $sheet->setCellValue('B6', $datos['univ_inst']);
        $sheet->setCellValue('A7', 'Referencia APA');
        $sheet->setCellValue('B7', $datos['reference']);
        $sheet->setCellValue('A8', 'Palabras clave');
        $sheet->setCellValue('A9', $datos['keyword']);
        $sheet->setCellValue('A10', 'Resumen');
        $sheet->setCellValue('A11', $datos['resume']);
        $sheet->setCellValue('A12', 'Herramientas utilizadas');
        $sheet->setCellValue('A13', $datos['tools']);
        $sheet->setCellValue('A14', 'Comentarios');
        $sheet->setCellValue('A15', $datos['comments']);
        $sheet->setCellValue('A16', 'Valoración');
        $sheet->setCellValue('B16', $datos['rate']);

        $writer = new Xlsx($spreadsheet);
        $writer->save('fcl.xlsx');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf($spreadsheet);
        $writer->save("save.pdf");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('FORMATO_CONTROL_DE_LECTURA_' . $datos['format_number'] . '.pdf'));
        $writer->save('php://output');
    }

    public function pdfCrear($datos)
    {
        $spreadsheet = new Spreadsheet();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf($spreadsheet);
        $writer->save("05featuredemo.pdf");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('05featuredemo.pdf') . '"');
        $writer->save('php://output');
    }
}
