<?php
require 'config/conection.php';

if (!isset($_GET['id_pelicula'])) {
    die("Película no encontrada");
}

$id_pelicula = intval($_GET['id_pelicula']);

/* CONSULTAR PELÍCULA */
$sqlPelicula = "
SELECT p.*, g.nombre AS genero
FROM pelicula p
INNER JOIN genero g
ON p.id_genero = g.id_genero
WHERE p.id_pelicula = $id_pelicula
";

$resultPelicula = $conn->query($sqlPelicula);

if ($resultPelicula->num_rows == 0) {
    die("Película no encontrada");
}

$pelicula = $resultPelicula->fetch_assoc();

/* CONSULTAR FUNCIONES */
$sqlFunciones = "
SELECT f.*, s.nombre AS sala
FROM funcion f
INNER JOIN sala s
ON f.id_sala = s.id_sala
WHERE f.id_pelicula = $id_pelicula
ORDER BY f.fecha, f.hora
";

$funciones = $conn->query($sqlFunciones);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios - <?php echo htmlspecialchars($pelicula['titulo']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="cinema-container">

        <div class="movie-header">

            <div class="movie-poster">
                <img src="<?php echo htmlspecialchars($pelicula['imagen']); ?>"
                    alt="<?php echo htmlspecialchars($pelicula['titulo']); ?>" style="width:100%; border-radius:12px;">
            </div>

            <div class="movie-info">

                <h1>
                    <?php echo htmlspecialchars($pelicula['titulo']); ?>
                </h1>

                <p class="genre">
                    <?php echo htmlspecialchars($pelicula['genero']); ?>
                    •
                    <?php echo htmlspecialchars($pelicula['duracion']); ?>
                    •
                    <?php echo htmlspecialchars($pelicula['clasificacion']); ?>
                </p>

                <p class="synopsis">
                    <?php echo htmlspecialchars($pelicula['sinopsis']); ?>
                </p>

            </div>

        </div>

        <div class="selection-section">

            <h3>Funciones Disponibles</h3>

            <div class="horizontal-list time-list">

                <?php
            if ($funciones->num_rows > 0) {

                while ($funcion = $funciones->fetch_assoc()) {
            ?>

                <a href="seats.php?id_funcion=<?php echo $funcion['id_funcion']; ?>" class="list-item time-item"
                    style="text-decoration:none;">

                    <strong>
                        <?php echo date('d/m/Y', strtotime($funcion['fecha'])); ?>
                    </strong>

                    <br>

                    <?php echo substr($funcion['hora'], 0, 5); ?>

                    <br>

                    <small>
                        <?php echo htmlspecialchars($funcion['sala']); ?>
                    </small>

                </a>

                <?php
                }

            } else {
                echo "<p>No hay funciones disponibles para esta película.</p>";
            }
            ?>

            </div>

        </div>

    </div>

    <script src="script.js"></script>

</body>

</html>