<?php
session_start();
$sesion = $_SESSION['usuario'];

if (isset($_POST['idConcepto'])) { $idConcepto = $_POST['idConcepto']; }
if (isset($_POST['concepto'])) { $concepto = $_POST['concepto']; }
if (isset($_POST['cantidad'])) { $cantidad = $_POST['cantidad']; }
if (isset($_POST['unitario'])) { $unitario = $_POST['unitario']; }

require_once '../class/class.detalleventas.php';
$JSONdetalle = new DetalleVenta();

$file = 'tmpdetallesventas' . $sesion . '.json';
$exist = is_file($file);

if (!empty($idConcepto) and !empty($cantidad) and !empty($unitario)) {
    if ($exist) {
        $arrDetalles = $JSONdetalle->getDetalles($sesion);
        $ultimoID = 0;
        foreach ($arrDetalles as $detalle) {
            $ultimoID++;
        }
        $ultimoID = $ultimoID + 1;
    } else {
        $ultimoID = 1;
    }

    $arregloDetalles = array(
        'idTmpDetalle' => $ultimoID,
        'idConcepto' => $idConcepto,
        'concepto' => $concepto,
        'cantidad' => $cantidad,
        'unitario' => $unitario,
        'sesion' => $sesion
    );

    if ($exist) {
        $JSONdetalle->createDetalleExist($arregloDetalles, $sesion);
    } else {
        $JSONdetalle->createDetalleNotExist($arregloDetalles, $sesion);
    }
}

if (isset($_GET['id'])) {
    $idtmpDetalle = intval($_GET['id']);
    $JSONdetalle->deleteDetalle($idtmpDetalle, $sesion);
}
?>
<table class="table">
    <tr>
        <th class='text-center'>CODIGO</th>
        <th class='text-center'>CANTIDAD</th>
        <th>DESCRIPCION</th>
        <th class='text-right'>UNITARIO</th>
        <th class='text-right'>TOTAL</th>
        <th></th>
    </tr>
    <?php
    $sumador_total = 0;
    $total_detalle = 0;

    $arrDetalles = $JSONdetalle->getDetalles($sesion);
    foreach ($arrDetalles as $row) {
        $id_tmp = $row["idTmpDetalle"];
        $idConcepto = $row['idConcepto'];
        $cantidad = $row['cantidad'];
        $descripcion = $row['concepto'];
        $precio_venta = $row['unitario'];
        $precio_venta_f = number_format($precio_venta, 2, ',', '.'); // Formateo variables
        $precio_total = $precio_venta * $cantidad;
        $precio_total_f = number_format($precio_total, 2, ',', '.'); // Precio total formateado
        $sumador_total += $precio_venta; // Sumador
        $total_detalle += $p
