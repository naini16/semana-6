<?php
session_start();
$usuario = $_SESSION["usuario"];

if ($usuario == null) {
    header('location: ../');
}

require_once('../class/class.ventas.php');
$ventas = new Venta();

$action = '';
if (isset($_REQUEST['action']) && $_REQUEST['action'] != null) {
    $action = $_REQUEST['action'];
}

if (isset($_REQUEST['numero_venta'])) {
    $numero_venta = $_REQUEST['numero_venta'];
    $sql = "UPDATE ventas SET anulado=1 WHERE numero = '$numero_venta'";
    $sqlanula = $ventas->getVentaSQL($sql);
    if ($sqlanula) { ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Aviso!</strong> Venta anulada exitosamente!
        </div>
    <?php
    } else { ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Error!</strong> No se puede anular esta Venta
        </div>
    <?php
    }
}

if ($action == 'ajax') {
    $sTable = ' vventas ';
    $sWhere = " WHERE vventas.numero_venta > 0 ";

    if (isset($_REQUEST['q']) && $_REQUEST['q'] != null) {
        $q = $_REQUEST['q'];
        $sWhere .= " AND vventas.razon_social LIKE '%" . $q . "%' ";
    }

    $sWhere .= " ORDER BY vventas.numero_venta DESC";

    include '../php/pagination.php';

    $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
    $per_page = 5;
    $adjacents = 3;
    $offset = ($page - 1) * $per_page;
    $sql = 'select count(*) as numrows from ' . $sTable . $sWhere;
    $count_query = $ventas->getVentaSQL($sql);
    $row = $count_query->fetch_array();
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload = '../listaventas.php';

    $sql = 'select * from ' . $sTable . $sWhere . ' LIMIT ' . $offset . ',' . $per_page;
    $query = $ventas->getVentaSQL($sql);
    if ($numrows > 0) { ?>
        <br>
 
