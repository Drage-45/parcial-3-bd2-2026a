<?php

session_start();

require 'config/conection.php';


if(!isset($_SESSION['usuario_id'])){
    die("Debe iniciar sesión");
}


if(!isset($_POST['id_funcion']) || !isset($_POST['asientos'])){
    die("Datos incompletos");
}


$id_cliente = $_SESSION['usuario_id'];

$totalventa = 0;

$id_funcion = intval($_POST['id_funcion']);

$asientos = explode(",", $_POST['asientos']);



/*
 Buscar precio
*/

$sqlPrecio="
SELECT precio
FROM funcion
WHERE id_funcion=$id_funcion
";


$resultado=$conn->query($sqlPrecio);

$funcion=$resultado->fetch_assoc();


$precio=$funcion['precio'];

$conn->query("
INSERT INTO venta
(id_cliente,total)
VALUES
($id_cliente,0)
");


$id_venta = $conn->insert_id;


$idsBoletos = [];


foreach($asientos as $id_funcion_butaca){


    $id_funcion_butaca = intval($id_funcion_butaca);



    // validar disponibilidad

    $check="
    SELECT estado
    FROM funcion_butaca
    WHERE id_funcion_butaca=$id_funcion_butaca
    ";


    $estado=$conn->query($check)->fetch_assoc();



    if($estado['estado']!="Disponible"){

        die("Un asiento ya fue ocupado");

    }

    


    // crear boleto

    $sql="
    INSERT INTO boleto
    (
    fecha_venta,
    precio,
    id_cliente,
    id_funcion_butaca,
    id_venta
    )
    VALUES
    (
    NOW(),
    $precio,
    $id_cliente,
    $id_funcion_butaca,
    $id_venta
    )
    ";



    if(!$conn->query($sql)){
        throw new Exception($conn->error);
    }



    $idBoletoCreado = $conn->insert_id;


    $idsBoletos[] = $idBoletoCreado;



    // sumar venta

    $totalVenta += $precio;



    // guardar detalle

    $conn->query("
    INSERT INTO venta_detalle
    (
    id_venta,
    tipo,
    descripcion,
    cantidad,
    precio
    )
    VALUES
    (
    $id_venta,
    'BOLETO',
    'Asiento cine',
    1,
    $precio
    )
    ");



    // bloquear asiento

    $conn->query("
    UPDATE funcion_butaca
    SET estado='Ocupado'
    WHERE id_funcion_butaca=$id_funcion_butaca
    ");



}



// actualizar total final

$conn->query("
UPDATE venta
SET total=$totalVenta
WHERE id_venta=$id_venta
");



$idBoleto = implode(",", $idsBoletos);



echo "
<script>
alert('Compra realizada correctamente');
window.location='voucher.php?boletos=$idBoleto&venta=$id_venta';
</script>";

?>