<?php

session_start();

require '../config/conection.php';

/*
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
*/

$sql = "
SELECT *
FROM producto
ORDER BY id_producto DESC
";

$productos = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Administrar Productos</title>

<link rel="stylesheet" href="../style.css">

</head>

<body>

<div class="cinema-container">

    <h1>
        🍿 Administración de Productos
    </h1>

    <br>

    <a href="product_create.php" class="btn-neon">
        ➕ Nuevo Producto
    </a>

    <br><br>

    <table border="1" width="100%" cellpadding="10">

        <tr>

            <th>ID</th>

            <th>Imagen</th>

            <th>Nombre</th>

            <th>Descripción</th>

            <th>Precio</th>

            <th>Categoría</th>

            <th>Estado</th>

            <th>Acciones</th>

        </tr>

        <?php while($p = $productos->fetch_assoc()){ ?>

        <tr>

            <td>
                <?php echo $p['id_producto']; ?>
            </td>

            <td>

                <img
                src="../<?php echo $p['imagen']; ?>"
                width="100"
                >

            </td>

            <td>
                <?php echo $p['nombre']; ?>
            </td>

            <td>
                <?php echo $p['descripcion']; ?>
            </td>

            <td>

                $

                <?php echo number_format($p['precio'],0,",","."); ?>

            </td>

            <td>
                <?php echo $p['categoria']; ?>
            </td>

            <td>
                <?php echo $p['estado']; ?>
            </td>

            <td>

                <a
                href="product_edit.php?id=<?php echo $p['id_producto']; ?>"
                class="btn-comprar"
                >
                    Editar
                </a>

                <br><br>

                <a
                href="product_delete.php?id=<?php echo $p['id_producto']; ?>"
                class="btn-comprar"
                onclick="return confirm('¿Cambiar estado del producto?')"
                >
                    Desactivar
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="dashboard.php" class="btn-neon">
        ⬅ Volver al Panel
    </a>

</div>

</body>

</html>