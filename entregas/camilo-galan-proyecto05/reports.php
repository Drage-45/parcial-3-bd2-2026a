<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'config/conection.php';

$sqlPeliculas = "
SELECT p.titulo,
    COUNT(b.id_boleto) AS vendidos,
    SUM(b.precio)      AS recaudado
FROM boleto b
INNER JOIN funcion_butaca fb ON b.id_funcion_butaca = fb.id_funcion_butaca
INNER JOIN funcion f         ON fb.id_funcion       = f.id_funcion
INNER JOIN pelicula p        ON f.id_pelicula       = p.id_pelicula
GROUP BY p.id_pelicula
ORDER BY vendidos DESC
";

$sqlDias = "
SELECT DATE(fecha) AS fecha, SUM(total) AS total
FROM venta
WHERE estado='PAGADA'
GROUP BY DATE(fecha)
ORDER BY fecha DESC
";

$sqlCombos = "
SELECT descripcion,
    SUM(cantidad)          AS vendidos,
    SUM(precio * cantidad) AS total
FROM venta_detalle
WHERE tipo='COMBO'
GROUP BY descripcion
ORDER BY vendidos DESC
";

$resumen = $conn->query("
    SELECT COUNT(*) AS ventas, SUM(total) AS dinero
    FROM venta WHERE estado='PAGADA'
")->fetch_assoc();

$sqlFunciones = "
SELECT p.titulo, f.fecha, f.hora, COUNT(b.id_boleto) AS vendidos
FROM boleto b
INNER JOIN funcion_butaca fb ON b.id_funcion_butaca = fb.id_funcion_butaca
INNER JOIN funcion f         ON fb.id_funcion       = f.id_funcion
INNER JOIN pelicula p        ON f.id_pelicula       = p.id_pelicula
GROUP BY f.id_funcion
ORDER BY vendidos DESC
LIMIT 10
";

$peliculas = $conn->query($sqlPeliculas);
$dias      = $conn->query($sqlDias);
$combos    = $conn->query($sqlCombos);
$funciones = $conn->query($sqlFunciones);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php" class="nav-link">← Volver</a></li>
            </ul>
        </nav>
    </header>

    <div class="cinema-container">

        <div class="page-header">
            <h1>Reportes</h1>
            <p>Panel de administración — resumen de ventas</p>
        </div>

        <!-- Tarjetas resumen -->
        <div class="reports-grid">
            <div class="stat-card">
                <div class="stat-label">Ventas realizadas</div>
                <div class="stat-value"><?= $resumen['ventas'] ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Ingresos totales</div>
                <div class="stat-value red">
                    $<?= number_format($resumen['dinero'] ?? 0, 0, ',', '.') ?>
                </div>
            </div>
        </div>

        <!-- Películas más vendidas -->
        <div class="reports-section-title">🎬 Películas más vendidas</div>
        <table>
            <thead>
                <tr>
                    <th>Película</th>
                    <th>Boletos vendidos</th>
                    <th>Recaudación</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($p = $peliculas->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($p['titulo']) ?></td>
                    <td><?= $p['vendidos'] ?></td>
                    <td style="color:var(--primary);font-weight:700;">
                        $<?= number_format($p['recaudado'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Combos más vendidos -->
        <div class="reports-section-title">🍿 Combos más vendidos</div>
        <table>
            <thead>
                <tr>
                    <th>Combo</th>
                    <th>Vendidos</th>
                    <th>Ingresos</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($c = $combos->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($c['descripcion']) ?></td>
                    <td><?= $c['vendidos'] ?></td>
                    <td style="color:var(--primary);font-weight:700;">
                        $<?= number_format($c['total'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Recaudación por día -->
        <div class="reports-section-title">💰 Recaudación por día</div>
        <table>
            <thead>
                <tr><th>Fecha</th><th>Total</th></tr>
            </thead>
            <tbody>
                <?php while ($d = $dias->fetch_assoc()): ?>
                <tr>
                    <td><?= $d['fecha'] ?></td>
                    <td style="color:var(--primary);font-weight:700;">
                        $<?= number_format($d['total'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Funciones más vendidas -->
        <div class="reports-section-title">🎟 Funciones más vendidas</div>
        <table>
            <thead>
                <tr>
                    <th>Película</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Boletos</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($f = $funciones->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($f['titulo']) ?></td>
                    <td><?= $f['fecha'] ?></td>
                    <td><?= substr($f['hora'], 0, 5) ?></td>
                    <td><?= $f['vendidos'] ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div style="margin-top:32px;">
            <a href="index.php" class="btn-neon">← Volver al inicio</a>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>