<?php

session_start();

require '../config/conection.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $categoria = $_POST['categoria'];

    $imagen = "";

    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){

        $nombreImagen =
        time() . "_" . $_FILES['imagen']['name'];

        $ruta =
        "../Images/productos/" . $nombreImagen;

        move_uploaded_file(
            $_FILES['imagen']['tmp_name'],
            $ruta
        );

        $imagen =
        "Images/productos/" . $nombreImagen;
    }

    $sql = "
    INSERT INTO producto
    (
        nombre,
        descripcion,
        precio,
        imagen,
        categoria,
        estado
    )
    VALUES
    (
        '$nombre',
        '$descripcion',
        '$precio',
        '$imagen',
        '$categoria',
        'Disponible'
    )
    ";

    if($conn->query($sql)){

        header("Location: products.php");
        exit();

    }else{

        echo "Error: " . $conn->error;
    }

}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Nuevo Producto</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <div class="cinema-container">

        <h1>
            ➕ Nuevo Producto
        </h1>

        <form method="POST" enctype="multipart/form-data">

            <label>Nombre</label>

            <input type="text" name="nombre" required>

            <br><br>

            <label>Descripción</label>

            <textarea name="descripcion" required></textarea>

            <br><br>

            <label>Precio</label>

            <input type="number" step="0.01" name="precio" required>

            <br><br>

            <label>Categoría</label>

            <input type="text" name="categoria" required>

            <br><br>

            <label>Imagen</label>

            <input type="file" name="imagen" accept="image/*" required>

            <br><br>

            <button type="submit" class="btn-neon">
                Guardar
            </button>

            <a href="products.php" class="btn-comprar">
                Cancelar
            </a>

        </form>

    </div>

</body>

</html>