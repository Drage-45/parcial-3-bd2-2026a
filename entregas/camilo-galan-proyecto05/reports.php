<?php

session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

/*
Ejemplo si tienes rol administrador
if($_SESSION['rol'] != 'ADMIN'){
    die("Acceso denegado");
}
*/

require 'config/conection.php';


$sqlPeliculas = "

SELECT

p.titulo,
COUNT(b.id_boleto) AS vendidos,
SUM(b.precio) AS recaudado

FROM boleto b

INNER JOIN funcion_butaca fb
ON b.id_funcion_butaca = fb.id_funcion_butaca

INNER JOIN funcion f
ON fb.id_funcion = f.id_funcion

INNER JOIN pelicula p
ON f.id_pelicula = p.id_pelicula

GROUP BY p.id_pelicula

ORDER BY vendidos DESC

";

$peliculas = $conn->query($sqlPeliculas);

$sqlDias = "

SELECT

DATE(fecha) AS fecha,
SUM(total) AS total

FROM venta

WHERE estado='PAGADA'

GROUP BY DATE(fecha)

ORDER BY fecha DESC

";

$dias = $conn->query($sqlDias);

$sqlCombos = "

SELECT

descripcion,
SUM(cantidad) AS vendidos,
SUM(precio * cantidad) AS total

FROM venta_detalle

WHERE tipo='COMBO'

GROUP BY descripcion

ORDER BY vendidos DESC

";

$combos = $conn->query($sqlCombos);

$resumen = $conn->query("

SELECT

COUNT(*) AS ventas,
SUM(total) AS dinero

FROM venta

WHERE estado='PAGADA'

")->fetch_assoc();

$sqlFunciones = "

SELECT

p.titulo,
f.fecha,
f.hora,
COUNT(b.id_boleto) AS vendidos

FROM boleto b

INNER JOIN funcion_butaca fb
ON b.id_funcion_butaca = fb.id_funcion_butaca

INNER JOIN funcion f
ON fb.id_funcion = f.id_funcion

INNER JOIN pelicula p
ON f.id_pelicula = p.id_pelicula

GROUP BY f.id_funcion

ORDER BY vendidos DESC

LIMIT 10

";

$funciones = $conn->query($sqlFunciones);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>🎬 Películas más vendidas</h2>

    <table>

        <tr>
            <th>Película</th>
            <th>Boletos</th>
            <th>Recaudación</th>
        </tr>

        <?php while($p = $peliculas->fetch_assoc()){ ?>

        <tr>
            <td><?php echo $p['titulo']; ?></td>
            <td><?php echo $p['vendidos']; ?></td>
            <td>$<?php echo number_format($p['recaudado'],0,",","."); ?></td>
        </tr>

        <?php } ?>

    </table>

    <h2>🍿 Combos más vendidos</h2>

    <table>

        <tr>
            <th>Combo</th>
            <th>Vendidos</th>
            <th>Ingresos</th>
        </tr>

        <?php while($c = $combos->fetch_assoc()){ ?>

        <tr>
            <td><?php echo $c['descripcion']; ?></td>
            <td><?php echo $c['vendidos']; ?></td>
            <td>$<?php echo number_format($c['total'],0,",","."); ?></td>
        </tr>

        <?php } ?>

    </table>

    <h2>💰 Recaudación por día</h2>

    <table>

        <tr>
            <th>Fecha</th>
            <th>Total</th>
        </tr>

        <?php while($d = $dias->fetch_assoc()){ ?>

        <tr>
            <td><?php echo $d['fecha']; ?></td>
            <td>$<?php echo number_format($d['total'],0,",","."); ?></td>
        </tr>

        <?php } ?>

    </table>

    <h2>🎟 Funciones más vendidas</h2>

    <table>

        <tr>
            <th>Película</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Boletos</th>
        </tr>

        <?php while($f = $funciones->fetch_assoc()){ ?>

        <tr>
            <td><?php echo $f['titulo']; ?></td>
            <td><?php echo $f['fecha']; ?></td>
            <td><?php echo $f['hora']; ?></td>
            <td><?php echo $f['vendidos']; ?></td>
        </tr>

        <?php } ?>

    </table>

    <h2>📈 Resumen general</h2>

    <p>
        Ventas realizadas:
        <?php echo $resumen['ventas']; ?>
    </p>

    <p>
        Ingresos totales:
        $
        <?php echo number_format($resumen['dinero'] ?? 0,0,",","."); ?>
    </p>

    <a href="index.php" class="btn-neon">
        Volver al inicio
    </a>
</body>

</html>