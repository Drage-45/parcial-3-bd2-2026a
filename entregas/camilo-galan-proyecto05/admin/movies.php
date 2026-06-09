<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Películas';
$activeMenu = 'movies';
require '_layout.php';

$msg = "";

// Eliminar
if (isset($_GET['delete'])) {
    $id  = (int)$_GET['delete'];
    $row = $conn->query("SELECT imagen FROM pelicula WHERE id_pelicula=$id")->fetch_assoc();
    if ($row && $row['imagen']) @unlink("../" . $row['imagen']);
    $conn->query("DELETE FROM pelicula WHERE id_pelicula=$id");
    header("Location: movies.php?success=deleted"); exit();
}

// Cambiar estado (activa / inactiva si tienes columna estado)
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $conn->query("UPDATE pelicula SET activa = !activa WHERE id_pelicula=$id");
    header("Location: movies.php"); exit();
}

$search = trim($_GET['q'] ?? '');
$where  = $search ? "WHERE p.titulo LIKE '%" . $conn->real_escape_string($search) . "%'" : "";

$peliculas = $conn->query("
    SELECT p.*, g.nombre AS genero
    FROM pelicula p
    LEFT JOIN genero g ON p.id_genero = g.id_genero
    $where
    ORDER BY p.id_pelicula DESC
");

$total = $conn->query("SELECT COUNT(*) AS n FROM pelicula $where")->fetch_assoc()['n'] ?? 0;
?>
<style>
.toolbar { display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px; }
.search-box { display:flex;align-items:center;gap:0;background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;transition:border-color var(--transition); }
.search-box:focus-within { border-color:var(--primary); }
.search-box input { background:transparent;border:none;outline:none;color:var(--text);font-family:inherit;font-size:13px;padding:10px 14px;min-width:220px; }
.search-box input::placeholder { color:var(--text-muted); }
.search-box button { background:var(--primary);border:none;color:#fff;padding:10px 14px;cursor:pointer;font-size:14px; }

.movie-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px; }
.movie-item {
    background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);
    overflow:hidden;transition:border-color var(--transition),transform var(--transition);
}
.movie-item:hover { border-color:var(--border-accent);transform:translateY(-3px); }
.movie-thumb { width:100%;aspect-ratio:.67;object-fit:cover;display:block;background:var(--bg-elevated); }
.movie-thumb-placeholder { width:100%;aspect-ratio:.67;background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;font-size:32px; }
.movie-body  { padding:12px; }
.movie-titulo { font-size:13px;font-weight:700;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.movie-meta   { font-size:11px;color:var(--text-muted);margin-bottom:10px; }
.movie-actions { display:flex;gap:6px; }
.counter { font-size:13px;color:var(--text-secondary);align-self:center; }
.counter strong { color:var(--text); }
@media(max-width:600px){ .movie-grid{grid-template-columns:repeat(2,1fr)} }
</style>

<div class="toolbar">
    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:2px">🎬 Películas</h2>
        <span class="counter"><strong><?= $total ?></strong> en total</span>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
        <form method="GET" action="">
            <div class="search-box">
                <input type="text" name="q" placeholder="Buscar película..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">🔍</button>
            </div>
        </form>
        <a href="add_movie.php" class="btn btn-primary">+ Nueva película</a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success" style="margin-bottom:18px">
    ✅ <?= $_GET['success']==='deleted' ? 'Película eliminada.' : 'Película guardada correctamente.' ?>
</div>
<?php endif; ?>

<?php if ($peliculas && $peliculas->num_rows > 0): ?>
<div class="movie-grid">
    <?php while ($p = $peliculas->fetch_assoc()): ?>
    <div class="movie-item">
        <?php if ($p['imagen']): ?>
            <img class="movie-thumb" src="../<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>">
        <?php else: ?>
            <div class="movie-thumb-placeholder">🎬</div>
        <?php endif; ?>
        <div class="movie-body">
            <div class="movie-titulo" title="<?= htmlspecialchars($p['titulo']) ?>"><?= htmlspecialchars($p['titulo']) ?></div>
            <div class="movie-meta">
                <?= htmlspecialchars($p['genero'] ?? '—') ?> · <?= htmlspecialchars($p['clasificacion'] ?? '') ?><br>
                <?= htmlspecialchars($p['duracion'] ?? '') ?>
            </div>
            <div class="movie-actions">
                <a href="edit_movie.php?id=<?= $p['id_pelicula'] ?>" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center">✏️</a>
                <a href="movies.php?delete=<?= $p['id_pelicula'] ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Eliminar esta película?')">🗑</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
    <div style="text-align:center;padding:60px 20px;color:var(--text-muted)">
        <div style="font-size:40px;margin-bottom:12px">🎬</div>
        <p style="font-size:14px">No hay películas<?= $search ? " con ese título" : "" ?>.</p>
        <?php if (!$search): ?><a href="add_movie.php" class="btn btn-primary" style="margin-top:16px">Agregar primera película</a><?php endif; ?>
    </div>
<?php endif; ?>

<?php require '_layout_end.php'; ?>
