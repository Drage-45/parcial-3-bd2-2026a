<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Ventas del día';
$activeMenu = 'sales';
require '_layout.php';

$hoy = date('Y-m-d');

$resumenHoy = $conn->query("
    SELECT COUNT(*) AS ventas, COALESCE(SUM(total),0) AS ingresos, COUNT(DISTINCT id_cliente) AS clientes
    FROM venta WHERE estado='PAGADA' AND DATE(fecha)='$hoy'
")->fetch_assoc();

$porHora = $conn->query("
    SELECT HOUR(fecha) AS hora, COUNT(*) AS ventas, SUM(total) AS total
    FROM venta WHERE estado='PAGADA' AND DATE(fecha)='$hoy'
    GROUP BY HOUR(fecha) ORDER BY hora
");

$funcionesHoy = $conn->query("
    SELECT p.titulo, f.hora, COUNT(b.id_boleto) AS vendidos, SUM(b.precio) AS recaudado
    FROM boleto b
    INNER JOIN funcion_butaca fb ON b.id_funcion_butaca=fb.id_funcion_butaca
    INNER JOIN funcion f ON fb.id_funcion=f.id_funcion
    INNER JOIN pelicula p ON f.id_pelicula=p.id_pelicula
    WHERE DATE(b.fecha_venta)='$hoy'
    GROUP BY f.id_funcion ORDER BY vendidos DESC LIMIT 8
");

$topFunciones = $conn->query("
    SELECT p.titulo, f.fecha, f.hora, COUNT(b.id_boleto) AS vendidos, SUM(b.precio) AS recaudado
    FROM boleto b
    INNER JOIN funcion_butaca fb ON b.id_funcion_butaca=fb.id_funcion_butaca
    INNER JOIN funcion f ON fb.id_funcion=f.id_funcion
    INNER JOIN pelicula p ON f.id_pelicula=p.id_pelicula
    WHERE b.fecha_venta >= DATE_SUB('$hoy', INTERVAL 30 DAY)
    GROUP BY f.id_funcion ORDER BY vendidos DESC LIMIT 10
");

$ventasRecientes = $conn->query("
    SELECT v.id_venta, v.fecha, v.total, u.nombre AS cliente, u.correo AS email
    FROM venta v LEFT JOIN cliente u ON v.id_cliente=u.id_cliente
    WHERE v.estado='PAGADA' AND DATE(v.fecha)='$hoy'
    ORDER BY v.fecha DESC LIMIT 10
");

$resumenTotal = $conn->query("SELECT COUNT(*) AS ventas, COALESCE(SUM(total),0) AS ingresos FROM venta WHERE estado='PAGADA'")->fetch_assoc();

$horasData = array_fill(0, 24, 0);
while ($row = $porHora->fetch_assoc()) $horasData[(int)$row['hora']] = (int)$row['ventas'];
$porHora->data_seek(0);
?>
<style>
.kpi-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:28px; }
.kpi-card {
    background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);
    padding:20px;position:relative;overflow:hidden;transition:border-color var(--transition),transform var(--transition);
}
.kpi-card:hover { border-color:var(--border-accent);transform:translateY(-2px); }
.kpi-card::before { content:'';position:absolute;top:0;left:0;right:0;height:2px;background:var(--accent,var(--primary)); }
.kpi-icon  { font-size:22px;margin-bottom:10px; }
.kpi-label { font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);margin-bottom:6px; }
.kpi-value { font-family:'Bebas Neue',sans-serif;font-size:2rem;letter-spacing:1px;line-height:1; }
.kpi-value.colored { color:var(--accent,var(--primary)); }
.kpi-sub   { font-size:11px;color:var(--text-muted);margin-top:6px; }

.section-card { background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:22px; }
.section-title { font-size:13px;font-weight:700;color:var(--text);margin-bottom:16px;display:flex;align-items:center;gap:10px; }
.section-title .pill { background:var(--primary);color:#fff;font-size:9px;padding:3px 8px;border-radius:20px;letter-spacing:1px;text-transform:uppercase; }

.bar-chart { display:flex;align-items:flex-end;gap:3px;height:90px;padding-top:10px; }
.bar-wrap  { flex:1;display:flex;flex-direction:column;align-items:center;gap:3px; }
.bar       { width:100%;background:var(--primary);border-radius:3px 3px 0 0;min-height:3px;opacity:.75;transition:opacity var(--transition); }
.bar:hover { opacity:1; }
.bar-lbl   { font-size:8px;color:var(--text-muted); }

.tab-row { display:flex;gap:3px;margin-bottom:18px;background:var(--bg-elevated);border-radius:var(--radius-sm);padding:3px;width:fit-content; }
.tab     { padding:7px 16px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;color:var(--text-secondary);transition:all var(--transition); }
.tab.active { background:var(--primary);color:#fff; }
.tab-panel { display:none; }
.tab-panel.active { display:block; }

.rank-num        { font-family:'Bebas Neue',sans-serif;font-size:1.1rem;color:var(--text-muted);width:24px;text-align:center; }
.rank-num.gold   { color:#f59e0b; }
.rank-num.silver { color:#9ca3af; }
.rank-num.bronze { color:#b45309; }

.empty-state { text-align:center;padding:32px;color:var(--text-muted);font-size:13px; }

@media(max-width:900px){ .kpi-grid{grid-template-columns:repeat(2,1fr)} }
@media(max-width:480px){ .kpi-grid{grid-template-columns:1fr 1fr} }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
    <div>
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.8rem;letter-spacing:2px">📊 Ventas del día</h2>
        <p style="font-size:12px;color:var(--text-secondary);margin-top:2px">
            <?= date('d \d\e F \d\e Y') ?> — actualiza cada 2 min
        </p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="add_movie.php"      class="btn btn-secondary btn-sm">🎬 + Película</a>
        <a href="product_create.php" class="btn btn-secondary btn-sm">🍿 + Producto</a>
        <a href="dashboard.php"      class="btn btn-primary  btn-sm">🏠 Dashboard</a>
    </div>
</div>

<!-- KPIs -->
<div class="kpi-grid">
    <div class="kpi-card" style="--accent:#e50914">
        <div class="kpi-icon">🎟</div>
        <div class="kpi-label">Ventas hoy</div>
        <div class="kpi-value colored"><?= $resumenHoy['ventas'] ?></div>
        <div class="kpi-sub">transacciones</div>
    </div>
    <div class="kpi-card" style="--accent:#22c55e">
        <div class="kpi-icon">💰</div>
        <div class="kpi-label">Ingresos hoy</div>
        <div class="kpi-value colored">$<?= number_format($resumenHoy['ingresos'],0,',','.') ?></div>
        <div class="kpi-sub">ventas pagadas</div>
    </div>
    <div class="kpi-card" style="--accent:#3b82f6">
        <div class="kpi-icon">👤</div>
        <div class="kpi-label">Clientes únicos</div>
        <div class="kpi-value colored"><?= $resumenHoy['clientes'] ?></div>
        <div class="kpi-sub">compradores hoy</div>
    </div>
    <div class="kpi-card" style="--accent:#f59e0b">
        <div class="kpi-icon">📈</div>
        <div class="kpi-label">Total histórico</div>
        <div class="kpi-value colored">$<?= number_format($resumenTotal['ingresos'],0,',','.') ?></div>
        <div class="kpi-sub"><?= $resumenTotal['ventas'] ?> ventas totales</div>
    </div>
</div>

<!-- Gráfico por hora -->
<div class="section-card">
    <div class="section-title">🕐 Ventas por hora — hoy <span class="pill">Live</span></div>
    <?php
    $maxV    = max($horasData) ?: 1;
    $hasData = array_sum($horasData) > 0;
    if ($hasData): ?>
    <div class="bar-chart">
        <?php for ($h=8;$h<=23;$h++): $v=$horasData[$h]; $pct=($v/$maxV)*100; ?>
        <div class="bar-wrap">
            <div class="bar" style="height:<?= max($pct,3) ?>px" title="<?= $h ?>:00 — <?= $v ?> ventas"></div>
            <span class="bar-lbl"><?= $h ?></span>
        </div>
        <?php endfor; ?>
    </div>
    <p style="font-size:11px;color:var(--text-muted);margin-top:8px">Horas 8h–23h. Hover para detalle.</p>
    <?php else: ?>
        <div class="empty-state">📭 Sin ventas registradas hoy aún.</div>
    <?php endif; ?>
</div>

<!-- Funciones más vendidas -->
<div class="section-card">
    <div class="section-title">🎬 Funciones más vendidas</div>
    <div class="tab-row">
        <div class="tab active" data-panel="panel-hoy">Hoy</div>
        <div class="tab" data-panel="panel-top">Últimos 30 días</div>
    </div>

    <div class="tab-panel active" id="panel-hoy">
        <?php if ($funcionesHoy && $funcionesHoy->num_rows > 0): ?>
        <table>
            <thead><tr><th>#</th><th>Película</th><th>Hora</th><th>Boletos</th><th>Recaudado</th></tr></thead>
            <tbody>
            <?php $i=1; while($f=$funcionesHoy->fetch_assoc()): $rc=$i==1?'gold':($i==2?'silver':($i==3?'bronze':'')); ?>
            <tr>
                <td><span class="rank-num <?= $rc ?>"><?= $i ?></span></td>
                <td style="color:var(--text);font-weight:600"><?= htmlspecialchars($f['titulo']) ?></td>
                <td><?= substr($f['hora'],0,5) ?></td>
                <td><strong style="color:var(--primary)"><?= $f['vendidos'] ?></strong></td>
                <td style="color:#22c55e;font-weight:700">$<?= number_format($f['recaudado'],0,',','.') ?></td>
            </tr>
            <?php $i++; endwhile; ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state">🎟 Sin funciones vendidas hoy.</div><?php endif; ?>
    </div>

    <div class="tab-panel" id="panel-top">
        <?php if ($topFunciones && $topFunciones->num_rows > 0): ?>
        <table>
            <thead><tr><th>#</th><th>Película</th><th>Fecha</th><th>Hora</th><th>Boletos</th><th>Recaudado</th></tr></thead>
            <tbody>
            <?php $i=1; while($f=$topFunciones->fetch_assoc()): $rc=$i==1?'gold':($i==2?'silver':($i==3?'bronze':''));?>
            <tr>
                <td><span class="rank-num <?= $rc ?>"><?= $i ?></span></td>
                <td style="color:var(--text);font-weight:600"><?= htmlspecialchars($f['titulo']) ?></td>
                <td style="color:var(--text-secondary)"><?= date('d/m/Y',strtotime($f['fecha'])) ?></td>
                <td><?= substr($f['hora'],0,5) ?></td>
                <td><strong style="color:var(--primary)"><?= $f['vendidos'] ?></strong></td>
                <td style="color:#22c55e;font-weight:700">$<?= number_format($f['recaudado'],0,',','.') ?></td>
            </tr>
            <?php $i++; endwhile; ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state">📊 Sin datos en últimos 30 días.</div><?php endif; ?>
    </div>
</div>

<!-- Últimas transacciones -->
<div class="section-card">
    <div class="section-title">🧾 Últimas transacciones de hoy</div>
    <?php if ($ventasRecientes && $ventasRecientes->num_rows > 0): ?>
    <table>
        <thead><tr><th>ID</th><th>Hora</th><th>Cliente</th><th>Total</th><th>Estado</th></tr></thead>
        <tbody>
        <?php while($v=$ventasRecientes->fetch_assoc()): ?>
        <tr>
            <td style="color:var(--text-muted);font-size:12px">#<?= $v['id_venta'] ?></td>
            <td><?= date('H:i',strtotime($v['fecha'])) ?></td>
            <td>
                <div style="color:var(--text);font-weight:600"><?= htmlspecialchars($v['cliente']??'—') ?></div>
                <div style="font-size:11px;color:var(--text-muted)"><?= htmlspecialchars($v['email']??'') ?></div>
            </td>
            <td style="color:#22c55e;font-weight:700">$<?= number_format($v['total'],0,',','.') ?></td>
            <td><span class="badge badge-green">PAGADA</span></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="empty-state">🧾 Sin transacciones hoy.</div>
    <?php endif; ?>
</div>

<script>
document.querySelectorAll('.tab').forEach(t => {
    t.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(x=>x.classList.remove('active'));
        t.classList.add('active');
        document.getElementById(t.dataset.panel).classList.add('active');
    });
});
setTimeout(()=>location.reload(), 120000);
</script>

<?php require '_layout_end.php'; ?>