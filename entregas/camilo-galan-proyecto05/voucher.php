<?php

session_start();

require 'config/conection.php';



if(!isset($_GET['venta'])){
    die("Venta no encontrada");
}

$id_venta = intval($_GET['venta']);



$sql="
SELECT

b.id_boleto,
b.fecha_venta,
b.precio,

c.nombre,
c.correo,

p.titulo,

f.fecha,
f.hora,

bu.fila,
bu.numero

FROM boleto b

INNER JOIN cliente c
ON b.id_cliente=c.id_cliente

INNER JOIN funcion_butaca fb
ON b.id_funcion_butaca=fb.id_funcion_butaca

INNER JOIN funcion f
ON fb.id_funcion=f.id_funcion

INNER JOIN pelicula p
ON f.id_pelicula=p.id_pelicula

INNER JOIN butaca bu
ON fb.id_butaca=bu.id_butaca

WHERE b.id_venta=$id_venta
";

$sqlTotal="
SELECT total
FROM venta
WHERE id_venta=$id_venta
";

$venta=$conn->query($sqlTotal)->fetch_assoc();

$sqlDetalle="
SELECT *
FROM venta_detalle
WHERE id_venta=$id_venta
AND tipo='COMBO'
";

$detalles=$conn->query($sqlDetalle);

$result=$conn->query($sql);

?>


<!DOCTYPE html>

<html>

<head>

    <title>Comprobante</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>



    <div class="cinema-container">

        <h2>🍿 Productos adicionales</h2>

        <?php while($d=$detalles->fetch_assoc()){ ?>

        <p>
            <?php echo $d['descripcion']; ?>
            x <?php echo $d['cantidad']; ?>

            $
            <?php echo number_format($d['precio'],0,",","."); ?>

        </p>

        <?php } ?>



        <h1>
            🎬 Cinemas Star
        </h1>


        <h2>
            Comprobante de compra
        </h2>



        <?php while($b=$result->fetch_assoc()){ ?>


        <div class="voucher">

        <h2>🎟 Boletos</h2>
            <h3>
                <?php echo $b['titulo']; ?>
            </h3>


            <p>
                Cliente:
                <?php echo $b['nombre']; ?>
            </p>


            <p>
                Correo:
                <?php echo $b['correo']; ?>
            </p>


            <p>
                Fecha:
                <?php echo $b['fecha']; ?>
            </p>


            <p>
                Hora:
                <?php echo $b['hora']; ?>
            </p>


            <p>
                Asiento:
                <?php echo $b['fila'].$b['numero']; ?>
            </p>


            <p>
                Precio:
                $
                <?php echo number_format($b['precio'],0,",","."); ?>
            </p>


        </div>


        <hr>


        <?php } ?>

        <hr>

        <h3>

            Total pagado:

            $

            <?php echo number_format($venta['total'],0,",","."); ?>

        </h3>

        <a href="index.php" class="btn-neon">
            Volver
        </a>


    </div>


</body>


</html>