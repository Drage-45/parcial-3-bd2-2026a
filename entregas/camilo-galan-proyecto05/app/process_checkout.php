<?php

session_start();

require 'config/conection.php';

if(!isset($_SESSION['usuario_id'])){
    die("Debe iniciar sesión");
}

$id_cliente = $_SESSION['usuario_id'];

$sql = "
SELECT *
FROM venta
WHERE id_cliente = $id_cliente
AND estado='PENDIENTE'
LIMIT 1
";

$result = $conn->query($sql);

if($result->num_rows == 0){
    die("No hay compras pendientes");
}

$venta = $result->fetch_assoc();

$id_venta = $venta['id_venta'];

$conn->query("
UPDATE venta
SET estado='PAGADA'
WHERE id_venta=$id_venta
");


header("Location:voucher.php?venta=$id_venta");
exit();

?>