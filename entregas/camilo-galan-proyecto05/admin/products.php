<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Productos';
$activeMenu = 'products';
require '_layout.php';

// Eliminar
if (isset($_GET['delete'])) {
    $id  = (int)$_GET['delete'];
    $row = $conn->query("SELECT imagen FROM producto WHERE id_producto=$id")->fetch_assoc();
    if ($row && $row['imagen']) @unlink("../" . $row['imagen']);
    $conn->query("DELETE FROM producto WHERE id_producto=$id");
    header("Location: products.php?success=deleted"); exit();
}

// Toggle estado
if (isset($_GET['toggle'])) {
    $id  = (int)$_GET['toggle'];
    $row = $conn->query("SELECT estado FROM producto WHERE id_producto=$id")->fetch_assoc();
    $nuevo = $row['estado'] === 'Disponible' ? 'No disponible' : 'Disponible';
    $conn->query("UPDATE producto SET estado='$nuevo' WHERE id_producto=$id");
    header("Location: products.php"); exit();
}

$search   = trim($_GET['q'] ?? '');
$catFilter = trim($_GET['cat'] ?? '');
$where    = "WHERE 1=1";
if ($search)    $where .= " AND nombre LIKE '%" . $conn->real_escape_string($search) . "%'";
if ($catFilter) $where .= " AND categoria='" . $conn->real_escape_string($catFilter) . "'";

$productos = $conn->query("SELECT * FROM producto $where ORDER BY id_producto DESC");
$total     = $conn->query("SELECT COUNT(*) AS n FROM producto $where")->fetch_assoc()['n'] ?? 0;
$categorias = $conn->query("SELECT DISTINCT categoria FROM producto ORDER BY categoria");
?>
<style>
.toolbar { display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px; }
.search-box { display:flex;align-items:center;background:var(--bg-elevated);border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;transition:border-color var(--transition); }
.search-box:focus-within { border-color:var(--primary); }
.search-box input { background:transparent;border:none;outline:none;color:var(--text);font-family:inherit;font-size:13px;padding:10px 14px;min-width:200px; }
.search-box input::placeholder { color:var(--text-muted); }
.search-box button { background:var(--primary);border:none;color:#fff;padding:10px 14px;cursor:pointer;font-size:14px; }
.cat-filter { display:flex;gap:6px;flex-wrap:wrap;margin-bottom:18px; }
.cat-btn { padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border);background:var(--bg-elevated);color:var(--text-secondary);text-decoration:none;transition:all var(--transition); }
.cat-btn:hover,.cat-btn.active { background:var(--primary);border-color:var(--primary);color:#fff; }
.counter { font-size:13px;color:var(--text-secondary); }
.counter strong { color:var(--text); }

.prod-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px; }
.prod-item {
    background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-md);
    overflow:hidden;transition:border-color var(--transition),transform var(--transition);
}
.prod-item:hover { border-color:var(--border-accent);transform:translateY(-3px); }
.prod-thumb { width:100%;aspect-ratio:1;object-fit:cover;display:block;background:var(--bg-elevated); }
.prod-thumb-placeholder { width:100%;aspect-ratio:1;background:var(--bg-elevated);display:flex;align-items:center;justify-content:center;font-size:32px; }
.prod-body  { padding:14px; }
.prod-nombre { font-size:13px;font-weight:700;color:var(--text);margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.prod-precio { font-family:'Bebas Neue',sans-serif;font-size:1.15rem;color:var(--primary);letter-spacing:1px;margin-bottom:6px; }
.prod-cat    { display:inline-block;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:700;background:rgba(255,255,255,.07);color:var(--text-secondary);margin-bottom:10px; }
.prod-actions { display:flex;gap:6px; }
.estado-dot { width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:5px; }
.dot-on  { background:#22c55e; }
.dot-off { background:#555; }
@media(max-width:600px){ .prod-grid{grid-template-columns:repeat(2,1fr)} }
</style>

<div class="toolbar">
    <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:2px">🍿 Productos</h2>
        <span class="counter"><strong><?= $total ?></strong> resultado<?= $total!=1?'s':'' ?></span>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
        <form method="GET" style="display:flex;gap:8px;align-items:center">
            <?php if($catFilter): ?><input type="hidden" name="cat" value="<?= htmlspecialchars($catFilter) ?>"><?php endif; ?>
            <div class="search-box">
                <input type="text" name="q" placeholder="Buscar producto..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">🔍</button>
            </div>
        </form>
        <a href="product_create.php" class="btn btn-primary">+ Nuevo producto</a>
    </div>
</div>

<!-- Filtro categoría -->
<div class="cat-filter">
    <a href="products.php<?= $search?"?q=".urlencode($search):'' ?>" class="cat-btn <?= !$catFilter?'active':'' ?>">Todos</a>
    <?php while($c=$categorias->fetch_assoc()): ?>
    <a href="products.php?cat=<?= urlencode($c['categoria']) ?><?= $search?"&q=".urlencode($search):'' ?>"
       class="cat-btn <?= $catFilter===$c['categoria']?'active':'' ?>">
        <?= htmlspecialchars($c['categoria']) ?>
    </a>
    <?php endwhile; ?>
</div>

<?php if (isset($_GET['success'])): ?>
<div class="alert alert-success" style="margin-bottom:18px">
    ✅ <?= $_GET['success']==='deleted' ? 'Producto eliminado.' : 'Producto guardado correctamente.' ?>
</div>
<?php endif; ?>

<?php if ($productos && $productos->num_rows > 0): ?>
<div class="prod-grid">
    <?php while ($p = $productos->fetch_assoc()): $activo = $p['estado']==='Disponible'; ?>
    <div class="prod-item" style="<?= !$activo ? 'opacity:.55' : '' ?>">
        <?php if ($p['imagen']): ?>
            <img class="prod-thumb" src="../<?= htmlspecialchars($p['imagen']) ?>" alt="">
        <?php else: ?>
            <div class="prod-thumb-placeholder">🍿</div>
        <?php endif; ?>
        <div class="prod-body">
            <div class="prod-nombre" title="<?= htmlspecialchars($p['nombre']) ?>"><?= htmlspecialchars($p['nombre']) ?></div>
            <div class="prod-precio">$<?= number_format($p['precio'],0,',','.') ?></div>
            <div>
                <span class="prod-cat"><?= htmlspecialchars($p['categoria'] ?? '') ?></span>
                <span style="font-size:11px;color:var(--text-muted)">
                    <span class="estado-dot <?= $activo?'dot-on':'dot-off' ?>"></span>
                    <?= $p['estado'] ?>
                </span>
            </div>
            <div class="prod-actions">
                <a href="edit_product.php?id=<?= $p['id_producto'] ?>" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center">✏️</a>
                <a href="products.php?toggle=<?= $p['id_producto'] ?>" class="btn btn-secondary btn-sm" title="Cambiar estado">
                    <?= $activo ? '🔴' : '🟢' ?>
                </a>
                <a href="products.php?delete=<?= $p['id_producto'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('¿Eliminar este producto?')">🗑</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
    <div style="text-align:center;padding:60px 20px;color:var(--text-muted)">
        <div style="font-size:40px;margin-bottom:12px">🍿</div>
        <p style="font-size:14px">No hay productos<?= $search?" con ese nombre":""?>.</p>
        <?php if(!$search): ?><a href="product_create.php" class="btn btn-primary" style="margin-top:16px">Agregar primer producto</a><?php endif; ?>
    </div>
<?php endif; ?>

<?php require '_layout_end.php'; ?>
