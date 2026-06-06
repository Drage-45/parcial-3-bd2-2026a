<?php
session_start();
require 'config/conection.php';

$sqlPeliculas = "
SELECT
    pelicula.*,
    genero.nombre AS genero
FROM pelicula
INNER JOIN genero
ON pelicula.id_genero = genero.id_genero
ORDER BY pelicula.id_pelicula DESC
";

$peliculas = $conn->query($sqlPeliculas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="menu">

        <img class="logo" src="Images/logo.svg" alt="Logo">

        <nav class="nav-menu">
            <ul class="principal">

                <li>
                    <a href="index.php" class="nav-link">Inicio</a>
                </li>

                <li class="dropdown">
                    <button class="dropbtn">
                        Confitería
                    </button>

                    <ul class="dropdown-content">
                        <li><a href="snacks.php">Combo Amigos</a></li>
                        <li><a href="snacks.php">Combo Familiar</a></li>
                        <li><a href="snacks.php">Combo Big</a></li>
                        <li><a href="snacks.php">Combo Fan</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <button class="dropbtn">
                        Estrenos
                    </button>

                    <ul class="dropdown-content">
                        <li><a href="premier.html">Esta Semana</a></li>
                        <li><a href="premier.html">Próximos Estrenos</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-link">Contacto</a>
                </li>
                <a href="cart.php" class="nav-link">

                     Carrito  <img src="Images/cart.svg" alt="">

                </a>
            </ul>
        </nav>

        <div class="but-cart">

            <?php if(isset($_SESSION['usuario_nombre'])): ?>

            <span style="color:white;margin-right:15px;font-weight:bold;">
                Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
            </span>

            <button class="cartelera" onclick="window.location.href='logout.php'">
                Cerrar Sesión
            </button>

            <?php else: ?>

            <button class="cartelera" onclick="window.location.href='login.php'">
                Iniciar Sesión
            </button>

            <button class="cartelera" onclick="window.location.href='register.php'">
                Registrarse
            </button>

            <?php endif; ?>

        </div>

    </header>

    <!-- SLIDER -->

    <div class="slider-container">

        <button class="slider-btn prev-btn">❮</button>
        <button class="slider-btn next-btn">❯</button>

        <div class="slider">

            <div class="slide-item">
                <img class="img-slider" src="Images/madalorian.jpg">
            </div>

            <div class="slide-item">
                <img class="img-slider" src="Images/mario-galaxy.jpg">
            </div>

            <div class="slide-item">
                <img class="img-slider" src="Images/michael.jpg">
            </div>

            <div class="slide-item">
                <img class="img-slider" src="Images/moda.jpg">
            </div>

        </div>

    </div>

    <!-- CARTELERA -->

    <section class="seccion-cartelera">

        <h2 class="titulo-cartelera">
            En Cartelera
        </h2>

        <div class="movies">

            <?php while($pelicula = $peliculas->fetch_assoc()): ?>

            <div class="movie-card">

                <img src="<?= htmlspecialchars($pelicula['imagen']) ?>"
                    alt="<?= htmlspecialchars($pelicula['titulo']) ?>">

                <div class="movie-info">

                    <h3>
                        <?= htmlspecialchars($pelicula['titulo']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($pelicula['genero']) ?>
                    </p>

                    <a href="schedule.php?id_pelicula=<?= $pelicula['id_pelicula'] ?>" class="btn-comprar">
                        Comprar Boletos
                    </a>

                </div>

            </div>

            <?php endwhile; ?>

        </div>

    </section>

    <script src="script.js"></script>

</body>

</html>