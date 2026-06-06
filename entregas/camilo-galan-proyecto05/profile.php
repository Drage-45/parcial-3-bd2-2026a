<?php

session_start();

require 'config/conection.php';

if(!isset($_SESSION['usuario_id'])){

    header("Location: login.php");
    exit();
}

$id = $_SESSION['usuario_id'];

$stmt = $conn->prepare(
    "SELECT *
     FROM cliente
     WHERE id_cliente = ?"
);

$stmt->bind_param("i",$id);

$stmt->execute();

$usuario =
$stmt->get_result()->fetch_assoc();

?>

<h1>Mi Perfil</h1>

<p>Nombre:
<?= htmlspecialchars($usuario['nombre']) ?>
</p>

<p>Correo:
<?= htmlspecialchars($usuario['correo']) ?>
</p>

<p>Teléfono:
<?= htmlspecialchars($usuario['telefono']) ?>
</p>

<p>Fecha Registro:
<?= htmlspecialchars($usuario['fecha_registro']) ?>
</p>