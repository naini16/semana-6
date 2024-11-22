<?php
error_reporting(E_ALL ^ E_WARNING);
ob_start();

require_once '../class/class.ventas.php';
$factura = new Venta();

require_once '../class/class.detalleventas.php';
$JSONdetalle = new DetalleVenta();

session_start();
$sesion = $_SESSION['usuario'];
$arrDetalles = $JSONdetalle->getDetalles($sesion);
$count = 0;

foreach ($arrDetalles as $detalle) { $count++; }

if ($count == 0) {
    echo "<script>alert('No hay articulos agregados a la venta')</script>";
    echo "<script>window.close();</script>";
    exit;
}

require_once dirname(__FILE__) . '/../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;

// Variables por REQUEST
$idcliente = $_REQUEST['idcliente'];
$fecha = $_REQUEST['fecha'];
$condicion = $_REQUEST['condicion'];
$idusuario = $_SESSION['idUsuario'];

// Obtener el HTML
include(dirname('__FILE__').'/documentos/venta_html.php');
$content = ob_get_clean();
//ob_end_clean();

try {
    // Inicializar HTML2PDF
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(0, 0, 0, 0));
    // Mostrar la página completa
    $html2pdf->pdf->SetDisplayMode('fullpage');
    // Convertir
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    // Enviar el PDF
    $html2pdf->Output('facturadeventa-' . $sesion . '.pdf');
} catch(Html2pdfException $e) {
    echo $e;
    exit;
}
?>
