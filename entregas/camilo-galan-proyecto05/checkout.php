<?php

session_start();

require 'config/conection.php';


if(!isset($_SESSION['usuario_id'])){

    header("Location: login.php");
    exit();

}


$id_cliente=$_SESSION['usuario_id'];


// productos del carrito

$sqlProductos="

SELECT

p.nombre,
cd.cantidad,
cd.precio,
(cd.cantidad * cd.precio) subtotal


FROM carrito c


INNER JOIN carrito_detalle cd
ON c.id_carrito = cd.id_carrito


INNER JOIN producto p
ON cd.id_producto = p.id_producto


WHERE c.id_cliente=$id_cliente

";


$productos=$conn->query($sqlProductos);



$totalComida=0;


?>


<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Checkout</title>

<link rel="stylesheet" href="style.css">

</head>


<body>


<div class="cinema-container">


<h1>
Confirmar compra
</h1>



<h2>
🍿 Confitería
</h2>



<?php while($p=$productos->fetch_assoc()){ ?>


<div class="bloque-card">


<h3>
<?php echo $p['nombre']; ?>
</h3>


<p>

Cantidad:

<?php echo $p['cantidad']; ?>

</p>


<p>

$
<?php echo number_format($p['subtotal'],0,",","."); ?>

</p>



</div>



<?php


$totalComida += $p['subtotal'];


}

?>



<h2>

Total comida:

$

<?php echo number_format($totalComida,0,",","."); ?>

</h2>




<hr>




<h2>
🎬 Boletos
</h2>



<form action="process_purchase.php" method="POST">



<input
type="hidden"
name="comida"
value="<?php echo $totalComida; ?>"
>



<input
type="hidden"
name="confirmado"
value="1"
>



<button class="btn-neon">

Confirmar pago

</button>



</form>



</div>



</body>

</html>