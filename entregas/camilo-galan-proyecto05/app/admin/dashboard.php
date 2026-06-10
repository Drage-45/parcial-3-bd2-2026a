<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Dashboard';
$activeMenu = 'dashboard';
require '_layout.php';

$hoy = date('Y-m-d');

$kpis = [
    'ventas_hoy'    => $conn->query("SELECT COUNT(*) AS n FROM venta WHERE estado='PAGADA' AND DATE(fecha)='$hoy'")->fetch_assoc()['n'] ?? 0,
    'ingresos_hoy'  => $conn->query("SELECT COALESCE(SUM(total),0) AS t FROM venta WHERE estado='PAGADA' AND DATE(fecha)='$hoy'")->fetch_assoc()['t'] ?? 0,
    'ventas_mes'    => $conn->query("SELECT COUNT(*) AS n FROM venta WHERE estado='PAGADA' AND MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE())")->fetch_assoc()['n'] ?? 0,
    'ingresos_mes'  => $conn->query("SELECT COALESCE(SUM(total),0) AS t FROM venta WHERE estado='PAGADA' AND MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE())")->fetch_assoc()['t'] ?? 0,
    'peliculas'     => $conn->query("SELECT COUNT(*) AS n FROM pelicula")->fetch_assoc()['n'] ?? 0,
    'productos'     => $conn->query("SELECT COUNT(*) AS n FROM producto WHERE estado='Disponible'")->fetch_assoc()['n'] ?? 0,
    'funciones_hoy' => $conn->query("SELECT COUNT(*) AS n FROM funcion WHERE DATE(fecha)='$hoy'")->fetch_assoc()['n'] ?? 0,
];

$ultVentas = $conn->query("
    SELECT v.id_venta, v.fecha, v.total, u.nombre AS cliente
    FROM venta v LEFT JOIN cliente u ON v.id_cliente = u.id_cliente
    WHERE v.estado='PAGADA' ORDER BY v.fecha DESC LIMIT 6
");

$topPeliculas = $conn->query("
    SELECT p.titulo, p.imagen, COUNT(b.id_boleto) AS vendidos
    FROM boleto b
    INNER JOIN funcion_butaca fb ON b.id_funcion_butaca=fb.id_funcion_butaca
    INNER JOIN funcion f ON fb.id_funcion=f.id_funcion
    INNER JOIN pelicula p ON f.id_pelicula=p.id_pelicula
    GROUP BY p.id_pelicula ORDER BY vendidos DESC LIMIT 5
");

$acciones = [
    ['icon'=>'🎬','label'=>'Nueva Película',  'href'=>'add_movie.php',      'color'=>'#e50914'],
    ['icon'=>'🍿','label'=>'Nuevo Producto',  'href'=>'product_create.php', 'color'=>'#f59e0b'],
    ['icon'=>'📊','label'=>'Ventas del día',  'href'=>'admin_sales.php',    'color'=>'#22c55e'],
    ['icon'=>'📈','label'=>'Reportes',        'href'=>'../reports.php',     'color'=>'#3b82f6'],
    ['icon'=>'🎞','label'=>'Ver películas',   'href'=>'movies.php',         'color'=>'#8b5cf6'],
    ['icon'=>'📦','label'=>'Ver productos',   'href'=>'products.php',       'color'=>'#ec4899'],
];
?>
<style>
.dash-greeting { margin-bottom:28px; }
.dash-greeting h1 { font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:2px; margin-bottom:4px; }
.dash-greeting p  { font-size:13px; color:var(--text-secondary); }
.dash-greeting strong { color:var(--primary); }

.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:28px; }
.kpi-card {
    background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg);
    padding:20px; position:relative; overflow:hidden;
    transition:border-color var(--transition),transform var(--transition);
}
.kpi-card:hover { border-color:var(--border-accent); transform:translateY(-2px); }
.kpi-card::before { content:''; position:absolute; top:0;left:0;right:0; height:2px; background:var(--accent,var(--primary)); }
.kpi-icon   { font-size:22px; margin-bottom:10px; }
.kpi-label  { font-size:10px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px; }
.kpi-value  { font-family:'Bebas Neue',sans-serif; font-size:2rem; letter-spacing:1px; color:var(--text); line-height:1; }
.kpi-value.colored { color:var(--accent,var(--primary)); }
.kpi-sub    { font-size:11px; color:var(--text-muted); margin-top:6px; }

.section-title { font-size:12px; font-weight:700; letter-spacing:2px; text-transform:uppercase; color:var(--text-muted); margin-bottom:14px; }
.actions-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:28px; }
.action-card {
    background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-md);
    padding:18px 16px; display:flex; align-items:center; gap:12px;
    text-decoration:none; transition:all var(--transition);
}
.action-card:hover { border-color:var(--ac,var(--primary)); background:rgba(255,255,255,.03); transform:translateY(-2px); }
.action-icon { width:40px;height:40px; border-radius:10px; display:flex;align-items:center;justify-content:center; font-size:18px; flex-shrink:0; }
.action-label { font-size:13px; font-weight:600; color:var(--text); }

