<?php

session_start();

require 'config/conection.php';


if(!isset($_SESSION['usuario_id'])){

    header("Location: login.php");
    exit();

}


$id_cliente = $_SESSION['usuario_id'];



$sql = "

SELECT

vd.id_detalle,
vd.descripcion,
vd.cantidad,
vd.precio,
p.nombre,
p.imagen,

(vd.cantidad * vd.precio) AS subtotal

FROM venta_detalle vd

INNER JOIN producto p
ON vd.id_producto = p.id_producto

INNER JOIN venta v
ON vd.id_venta = v.id_venta

WHERE v.id_cliente=$id_cliente
AND vd.tipo='COMBO'

ORDER BY vd.id_detalle DESC

";


$productos = $conn->query($sql);



?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Carrito</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>


    <div class="cinema-container">


        <h1>
            Mi carrito
        </h1>



        <?php

$total = 0;


if($productos->num_rows > 0){


while($p=$productos->fetch_assoc()){


$total += $p['subtotal'];

?>


        <div class="bloque-card">


            <img src="<?php echo $p['imagen']; ?>" class="bloque-img">


            <div class="bloque-info">


                <h3>

                    <?php echo $p['nombre']; ?>

                </h3>


                <p>

                    Cantidad:

                    <?php echo $p['cantidad']; ?>

                </p>



                <p>

                    Subtotal:

                    $

                    <?php echo number_format($p['subtotal'],0,",","."); ?>

                </p>



                <form action="delete_cart.php" method="POST">


                    <input type="hidden" name="id_detalle" value="<?php echo $p['id_detalle']; ?>">


                    <button class="btn-comprar">

                        Eliminar

                    </button>


                </form>



            </div>


        </div>


        <?php

}

?>


        <h2>

            Total:

            $

            <?php echo number_format($total,0,",","."); ?>

        </h2>


        <a href="checkout.php" class="btn-neon">

            Continuar compra

        </a>



        <?php

}else{

?>


        <h2>
            Tu carrito está vacío
        </h2>


        <a href="snacks.php" class="btn-neon">

            Ver combos

        </a>


        <?php } ?>


    </div>


</body>

</html>