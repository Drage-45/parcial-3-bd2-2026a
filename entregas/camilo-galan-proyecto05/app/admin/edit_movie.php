<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Editar Película';
$activeMenu = 'movies';
require '_layout.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header("Location: movies.php"); exit(); }

$pelicula = $conn->query("SELECT * FROM pelicula WHERE id_pelicula=$id")->fetch_assoc();
if (!$pelicula) { header("Location: movies.php"); exit(); }

$generos = $conn->query("SELECT id_genero, nombre FROM genero ORDER BY nombre");
$error   = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo        = trim($_POST['titulo']);
    $duracion      = trim($_POST['duracion']);
    $clasificacion = $_POST['clasificacion'];
    $sinopsis      = trim($_POST['sinopsis']);
    $id_genero     = (int)$_POST['id_genero'];
    $imagen        = $pelicula['imagen'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            if ($imagen) @unlink("../" . $imagen);
            $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../Images/" . $nombreImagen);
            $imagen = "Images/" . $nombreImagen;
        } else { $error = "Solo JPG, PNG o WEBP."; }
    }

    if (!$error) {
        $t  = $conn->real_escape_string($titulo);
        $d  = $conn->real_escape_string($duracion);
        $cl = $conn->real_escape_string($clasificacion);
        $s  = $conn->real_escape_string($sinopsis);
        $im = $conn->real_escape_string($imagen);
        $conn->query("UPDATE pelicula SET titulo='$t',duracion='$d',clasificacion='$cl',sinopsis='$s',imagen='$im',id_genero=$id_genero WHERE id_pelicula=$id");
        header("Location: movies.php?success=updated"); exit();
    }
    // Rellenar con POST si hay error
    $pelicula = array_merge($pelicula, $_POST);
}
?>
<style>
.create-layout { display:grid;grid-template-columns:1fr 300px;gap:22px;align-items:start; }
.file-label { display:flex;align-items:center;justify-content:center;gap:10px;background:var(--bg-elevated);border:1px dashed var(--border-accent);border-radius:var(--radius-sm);padding:14px;cursor:pointer;font-size:13px;font-weight:600;color:var(--text-secondary);transition:all var(--transition); }
.file-label:hover { border-color:var(--primary);color:var(--text); }
#imagen { display:none; }
.img-preview-box { width:100%;aspect-ratio:.67;background:var(--bg-elevated);border-radius:var(--radius-md);overflow:hidden;margin-bottom:14px;border:1px solid var(--border); }
.img-preview-box img { width:100%;height:100%;object-fit:cover;display:block; }
.img-empty { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:36px; }
@media(max-width:900px){.create-layout{grid-template-columns:1fr}}
</style>

<div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
    <a href="movies.php" class="btn btn-secondary btn-sm">← Volver</a>
    <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:2px">✏️ Editar Película</h2>
</div>

<?php if ($error): ?><div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="create-layout">

    <div class="form-card">
        <div class="form-card-title">Información de la película</div>
        <div class="form-grid">
            <div class="form-group full">
                <label>Título <span class="req">*</span></label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($pelicula['titulo']) ?>" required>
            </div>
            <div class="form-group">
                <label>Género <span class="req">*</span></label>
                <select name="id_genero" required>
                    <option value="">Seleccionar</option>
                    <?php while($g=$generos->fetch_assoc()): ?>
                    <option value="<?= $g['id_genero'] ?>" <?= $pelicula['id_genero']==$g['id_genero']?'selected':'' ?>>
                        <?= htmlspecialchars($g['nombre']) ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Clasificación <span class="req">*</span></label>
                <select name="clasificacion" required>
                    <?php foreach(['G','PG','PG-13','R','NC-17'] as $c): ?>
                    <option value="<?= $c ?>" <?= $pelicula['clasificacion']===$c?'selected':'' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Duración <span class="req">*</span></label>
                <input type="text" name="duracion" value="<?= htmlspecialchars($pelicula['duracion']) ?>" required>
            </div>
            <div class="form-group full">
                <label>Sinopsis</label>
                <textarea name="sinopsis"><?= htmlspecialchars($pelicula['sinopsis'] ?? '') ?></textarea>
            </div>
        </div>
        <div style="display:flex;gap:12px;margin-top:22px">
            <button type="submit" class="btn btn-primary">💾 Guardar cambios</button>
            <a href="movies.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-title">Poster actual</div>
        <div class="img-preview-box" id="preview-box">
            <?php if ($pelicula['imagen']): ?>
                <img id="preview-img" src="../<?= htmlspecialchars($pelicula['imagen']) ?>" alt="">
            <?php else: ?>
                <div class="img-empty" id="placeholder">🎬</div>
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
document.getElementById('imagen').addEventListener('change', function(){
    const r = new FileReader();
    r.onload = e => {
        const img = document.getElementById('preview-img');
        img.src = e.target.result; img.style.display='block';
        const ph = document.getElementById('placeholder');
        if(ph) ph.style.display='none';
    };
    if(this.files[0]) r.readAsDataURL(this.files[0]);
});
</script>

<?php require '_layout_end.php'; ?>
