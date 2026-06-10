<?php
session_start();
require 'config/conection.php';

$sql      = "SELECT * FROM producto WHERE estado='Disponible'";
$productos = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confitería — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php"    class="nav-link">Inicio</a></li>
                <li><a href="premier.html" class="nav-link">Estrenos</a></li>
                <li><a href="snacks.php"   class="nav-link active">Confitería</a></li>
                <li><a href="cart.php"     class="nav-link">Carrito <img src="Images/cart.svg" alt=""></a></li>
            </ul>
        </nav>
        <div class="but-cart">
            <?php if (isset($_SESSION['usuario_nombre'])): ?>
                <span>Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
                <button class="cartelera" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
            <?php else: ?>
                <button class="cartelera" onclick="window.location.href='login.php'">Iniciar Sesión</button>
            <?php endif; ?>
        </div>
    </header>

    <section class="seccion-principal">
        <h2 class="titulo-seccion">Nuestros Combos</h2>

        <div class="contenedor-bloques">
            <?php while ($p = $productos->fetch_assoc()): ?>
            <div class="bloque-card">
                <img src="<?= htmlspecialchars($p['imagen']) ?>"
                     class="bloque-img producto-img"
                     alt="<?= htmlspecialchars($p['nombre']) ?>">

                <div class="bloque-info">
                    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                    <p><?= htmlspecialchars($p['descripcion']) ?></p>

                    <div class="bloque-footer">
                        <span class="bloque-precio">
                            $<?= number_format($p['precio'], 0, ',', '.') ?>
                        </span>
                        <form action="add_cart.php" method="POST">
                            <input type="hidden" name="id_producto" value="<?= $p['id_producto'] ?>">
                            <button type="submit" class="btn-comprar">Agregar al carrito</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>