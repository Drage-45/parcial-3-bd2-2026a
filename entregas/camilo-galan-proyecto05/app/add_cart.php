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

// Buscar venta pendiente

$sqlVenta = "
SELECT id_venta
FROM venta
WHERE id_cliente = $id_cliente
AND estado = 'PENDIENTE'
LIMIT 1
";

$resVenta = $conn->query($sqlVenta);

if($resVenta->num_rows > 0){

    $venta = $resVenta->fetch_assoc();

    $id_venta = $venta['id_venta'];

}else{

    $conn->query("
    INSERT INTO venta
    (
        id_cliente,
        total,
        estado
    )
    VALUES
    (
        $id_cliente,
        0,
        'PENDIENTE'
    )
    ");

    $id_venta = $conn->insert_id;
}



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