.dash-bottom { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.dash-panel { background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden; }
.dash-panel-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.dash-panel-header h3 { font-size:13px; font-weight:700; }
.dash-panel-header a  { font-size:11px; color:var(--primary); text-decoration:none; font-weight:600; }

.venta-row { display:flex;align-items:center;justify-content:space-between; padding:12px 20px; border-bottom:1px solid var(--border); transition:background var(--transition); }
.venta-row:last-child { border-bottom:none; }
.venta-row:hover { background:rgba(255,255,255,.025); }
.v-cliente { font-size:13px; font-weight:600; color:var(--text); }
.v-hora    { font-size:11px; color:var(--text-muted); }
.venta-total { font-family:'Bebas Neue',sans-serif; font-size:1.1rem; letter-spacing:1px; color:#22c55e; }

.movie-row { display:flex;align-items:center;gap:12px; padding:12px 20px; border-bottom:1px solid var(--border); transition:background var(--transition); }
.movie-row:last-child { border-bottom:none; }
.movie-row:hover { background:rgba(255,255,255,.025); }
.movie-rank { font-family:'Bebas Neue',sans-serif; font-size:1.1rem; color:var(--text-muted); width:22px; text-align:center; }
.movie-rank.g1{color:#f59e0b}.movie-rank.g2{color:#9ca3af}.movie-rank.g3{color:#b45309}
.movie-thumb { width:36px;height:52px;object-fit:cover;border-radius:6px;flex-shrink:0;background:var(--bg-elevated); }
.m-titulo    { font-size:13px; font-weight:600; color:var(--text); }
.m-vendidos  { font-size:11px; color:var(--text-muted); }

@media(max-width:1200px){.kpi-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:900px){.actions-grid{grid-template-columns:1fr 1fr}.dash-bottom{grid-template-columns:1fr}}
@media(max-width:480px){.kpi-grid{grid-template-columns:1fr 1fr}.actions-grid{grid-template-columns:1fr}}
</style>

<div class="dash-greeting">
    <h1>👋 Hola, <?= htmlspecialchars(explode(' ', $adminNombre)[0]) ?></h1>
    <p>Hoy es <strong><?= date('d \d\e F \d\e Y') ?></strong> — resumen de Cinemas Star.</p>
</div>

<div class="kpi-grid">
    <div class="kpi-card" style="--accent:#e50914">
        <div class="kpi-icon">🎟</div>
        <div class="kpi-label">Ventas hoy</div>
        <div class="kpi-value colored"><?= $kpis['ventas_hoy'] ?></div>
        <div class="kpi-sub">transacciones</div>
    </div>
    <div class="kpi-card" style="--accent:#22c55e">
        <div class="kpi-icon">💰</div>
        <div class="kpi-label">Ingresos hoy</div>
        <div class="kpi-value colored">$<?= number_format($kpis['ingresos_hoy'],0,',','.') ?></div>
        <div class="kpi-sub">ventas pagadas</div>
    </div>
    <div class="kpi-card" style="--accent:#3b82f6">
        <div class="kpi-icon">📅</div>
        <div class="kpi-label">Ventas del mes</div>
        <div class="kpi-value colored"><?= $kpis['ventas_mes'] ?></div>
        <div class="kpi-sub">$<?= number_format($kpis['ingresos_mes'],0,',','.') ?></div>
    </div>
    <div class="kpi-card" style="--accent:#8b5cf6">
        <div class="kpi-icon">🎬</div>
        <div class="kpi-label">Funciones hoy</div>
        <div class="kpi-value colored"><?= $kpis['funciones_hoy'] ?></div>
        <div class="kpi-sub"><?= $kpis['peliculas'] ?> películas activas</div>
    </div>
</div>

<div class="section-title">Acciones rápidas</div>
<div class="actions-grid">
    <?php foreach ($acciones as $ac): ?>
    <a href="<?= $ac['href'] ?>" class="action-card" style="--ac:<?= $ac['color'] ?>">
        <div class="action-icon" style="background:<?= $ac['color'] ?>22"><?= $ac['icon'] ?></div>
        <span class="action-label"><?= $ac['label'] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<div class="dash-bottom">
    <div class="dash-panel">
        <div class="dash-panel-header">
            <h3>🧾 Últimas ventas</h3>
            <a href="admin_sales.php">Ver todas →</a>
        </div>
        <?php if ($ultVentas && $ultVentas->num_rows > 0):
            while ($v = $ultVentas->fetch_assoc()): ?>
            <div class="venta-row">
                <div>
                    <div class="v-cliente"><?= htmlspecialchars($v['cliente'] ?? 'Cliente') ?></div>
                    <div class="v-hora"><?= date('d/m H:i', strtotime($v['fecha'])) ?></div>
                </div>
                <div class="venta-total">$<?= number_format($v['total'],0,',','.') ?></div>
            </div>
        <?php endwhile; else: ?>
            <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:13px">Sin ventas aún.</div>
        <?php endif; ?>
    </div>

    <div class="dash-panel">
        <div class="dash-panel-header">
            <h3>🎬 Más vendidas</h3>
            <a href="../reports.php">Ver reporte →</a>
        </div>
        <?php $rankC=['g1','g2','g3','','']; $i=0;
        if ($topPeliculas && $topPeliculas->num_rows > 0):
            while ($p = $topPeliculas->fetch_assoc()): ?>
            <div class="movie-row">
                <span class="movie-rank <?= $rankC[$i] ?>"><?= $i+1 ?></span>
                <?php if ($p['imagen']): ?>
                    <img class="movie-thumb" src="../<?= htmlspecialchars($p['imagen']) ?>" alt="">
                <?php else: ?>
                    <div class="movie-thumb" style="display:flex;align-items:center;justify-content:center">🎬</div>
                <?php endif; ?>
                <div>
                    <div class="m-titulo"><?= htmlspecialchars($p['titulo']) ?></div>
                    <div class="m-vendidos"><?= $p['vendidos'] ?> boletos</div>
                </div>
            </div>
        <?php $i++; endwhile;
        else: ?>
            <div style="padding:32px;text-align:center;color:var(--text-muted);font-size:13px">Sin datos aún.</div>
        <?php endif; ?>
    </div>
</div>

<?php require '_layout_end.php'; ?>