<?php

session_start();

require 'config/conection.php';


$sql="
SELECT *
FROM producto
WHERE estado='Disponible'
";


$productos=$conn->query($sql);


?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confitería - Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="menu">
        <img class="logo" src="Images/logo.svg" alt="Logo">
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php" class="nav-link">Inicio</a></li>
                <li><a href="premier.html" class="nav-link">Estrenos</a></li>
                <li><a href="snacks.php" class="nav-link" style="color: #ddb8f5;">Confitería</a></li>
            </ul>
        </nav>
    </header>

    <section class="seccion-principal">
        <h2 class="titulo-seccion">Nuestros Combos</h2>

        <div class="contenedor-bloques">
            <div class="contenedor-bloques">
                <?php while($p=$productos->fetch_assoc()){ ?>

                <div class="bloque-card">


                    <img src="<?php echo $p['imagen']; ?>" class="producto-img">


                    <div class="bloque-info">

                        <h3>
                            <?php echo $p['nombre']; ?>
                        </h3>


                        <p>
                            <?php echo $p['descripcion']; ?>
                        </p>


                        <div class="bloque-footer">


                            <span class="bloque-precio">

                                $
                                <?php echo number_format($p['precio'],0,",","."); ?>

                            </span>


                            <button class="btn-comprar agregar-producto" data-id="<?php echo $p['id_producto']; ?>"
                                data-nombre="<?php echo $p['nombre']; ?>" data-precio="<?php echo $p['precio']; ?>">

                                Agregar

                            </button>


                        </div>

                    </div>

                </div>


                <?php } ?>


            </div>
        </div>
        </div>
    </section>

    <script src="script.js"></script>
</body>

</html>