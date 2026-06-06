<?php

session_start();

require 'config/conection.php';


if(!isset($_SESSION['usuario_id'])){

    header("Location: login.php");
    exit();

}


$id_cliente = $_SESSION['usuario_id'];

$id_producto = intval($_POST['id_producto']);



// Crear venta temporal

$sql = "
INSERT INTO venta
(
id_cliente,
total
)
VALUES
(
$id_cliente,
0
)
";


$conn->query($sql);


$id_venta = $conn->insert_id;



// Buscar producto

$sqlProducto = "
SELECT nombre, precio
FROM producto
WHERE id_producto=$id_producto
";


$p = $conn->query($sqlProducto)->fetch_assoc();



$conn->query("
INSERT INTO venta_detalle
(
id_venta,
tipo,
descripcion,
cantidad,
precio,
id_producto
)

VALUES

(
$id_venta,
'COMBO',
'".$p['nombre']."',
1,
".$p['precio'].",
$id_producto
)

");



// actualizar total

$conn->query("
UPDATE venta
SET total=".$p['precio']."
WHERE id_venta=$id_venta
");



header("Location: cart.php");

?>