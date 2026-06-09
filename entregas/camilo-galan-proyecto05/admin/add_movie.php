<?php
// session_start() — gestionado por _layout.php
$pageTitle  = 'Nueva Película';
$activeMenu = 'add_movie';
require '_layout.php';

$error = "";

$generos = $conn->query("SELECT id_genero, nombre FROM genero ORDER BY nombre");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo        = trim($_POST['titulo']);
    $duracion      = trim($_POST['duracion']);
    $clasificacion = $_POST['clasificacion'];
    $sinopsis      = trim($_POST['sinopsis']);
    $id_genero     = (int) $_POST['id_genero'];
    $imagen        = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $ext       = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp'])) {
            $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../Images/" . $nombreImagen);
            $imagen = "Images/" . $nombreImagen;
        } else {
            $error = "Solo se permiten JPG, PNG o WEBP.";
        }
    }

    if (!$error) {
        $t  = $conn->real_escape_string($titulo);
        $d  = $conn->real_escape_string($duracion);
        $cl = $conn->real_escape_string($clasificacion);
        $s  = $conn->real_escape_string($sinopsis);
        $im = $conn->real_escape_string($imagen);
        $sql = "INSERT INTO pelicula(titulo,duracion,clasificacion,sinopsis,imagen,id_genero)
                VALUES('$t','$d','$cl','$s','$im',$id_genero)";
        if ($conn->query($sql)) { header("Location: movies.php?success=1"); exit(); }
        else $error = "Error BD: " . $conn->error;
    }
}
?>
<style>
.create-layout { display:grid; grid-template-columns:1fr 300px; gap:22px; align-items:start; }
.file-label {
    display:flex;align-items:center;justify-content:center;gap:10px;
    background:var(--bg-elevated);border:1px dashed var(--border-accent);
    border-radius:var(--radius-sm);padding:14px;cursor:pointer;
    font-size:13px;font-weight:600;color:var(--text-secondary);
    transition:all var(--transition);
}
.file-label:hover { border-color:var(--primary);color:var(--text); }
#imagen { display:none; }
.img-preview-box {
    width:100%;aspect-ratio:.67;background:var(--bg-elevated);
    border-radius:var(--radius-md);overflow:hidden;margin-bottom:14px;
    display:flex;align-items:center;justify-content:center;
    border:1px dashed var(--border);font-size:36px;opacity:.4;
}
.img-preview-box img { width:100%;height:100%;object-fit:cover;display:none; }
@media(max-width:900px){.create-layout{grid-template-columns:1fr}}
</style>

<div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
    <a href="movies.php" class="btn btn-secondary btn-sm">← Volver</a>
    <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:2px">🎬 Nueva Película</h2>
</div>

<?php if ($error): ?><div class="alert alert-error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>

<form method="POST" enctype="multipart/form-data">
<div class="create-layout">

    <div class="form-card">
        <div class="form-card-title">Información de la película</div>
        <div class="form-grid">
            <div class="form-group full">
                <label>Título <span class="req">*</span></label>
                <input type="text" name="titulo" placeholder="Ej: Spider-Man: No Way Home"
                       value="<?= htmlspecialchars($_POST['titulo']??'') ?>" required>
            </div>
            <div class="form-group">
                <label>Género <span class="req">*</span></label>
                <select name="id_genero" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <?php while($g=$generos->fetch_assoc()): ?>
                    <option value="<?= $g['id_genero'] ?>" <?= (($_POST['id_genero']??'')==$g['id_genero'])?'selected':'' ?>>
                        <?= htmlspecialchars($g['nombre']) ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Clasificación <span class="req">*</span></label>
                <select name="clasificacion" required>
                    <option value="" disabled selected>Seleccionar</option>
                    <?php foreach(['G'=>'G — Todo público','PG'=>'PG — Guía parental','PG-13'=>'PG-13 — +13','R'=>'R — Restringida','NC-17'=>'NC-17 — Solo adultos'] as $v=>$l): ?>
                    <option value="<?= $v ?>" <?= (($_POST['clasificacion']??'')===$v)?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Duración <span class="req">*</span></label>
                <input type="text" name="duracion" placeholder="Ej: 2h 10min"
                       value="<?= htmlspecialchars($_POST['duracion']??'') ?>" required>
            </div>
            <div class="form-group full">
                <label>Sinopsis</label>
                <textarea name="sinopsis" placeholder="Descripción de la película..."><?= htmlspecialchars($_POST['sinopsis']??'') ?></textarea>
            </div>
        </div>
        <div style="display:flex;gap:12px;margin-top:22px">
            <button type="submit" class="btn btn-primary">✅ Guardar película</button>
            <a href="movies.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-title">Poster / Imagen</div>
        <div class="img-preview-box" id="preview-box">
            <span id="placeholder">🎬</span>
            <img id="preview-img" src="" alt="">
        </div>
        <label class="file-label" for="imagen">📤 Seleccionar imagen</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required>
        <p style="margin-top:10px;font-size:11px;color:var(--text-muted);text-align:center">
            JPG, PNG o WEBP<br>Recomendado: póster 2:3
        </p>
    </div>

</div>
</form>

<script>
document.getElementById('imagen').addEventListener('change', function(){
    const file = this.files[0]; if(!file) return;
    const r = new FileReader();
    r.onload = e => {
        const img = document.getElementById('preview-img');
        img.src = e.target.result; img.style.display='block';
        document.getElementById('placeholder').style.display='none';
    };
    r.readAsDataURL(file);
});
</script>

<?php require '_layout_end.php'; ?>
