<?php

session_start();

require 'config/conection.php';


if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}


$id_detalle = intval($_POST['id_detalle']);



$conn->query("
DELETE FROM venta_detalle
WHERE id_detalle=$id_detalle
");



header("Location: cart.php");

?>