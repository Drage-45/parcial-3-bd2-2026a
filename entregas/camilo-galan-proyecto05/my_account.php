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

c.nombre,
c.correo,
c.telefono,


bo.fecha_venta,
bo.precio,


p.titulo,
f.fecha,
f.hora,
s.nombre AS sala,


b.fila,
b.numero


FROM boleto bo


INNER JOIN cliente c
ON bo.id_cliente=c.id_cliente


INNER JOIN funcion_butaca fb
ON bo.id_funcion_butaca=fb.id_funcion_butaca


INNER JOIN funcion f
ON fb.id_funcion=f.id_funcion


INNER JOIN pelicula p
ON f.id_pelicula=p.id_pelicula


INNER JOIN sala s
ON f.id_sala=s.id_sala


INNER JOIN butaca b
ON fb.id_butaca=b.id_butaca


WHERE c.id_cliente=$id_cliente


ORDER BY bo.fecha_venta DESC

";


$compras=$conn->query($sql);



?>


<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Mi cuenta</title>

<link rel="stylesheet" href="style.css">

</head>

<header>

<?php if(isset($_SESSION['usuario_id'])){ ?>

<button onclick="location.href='mi_cuenta.php'">
Mi cuenta
</button>

<button onclick="location.href='logout.php'">
Cerrar sesión
</button>


<?php }else{ ?>


<button onclick="location.href='login.php'">
Iniciar sesión
</button>


<?php } ?>

</header>

<body>



<div class="cinema-container">


<h1>
Mi cuenta
</h1>



<div class="auth-card">


<h2>
<?php echo $_SESSION['usuario_nombre']; ?>
</h2>



<p>
Correo:
<?php echo $_SESSION['usuario_nombre']; ?>
</p>




<h2>
Mis compras
</h2>




<?php if($compras->num_rows > 0){ ?>



<table class="tabla-compras">


<tr>

<th>Película</th>
<th>Fecha</th>
<th>Hora</th>
<th>Sala</th>
<th>Asiento</th>
<th>Total</th>

</tr>



<?php while($c=$compras->fetch_assoc()){ ?>


<tr>


<td>
<?php echo $c['titulo']; ?>
</td>


<td>
<?php echo $c['fecha']; ?>
</td>


<td>
<?php echo substr($c['hora'],0,5); ?>
</td>


<td>
<?php echo $c['sala']; ?>
</td>


<td>
<?php echo $c['fila'].$c['numero']; ?>
</td>


<td>
$
<?php echo number_format($c['precio'],0,",","."); ?>
</td>



</tr>



<?php } ?>


</table>



<?php }else{ ?>


<p>
Todavía no tienes compras.
</p>


<?php } ?>



</div>


</div>


</body>

</html>