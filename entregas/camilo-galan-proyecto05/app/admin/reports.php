<?php
// Procesar queries ANTES del layout para evitar conflicto de variables
if (session_status() === PHP_SESSION_NONE) session_start();
require '../config/conection.php';

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

$resumen       = $conn->query("SELECT COUNT(*) AS ventas, SUM(total) AS dinero FROM venta WHERE estado='PAGADA'")->fetch_assoc();
$listaPelis    = $conn->query($sqlPeliculas);
$listaDias     = $conn->query($sqlDias);
$listaCombos   = $conn->query($sqlCombos);
$listaFunciones = $conn->query($sqlFunciones);

$pageTitle  = 'Reportes';
$activeMenu = 'reports';
require '_layout.php';
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Reportes</h1>
        <p class="admin-page-sub">Resumen general de ventas y funciones</p>
    </div>
</div>

<!-- Tarjetas resumen -->
<div class="stats-row" style="margin-bottom:32px;">
    <div class="stat-card-r">
        <div class="stat-r-label">Ventas totales</div>
        <div class="stat-r-value"><?= $resumen['ventas'] ?? 0 ?></div>
    </div>
    <div class="stat-card-r red">
        <div class="stat-r-label">Ingresos totales</div>
        <div class="stat-r-value">$<?= number_format($resumen['dinero'] ?? 0, 0, ',', '.') ?></div>
    </div>
</div>

<!-- Películas más vendidas -->
<div class="report-section">
    <div class="report-section-title">🎬 Películas más vendidas</div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Película</th><th>Boletos vendidos</th><th>Recaudación</th></tr></thead>
            <tbody>
            <?php if ($listaPelis->num_rows === 0): ?>
                <tr><td colspan="3" class="empty-row">Sin datos</td></tr>
            <?php endif; ?>
            <?php while ($p = $listaPelis->fetch_assoc()): ?>
            <tr>
                <td><strong><?= htmlspecialchars($p['titulo']) ?></strong></td>
                <td><?= $p['vendidos'] ?></td>
                <td class="money">$<?= number_format($p['recaudado'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Combos más vendidos -->
<div class="report-section">
    <div class="report-section-title">🍿 Combos más vendidos</div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Combo</th><th>Vendidos</th><th>Ingresos</th></tr></thead>
            <tbody>
            <?php if ($listaCombos->num_rows === 0): ?>
                <tr><td colspan="3" class="empty-row">Sin datos</td></tr>
            <?php endif; ?>
            <?php while ($c = $listaCombos->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($c['descripcion']) ?></td>
                <td><?= $c['vendidos'] ?></td>
                <td class="money">$<?= number_format($c['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Recaudación por día -->
<div class="report-section">
    <div class="report-section-title">💰 Recaudación por día</div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Fecha</th><th>Total</th></tr></thead>
            <tbody>
            <?php if ($listaDias->num_rows === 0): ?>
                <tr><td colspan="2" class="empty-row">Sin datos</td></tr>
            <?php endif; ?>
            <?php while ($d = $listaDias->fetch_assoc()): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($d['fecha'])) ?></td>
                <td class="money">$<?= number_format($d['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Funciones más vendidas -->
<div class="report-section">
    <div class="report-section-title">🎟 Funciones más vendidas</div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Película</th><th>Fecha</th><th>Hora</th><th>Boletos</th></tr></thead>
            <tbody>
            <?php if ($listaFunciones->num_rows === 0): ?>
                <tr><td colspan="4" class="empty-row">Sin datos</td></tr>
            <?php endif; ?>
            <?php while ($f = $listaFunciones->fetch_assoc()): ?>
            <tr>
                <td><strong><?= htmlspecialchars($f['titulo']) ?></strong></td>
                <td><?= date('d/m/Y', strtotime($f['fecha'])) ?></td>
                <td><?= substr($f['hora'], 0, 5) ?></td>
                <td><?= $f['vendidos'] ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.stats-row        { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 16px; }
.stat-card-r      { background: var(--bg-card); border: 1px solid var(--s-border); border-radius: 12px; padding: 24px; border-top: 2px solid #3b82f6; }
.stat-card-r.red  { border-top-color: var(--primary); }
.stat-r-label     { font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--s-muted); margin-bottom: 10px; }
.stat-r-value     { font-size: 32px; font-weight: 800; }
.stat-card-r.red .stat-r-value { color: var(--primary); }
.report-section        { margin-bottom: 32px; }
.report-section-title  { font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--s-muted); margin-bottom: 12px; }
.money     { color: var(--primary); font-weight: 700; }
.empty-row { text-align: center; color: var(--s-muted); padding: 32px !important; }
</style>

<?php require '_layout_end.php'; ?>
