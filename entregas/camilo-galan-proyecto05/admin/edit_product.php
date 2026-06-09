<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Editar Producto';
$activeMenu = 'products';
require '_layout.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: products.php"); exit(); }

$producto = $conn->query("SELECT * FROM producto WHERE id_producto=$id")->fetch_assoc();
if (!$producto) { header("Location: products.php"); exit(); }

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio      = (float)$_POST['precio'];
    $categoria   = $_POST['categoria'];
    $estado      = $_POST['estado'];
    $imagen      = $producto['imagen'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            if ($imagen) @unlink("../" . $imagen);
            $dir = "../Images/productos/";
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $nombreImagen);
            $imagen = "Images/productos/" . $nombreImagen;
        } else { $error = "Solo JPG, PNG o WEBP."; }
    }

    if (!$error) {
        $n  = $conn->real_escape_string($nombre);
        $de = $conn->real_escape_string($descripcion);
        $ca = $conn->real_escape_string($categoria);
        $es = $conn->real_escape_string($estado);
        $im = $conn->real_escape_string($imagen);
        $conn->query("UPDATE producto SET nombre='$n',descripcion='$de',precio=$precio,categoria='$ca',estado='$es',imagen='$im' WHERE id_producto=$id");
        header("Location: products.php?success=updated"); exit();
    }
    $producto = array_merge($producto, $_POST);
}
?>
<style>
.create-layout { display:grid;grid-template-columns:1fr 280px;gap:22px;align-items:start; }
.chip-row { display:flex;gap:8px;flex-wrap:wrap;margin-top:4px; }
.chip { padding:7px 14px;border-radius:20px;font-size:12px;font-weight:600;border:1px solid var(--border);background:var(--bg-elevated);color:var(--text-secondary);cursor:pointer;transition:all var(--transition); }
.chip.active,.chip:hover { background:var(--primary);border-color:var(--primary);color:#fff; }
.file-label { display:flex;align-items:center;justify-content:center;gap:8px;background:var(--bg-elevated);border:1px dashed var(--border-accent);border-radius:var(--radius-sm);padding:13px;cursor:pointer;font-size:13px;font-weight:600;color:var(--text-secondary);transition:all var(--transition); }
.file-label:hover { border-color:var(--primary);color:var(--text); }
#imagen { display:none; }
.img-preview-box { width:100%;aspect-ratio:1;background:var(--bg-elevated);border-radius:var(--radius-md);overflow:hidden;margin-bottom:14px;border:1px solid var(--border); }
.img-preview-box img { width:100%;height:100%;object-fit:cover;display:block; }
.img-empty { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:36px; }
.input-prefix { position:relative; }
.input-prefix em { position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--primary);font-weight:700;font-style:normal; }
.input-prefix input { padding-left:26px; }
@media(max-width:900px){.create-layout{grid-template-columns:1fr}}
</style>

<div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
    <a href="products.php" class="btn btn-secondary btn-sm">← Volver</a>
    <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:2px">✏️ Editar Producto</h2>
</div>

<?php if ($error): ?><div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="create-layout">

    <div class="form-card">
        <div class="form-card-title">Información del producto</div>
        <div class="form-grid">
            <div class="form-group full">
                <label>Nombre <span class="req">*</span></label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>
            <div class="form-group">
                <label>Precio <span class="req">*</span></label>
                <div class="input-prefix">
                    <em>$</em>
                    <input type="number" name="precio" min="0" step="100" value="<?= $producto['precio'] ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="Disponible"    <?= $producto['estado']==='Disponible'?'selected':'' ?>>Disponible</option>
                    <option value="No disponible" <?= $producto['estado']==='No disponible'?'selected':'' ?>>No disponible</option>
                </select>
            </div>
            <div class="form-group full">
                <label>Categoría <span class="req">*</span></label>
                <select name="categoria" id="categoria" required style="display:none">
                    <?php foreach(['Combo','Bebida','Snack','Dulce','Otro'] as $c): ?>
                    <option value="<?= $c ?>" <?= $producto['categoria']===$c?'selected':'' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="chip-row">
                    <?php $cats=['Combo'=>'🍿','Bebida'=>'🥤','Snack'=>'🧆','Dulce'=>'🍬','Otro'=>'📦'];
                    foreach ($cats as $cv => $ci): ?>
                    <span class="chip <?= $producto['categoria']===$cv?'active':'' ?>" data-val="<?= $cv ?>"><?= $ci ?> <?= $cv ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group full">
                <label>Descripción <span class="req">*</span></label>
                <textarea name="descripcion" required><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
            </div>
        </div>
        <div style="display:flex;gap:12px;margin-top:22px">
            <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
            <a href="products.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-title">Imagen actual</div>
        <div class="img-preview-box" id="preview-box">
            <?php if ($producto['imagen']): ?>
                <img id="preview-img" src="../<?= htmlspecialchars($producto['imagen']) ?>" alt="">
            <?php else: ?>
                <div class="img-empty">🍿</div>
                <img id="preview-img" src="" alt="" style="display:none">
            <?php endif; ?>
        </div>
        <label class="file-label" for="imagen">📤 Cambiar imagen</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
        <p style="margin-top:10px;font-size:11px;color:var(--text-muted);text-align:center">Dejar vacío para mantener la actual</p>
    </div>

</div>
</form>

<script>
document.querySelectorAll('.chip').forEach(chip => {
    chip.addEventListener('click', () => {
        document.querySelectorAll('.chip').forEach(c=>c.classList.remove('active'));
        chip.classList.add('active');
        document.getElementById('categoria').value = chip.dataset.val;
    });
});
document.getElementById('imagen').addEventListener('change', function(){
    const r = new FileReader();
    r.onload = e => {
        const img = document.getElementById('preview-img');
        img.src = e.target.result; img.style.display='block';
        const ph = document.querySelector('.img-empty');
        if(ph) ph.style.display='none';
    };
    if(this.files[0]) r.readAsDataURL(this.files[0]);
});
</script>

<?php require '_layout_end.php'; ?>
