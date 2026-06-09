<?php
session_start();
require 'config/conection.php';

$sqlPeliculas = "
SELECT pelicula.*, genero.nombre AS genero
FROM pelicula
INNER JOIN genero ON pelicula.id_genero = genero.id_genero
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .logo-wrap { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-text {
            font-family: 'Bebas Neue', 'Arial Black', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 2px;
            color: #fff;
            text-transform: uppercase;
        }
        .logo-red { color: #e50914; }
    </style>
</head>
<body>

    <header class="menu" id="navbar">
        <a href="index.php" class="logo-wrap">
            <span class="logo-text">CINEMAS <span class="logo-red">STAR</span></span>
        </a>

        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php" class="nav-link active">Inicio</a></li>

                <li class="dropdown">
                    <button class="dropbtn">Confitería ▾</button>
                    <ul class="dropdown-content">
                        <li><a href="snacks.php">Combo Amigos</a></li>
                        <li><a href="snacks.php">Combo Familiar</a></li>
                        <li><a href="snacks.php">Combo Big</a></li>
                        <li><a href="snacks.php">Combo Fan</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <button class="dropbtn">Estrenos ▾</button>
                    <ul class="dropdown-content">
                        <li><a href="premier.html">Esta Semana</a></li>
                        <li><a href="premier.html">Próximos Estrenos</a></li>
                    </ul>
                </li>

                <li><a href="#" class="nav-link">Contacto</a></li>

                <li>
                    <a href="cart.php" class="nav-link">
                        Carrito <img src="Images/cart.svg" alt="Carrito">
                    </a>
                </li>
            </ul>
        </nav>

        <div class="but-cart">
            <?php if (isset($_SESSION['admin_id'])): ?>
                <a href="admin/dashboard.php" class="cartelera" style="text-decoration:none;">
                    Panel Admin
                </a>
                <button class="cartelera" onclick="window.location.href='admin/logout.php'">
                    Cerrar Sesión
                </button>
            <?php elseif (isset($_SESSION['usuario_nombre'])): ?>
                <span style="color:var(--text-secondary);font-size:13px;font-weight:500;">
                    Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?>
                </span>
                <button class="cartelera" onclick="window.location.href='logout.php'">
                    Cerrar Sesión
                </button>
            <?php else: ?>
                <button class="cartelera" onclick="window.location.href='register.php'">
                    Registrarse
                </button>
                <button class="cartelera" onclick="window.location.href='login.php'">
                    Iniciar Sesión
                </button>
            <?php endif; ?>
        </div>
    </header>

    <!-- SLIDER -->
    <div class="slider-container">
        <button class="slider-btn prev-btn">❮</button>
        <button class="slider-btn next-btn">❯</button>

        <div class="slider">
            <div class="slide-item active">
                <img class="img-slider" src="Images/madalorian.jpg" alt="The Mandalorian">
                <div class="slide-overlay">
                    <h2>The Mandalorian</h2>
                    <p>Ciencia Ficción · Acción · Aventura</p>
                    <a href="#cartelera" class="btn-slider">Ver Cartelera</a>
                </div>
            </div>
            <div class="slide-item">
                <img class="img-slider" src="Images/mario-galaxy.jpg" alt="Mario">
                <div class="slide-overlay">
                    <h2>Mario Galaxy</h2>
                    <p>Animación · Aventura · Familiar</p>
                    <a href="#cartelera" class="btn-slider">Ver Cartelera</a>
                </div>
            </div>
            <div class="slide-item">
                <img class="img-slider" src="Images/michael.jpg" alt="Michael">
                <div class="slide-overlay">
                    <h2>Michael</h2>
                    <p>Drama · Biográfico</p>
                    <a href="#cartelera" class="btn-slider">Ver Cartelera</a>
                </div>
            </div>
            <div class="slide-item">
                <img class="img-slider" src="Images/moda.jpg" alt="El Diablo Viste a la Moda">
                <div class="slide-overlay">
                    <h2>El Diablo Viste a la Moda</h2>
                    <p>Drama · Comedia</p>
                    <a href="#cartelera" class="btn-slider">Ver Cartelera</a>
                </div>
            </div>
        </div>

        <div class="slider-indicators" id="dots"></div>
    </div>

    <!-- CARTELERA -->
    <section class="seccion-cartelera" id="cartelera">
        <h2 class="titulo-cartelera">En Cartelera</h2>

        <div class="movies">
            <?php while ($pelicula = $peliculas->fetch_assoc()): ?>
            <div class="movie-card">
                <img src="<?= htmlspecialchars($pelicula['imagen']) ?>"
                     alt="<?= htmlspecialchars($pelicula['titulo']) ?>">

                <span class="movie-genre-badge">
                    <?= htmlspecialchars($pelicula['genero']) ?>
                </span>

                <div class="movie-info">
                    <h3><?= htmlspecialchars($pelicula['titulo']) ?></h3>
                    <p><?= htmlspecialchars($pelicula['genero']) ?></p>
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