<?php

session_start();

require '../config/conection.php';

$sql = "

SELECT

p.id_pelicula,
p.titulo,
p.duracion,
p.clasificacion,
p.imagen,
g.nombre AS genero

FROM pelicula p

INNER JOIN genero g
ON p.id_genero = g.id_genero

ORDER BY p.id_pelicula DESC

";

$peliculas = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Administrar Películas</title>

    <link rel="stylesheet" href="../style.css">

</head>

<body>

    <div class="cinema-container">

        <h1>🎬 Administrar Películas</h1>

        <a href="add_movie.php" class="btn-neon">
            Agregar película
        </a>

        <br><br>

        <table border="1" width="100%">

            <tr>

                <th>ID</th>
                <th>Imagen</th>
                <th>Título</th>
                <th>Duración</th>
                <th>Clasificación</th>
                <th>Género</th>
                <th>Acciones</th>

            </tr>

            <?php while($p = $peliculas->fetch_assoc()){ ?>

            <tr>

                <td><?php echo $p['id_pelicula']; ?></td>

                <td>
                    <img src="../<?php echo $p['imagen']; ?>" width="80" alt="<?php echo $p['titulo']; ?>">
                </td>

                <td><?php echo $p['titulo']; ?></td>

                <td><?php echo $p['duracion']; ?></td>

                <td><?php echo $p['clasificacion']; ?></td>

                <td><?php echo $p['genero']; ?></td>

                <td>

                    <a href="edit_movie.php?id=<?php echo $p['id_pelicula']; ?>">
                        Editar
                    </a>

                    |

                    <a href="delete_movie.php?id=<?php echo $p['id_pelicula']; ?>"
                        onclick="return confirm('¿Eliminar película?')">
                        Eliminar
                    </a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</body>

</html>