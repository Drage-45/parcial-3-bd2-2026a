<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Funciones';
$activeMenu = 'funciones';
require '_layout.php';

// Eliminar función
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM funcion_butaca WHERE id_funcion_butaca IN (SELECT id_funcion_butaca FROM funcion_butaca WHERE id_funcion=$id)");
    $conn->query("DELETE FROM funcion WHERE id_funcion=$id");
    header("Location: funciones.php?msg=deleted");
    exit();
}

// Filtro por película
$filtro = isset($_GET['pelicula']) ? intval($_GET['pelicula']) : 0;
$where  = $filtro ? "WHERE f.id_pelicula = $filtro" : "";

$funciones = $conn->query("
    SELECT f.*, p.titulo, s.nombre AS sala, s.capacidad,
           COUNT(fb.id_funcion_butaca) AS asientos_totales,
           SUM(fb.estado='Disponible') AS asientos_libres
    FROM funcion f
    INNER JOIN pelicula p ON f.id_pelicula = p.id_pelicula
    INNER JOIN sala s     ON f.id_sala     = s.id_sala
    LEFT  JOIN funcion_butaca fb ON fb.id_funcion = f.id_funcion
    $where
    GROUP BY f.id_funcion
    ORDER BY f.fecha DESC, f.hora DESC
");

$peliculas = $conn->query("SELECT id_pelicula, titulo FROM pelicula ORDER BY titulo");
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Funciones</h1>
        <p class="admin-page-sub">Gestiona las funciones programadas</p>
    </div>
    <a href="funcion_create.php" class="btn-admin-primary">+ Nueva Función</a>
</div>

<?php if (isset($_GET['msg'])): ?>
<div class="admin-alert <?= $_GET['msg']==='ok'?'success':'error' ?>">
    <?= $_GET['msg']==='ok' ? '✅ Función creada correctamente.' : '🗑 Función eliminada.' ?>
</div>
<?php endif; ?>

<!-- Filtro -->
<form method="GET" style="margin-bottom:20px;display:flex;gap:10px;align-items:center;">
    <select name="pelicula" class="admin-select" onchange="this.form.submit()">
        <option value="">Todas las películas</option>
        <?php while ($p = $peliculas->fetch_assoc()): ?>
        <option value="<?= $p['id_pelicula'] ?>" <?= $filtro==$p['id_pelicula']?'selected':'' ?>>
            <?= htmlspecialchars($p['titulo']) ?>
        </option>
        <?php endwhile; ?>
    </select>
    <?php if ($filtro): ?>
    <a href="funciones.php" class="btn-admin-ghost">Limpiar</a>
    <?php endif; ?>
</form>

<!-- Tabla -->
<div class="admin-table-wrap">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Película</th>
                <th>Sala</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Precio</th>
                <th>Asientos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($funciones->num_rows === 0): ?>
        <tr><td colspan="8" style="text-align:center;color:var(--s-muted);padding:40px;">No hay funciones registradas</td></tr>
        <?php endif; ?>
        <?php while ($f = $funciones->fetch_assoc()): ?>
        <tr>
            <td><?= $f['id_funcion'] ?></td>
            <td><strong><?= htmlspecialchars($f['titulo']) ?></strong></td>
            <td><?= htmlspecialchars($f['sala']) ?></td>
            <td><?= date('d/m/Y', strtotime($f['fecha'])) ?></td>
            <td><?= substr($f['hora'], 0, 5) ?></td>
            <td>$<?= number_format($f['precio'], 0, ',', '.') ?></td>
            <td>
                <span style="color:<?= $f['asientos_libres']>0?'#22c55e':'#ef4444' ?>">
                    <?= $f['asientos_libres'] ?>/<?= $f['asientos_totales'] ?> libres
                </span>
            </td>
            <td>
                <a href="funciones.php?delete=<?= $f['id_funcion'] ?>"
                   class="btn-admin-danger btn-sm"
                   onclick="return confirm('¿Eliminar esta función? Se liberarán todos sus asientos.')">
                   Eliminar
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require '_layout_end.php'; ?>
