<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../class/class.rubros.php';
$rubro = new Rubro();

if (isset($_POST)) {
    $nombre = $_POST['nombre'];
    $rubro->add($nombre);
} else {
    echo '<script>alert("Error, no se guardó registro!");</script>';
}

header('location: ../listarubros.php');
?>
