<?php
include '../class/class.rubros.php';
$rubro = new Rubro();
$resultado = $rubro->getRubros();

// Llamada al autoload
require '../vendor/autoload.php';

// Carga la clase Spreadsheet de PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// Carga IOFactory y otras clases necesarias
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Estilos
$tableHead = [
    'font' => [
        'color' => ['rgb' => 'FFFFFF'],
        'bold' => true,
        'size' => 16,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'DC7633'],
    ],
];

$evenRow = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'EDBB99'],
    ],
];

$oddRow = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FBEEE6'],
    ],
];

// Crear objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configuración de fuente predeterminada
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);

// Encabezado
$spreadsheet->getActiveSheet()->setCellValue('B1', "Listado de Rubros");
$spreadsheet->getActiveSheet()->mergeCells("B1:C1");
$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Ajuste del ancho de las columnas
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);

// Encabezados de columnas
$spreadsheet->getActiveSheet()->setCellValue('B2', "Código")->setCellValue('C2', "Nombre");
$spreadsheet->getActiveSheet()->getStyle('B2:C2')->applyFromArray($tableHead);

// Cargar contenido
$row = 3;
foreach ($resultado as $registro) {
    $spreadsheet->getActiveSheet()->setCellValue('B' . $row, $registro['idRubro'])
                                     ->setCellValue('C' . $row, $registro['nombre']);

    // Estilo de filas pares e impares
    if ($row % 2 == 0) {
        $spreadsheet->getActiveSheet()->getStyle('B' . $row . ':C' . $row)->applyFromArray($evenRow);
    } else {
        $spreadsheet->getActiveSheet()->getStyle('B' . $row . ':C' . $row)->applyFromArray($oddRow);
    }
    $row++;
}

// Autofiltro
$firstRow = 2;
$lastRow = $row - 1;
$spreadsheet->getActiveSheet()->setAutoFilter("B" . $firstRow . ":C" . $lastRow);

// Encabezado para descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="listadorubros.xlsx"');

// Generación del archivo
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
?>
