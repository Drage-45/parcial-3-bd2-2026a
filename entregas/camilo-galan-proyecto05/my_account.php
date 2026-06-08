<?php
session_start();
require 'config/conection.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];

$sql = "
SELECT
    c.nombre, c.correo, c.telefono,
    bo.fecha_venta, bo.precio,
    p.titulo,
    f.fecha, f.hora,
    s.nombre AS sala,
    b.fila, b.numero
FROM boleto bo
INNER JOIN cliente c          ON bo.id_cliente         = c.id_cliente
INNER JOIN funcion_butaca fb  ON bo.id_funcion_butaca  = fb.id_funcion_butaca
INNER JOIN funcion f          ON fb.id_funcion         = f.id_funcion
INNER JOIN pelicula p         ON f.id_pelicula         = p.id_pelicula
INNER JOIN sala s             ON f.id_sala             = s.id_sala
INNER JOIN butaca b           ON fb.id_butaca          = b.id_butaca
WHERE c.id_cliente = $id_cliente
ORDER BY bo.fecha_venta DESC
";
$compras = $conn->query($sql);

// Inicial del nombre para el avatar
$inicial = strtoupper(mb_substr($_SESSION['usuario_nombre'], 0, 1));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php"    class="nav-link">Inicio</a></li>
                <li><a href="snacks.php"   class="nav-link">Confitería</a></li>
                <li><a href="premier.html" class="nav-link">Estrenos</a></li>
            </ul>
        </nav>
        <div class="but-cart">
            <button class="cartelera" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
        </div>
    </header>

    <div class="cinema-container">

        <div class="account-layout">

            <!-- Sidebar -->
            <aside class="account-sidebar">
                <div class="account-avatar"><?= $inicial ?></div>
                <h3><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></h3>
                <p><?= htmlspecialchars($_SESSION['usuario_correo'] ?? '') ?></p>
                <ul class="account-nav">
                    <li><a href="my_account.php" class="active">🎟 Mis compras</a></li>
                    <li><a href="index.php">🎬 Cartelera</a></li>
                    <li><a href="cart.php">🛒 Mi carrito</a></li>
                    <li><a href="logout.php">← Cerrar sesión</a></li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <main>
                <div class="page-header">
                    <h1>Mis Compras</h1>
                    <p>Historial de boletos adquiridos</p>
                </div>

                <?php if ($compras->num_rows > 0): ?>
                <table class="tabla-compras">
                    <thead>
                        <tr>
                            <th>Película</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Sala</th>
                            <th>Asiento</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($c = $compras->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['titulo']) ?></td>
                            <td><?= htmlspecialchars($c['fecha']) ?></td>
                            <td><?= substr($c['hora'], 0, 5) ?></td>
                            <td><?= htmlspecialchars($c['sala']) ?></td>
                            <td><strong><?= $c['fila'] . $c['numero'] ?></strong></td>
                            <td style="color:var(--primary);font-weight:700;">
                                $<?= number_format($c['precio'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="cart-empty">
                    <p style="font-size:48px;margin-bottom:16px;">🎬</p>
                    <p>Todavía no tienes compras registradas</p>
                    <a href="index.php" class="btn-neon">Ver cartelera</a>
                </div>
                <?php endif; ?>
            </main>

        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>