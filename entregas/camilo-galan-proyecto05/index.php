<?php
require 'config/conection.php';

$sqlPeliculas = "
SELECT
    pelicula.*,
    genero.nombre AS genero
FROM pelicula
INNER JOIN genero
ON pelicula.id_genero = genero.id_genero
";

$peliculas = $conn->query($sqlPeliculas);
?>

<?php
session_start();
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
        <img class="logo" src="Images/logo.svg" alt="">
        <nav class="nav-menu">
            <ul class="principal">
                <li>
                    <a href="#" class="nav-link">Inicio</a>
                </li>

                <li class="dropdown">
                    <button class="dropbtn">Confiteria<svg class="arrow" fill="currentColor"xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" >
<path d="m18.57,11.18l-10-7c-.3-.21-.7-.24-1.04-.07-.33.17-.54.51-.54.89v14c0,.37.21.71.54.89.15.08.3.11.46.11.2,0,.4-.06.57-.18l10-7c.27-.19.43-.49.43-.82s-.16-.63-.43-.82Z"/>
</svg> </button>
                    <ul class="dropdown-content">
                        <li><a href="snacks.html">Combo Amigos</a></li>
                        <li><a href="snacks.html">Combo Familiar</a></li>
                        <li><a href="snacks.html">Combo Big</a></li>
                        <li><a href="snacks.html">Combo Fan</a></li>
                    </ul>
                </li>
            

                <li class="dropdown">
                    <button class="dropbtn">Estrenos <svg class="arrow" fill="currentColor"xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" >
<path d="m18.57,11.18l-10-7c-.3-.21-.7-.24-1.04-.07-.33.17-.54.51-.54.89v14c0,.37.21.71.54.89.15.08.3.11.46.11.2,0,.4-.06.57-.18l10-7c.27-.19.43-.49.43-.82s-.16-.63-.43-.82Z"/>
</svg> </button>
                    <ul class="dropdown-content">
                        <li><a href="premier.html">Esta Semana</a></li>
                        <li><a href="premier.html">Proximos Estrenos</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="nav-link">Contacto</a>
                </li>
            </ul>    
        </nav>

        <div class="but-cart">
            <?php if(isset($_SESSION['usuario_nombre'])): ?>
                <span style="color: white; margin-right: 15px; font-family: 'Montserrat', serif; font-weight: bold;">Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                <button class="cartelera" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
            <?php else: ?>
                <button class="cartelera" onclick="window.location.href='login.php'">Iniciar Sesión</button>
                <button class="cartelera" onclick="window.location.href='register.php'" style="margin-left: 10px;">Registrarse</button>
            <?php endif; ?>
        </div>
    </header>
    
<div class="slider-container">

    <button class="slider-btn prev-btn">❮</button>
    <button class="slider-btn next-btn">❯</button>

        <div class="slider">
        
            <div class="slide-item" style="--color-shadow: rgba(181, 241, 178, 0.3);">
                <img class="img-slider" src="Images/madalorian.jpg" alt="The Mandalorian">
                <div class="slide-overlay">
                    <h2>The Mandalorian and Grogu</h2>
                </div>
            </div>

            <div class="slide-item" style="--color-shadow: rgba(229, 160, 220, 0.3);">
                <img class="img-slider" src="Images/mario-galaxy.jpg" alt="Mario Galaxy">
                <div class="slide-overlay">
                    <h2>Super Mario Galaxy</h2>
                </div>
            </div>

            <div class="slide-item" style="--color-shadow: rgba(240, 230, 143, 0.3);">
                <img class="img-slider" src="Images/michael.jpg" alt="Michael Jackson">
                <div class="slide-overlay">
                    <h2>Michael</h2>
                </div>
            </div>

            <div class="slide-item" style="--color-shadow: rgba(248, 117, 117, 0.3);">
                <img class="img-slider" src="Images/moda.jpg" alt="Moda">
                <div class="slide-overlay">
                    <h2>El Diablo Viste a la Moda 2</h2>
                </div>
            </div>
        </div>
    </div>
    </div>

        <section id="seccion-cartelera" class="seccion-cartelera">
        <h2 class="titulo-cartelera">En Cartelera</h2>
        
        <div class="movies">

            <?php while($pelicula = $peliculas->fetch_assoc()): ?>

            <div class="movie-card"
            style="--color-shadow: rgba(221,184,245,.35);">

                <img
                src="<?= htmlspecialchars($pelicula['imagen']) ?>"
                alt="<?= htmlspecialchars($pelicula['titulo']) ?>">

                <div class="movie-info">

                    <h3>
                        <?= htmlspecialchars($pelicula['titulo']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($pelicula['genero']) ?>
                    </p>

                    <button
                        class="btn-comprar"
                        onclick="window.location.href='schedule.php?id=<?= $pelicula['id_pelicula'] ?>'">

                        Ver Cartelera

                    </button>

                </div>

            </div>

            <?php endwhile; ?>

            </div>
        </div>
    </section>
    <script src="script.js"></script>
</body>
</html>