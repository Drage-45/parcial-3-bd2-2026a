<?php
require 'config/conection.php';

if (!isset($_GET['id_pelicula'])) {
    die("Película no encontrada");
}

$id_pelicula = intval($_GET['id_pelicula']);

$sqlPelicula = "
SELECT p.*, g.nombre AS genero
FROM pelicula p
INNER JOIN genero g ON p.id_genero = g.id_genero
WHERE p.id_pelicula = $id_pelicula
";
$resultPelicula = $conn->query($sqlPelicula);

if ($resultPelicula->num_rows == 0) die("Película no encontrada");

$pelicula = $resultPelicula->fetch_assoc();

$sqlFunciones = "
SELECT f.*, s.nombre AS sala
FROM funcion f
INNER JOIN sala s ON f.id_sala = s.id_sala
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
    <title><?= htmlspecialchars($pelicula['titulo']) ?> — Horarios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php" class="nav-link">← Cartelera</a></li>
            </ul>
        </nav>
    </header>

    <div class="cinema-container">

        <!-- Cabecera de la película -->
        <div class="movie-header">
            <div class="movie-poster">
                <img src="<?= htmlspecialchars($pelicula['imagen']) ?>"
                     alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
            </div>
            <div class="movie-info">
                <h1><?= htmlspecialchars($pelicula['titulo']) ?></h1>
                <p class="genre">
                    <?= htmlspecialchars($pelicula['genero']) ?>
                    <?php if (!empty($pelicula['duracion'])):      ?> · <?= htmlspecialchars($pelicula['duracion']) ?> min<?php endif; ?>
                    <?php if (!empty($pelicula['clasificacion'])): ?> · <?= htmlspecialchars($pelicula['clasificacion']) ?><?php endif; ?>
                </p>
                <?php if (!empty($pelicula['sinopsis'])): ?>
                <p class="synopsis"><?= htmlspecialchars($pelicula['sinopsis']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Funciones disponibles -->
        <div class="selection-section">
            <h3>Funciones Disponibles</h3>

            <?php if ($funciones->num_rows > 0): ?>
            <div class="horizontal-list">
                <?php while ($f = $funciones->fetch_assoc()): ?>
                <a href="seats.php?id_funcion=<?= $f['id_funcion'] ?>" class="list-item">
                    <strong><?= date('d/m/Y', strtotime($f['fecha'])) ?></strong>
                    <span style="font-family:'Bebas Neue',sans-serif;font-size:22px;letter-spacing:1px;color:var(--text);">
                        <?= substr($f['hora'], 0, 5) ?>
                    </span>
                    <small><?= htmlspecialchars($f['sala']) ?></small>
                </a>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <p style="color:var(--text-secondary);font-size:14px;">
                No hay funciones disponibles para esta película.
            </p>
            <?php endif; ?>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>