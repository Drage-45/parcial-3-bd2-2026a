<?php

session_start();

require '../config/conection.php';

/*
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
*/

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre      = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio      = (float) $_POST['precio'];
    $categoria   = $_POST['categoria'];
    $imagen      = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {

        $ext       = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $permitidos = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $permitidos)) {

            $dir = "../Images/productos/";
            if (!is_dir($dir)) mkdir($dir, 0755, true);

            $nombreImagen = time() . "_" . basename($_FILES['imagen']['name']);
            $ruta         = $dir . $nombreImagen;

            move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
            $imagen = "Images/productos/" . $nombreImagen;

        } else {
            $error = "Solo se permiten imágenes JPG, PNG o WEBP.";
        }
    }

    if (!$error) {

        $nombre_s      = $conn->real_escape_string($nombre);
        $descripcion_s = $conn->real_escape_string($descripcion);
        $imagen_s      = $conn->real_escape_string($imagen);
        $categoria_s   = $conn->real_escape_string($categoria);

        $sql = "
        INSERT INTO producto
            (nombre, descripcion, precio, imagen, categoria, estado)
        VALUES
            ('$nombre_s', '$descripcion_s', $precio, '$imagen_s', '$categoria_s', 'Disponible')
        ";

        if ($conn->query($sql)) {
            header("Location: products.php?success=1");
            exit();
        } else {
            $error = "Error al guardar: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Producto — Cinemas Star</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* ── ADMIN FORM ─────────────────────────────── */
        .admin-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 36px;
        }
        .admin-header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.2rem;
            letter-spacing: 2px;
            color: var(--text);
        }
        .admin-header .badge {
            background: var(--primary);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* ── LAYOUT ─────────────────────────────────── */
        .create-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 28px;
            align-items: start;
            max-width: 960px;
        }

        /* ── FORM CARD ──────────────────────────────── */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 36px;
        }
        .form-card h2 {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 24px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .form-group.full { grid-column: 1 / -1; }

        .form-group label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-secondary);
        }
        .form-group label span { color: var(--primary); }

        .form-group input,
        .form-group select,
        .form-group textarea {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            padding: 12px 14px;
            transition: border-color var(--transition), box-shadow var(--transition);
            outline: none;
            width: 100%;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .form-group select option { background: var(--bg-card); }
        .form-group textarea { resize: vertical; min-height: 90px; }

        /* precio con símbolo */
        .input-prefix {
            position: relative;
        }
        .input-prefix span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-weight: 700;
            font-size: 14px;
            pointer-events: none;
        }
        .input-prefix input { padding-left: 28px; }

        /* ── SIDEBAR IMAGEN ─────────────────────────── */
        .sidebar-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px;
        }
        .sidebar-card h2 {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
        }

        .img-preview-box {
            width: 100%;
            aspect-ratio: 1;
            background: var(--bg-elevated);
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
        }
        .img-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
        .img-preview-box .placeholder {
            font-size: 40px;
            opacity: .4;
        }

        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--bg-elevated);
            border: 1px dashed var(--border-accent);
            border-radius: var(--radius-md);
            padding: 13px;
            cursor: pointer;
            transition: border-color var(--transition), background var(--transition);
            width: 100%;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
        }
        .file-label:hover {
            border-color: var(--primary);
            background: rgba(229,9,20,.06);
            color: var(--text);
        }
        #imagen { display: none; }

        /* ── CATEG CHIPS ────────────────────────────── */
        .chip-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 4px;
        }
        .chip {
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid var(--border);
            background: var(--bg-elevated);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all var(--transition);
        }
        .chip.active, .chip:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        /* ── ALERT ──────────────────────────────────── */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            max-width: 960px;
        }
        .alert.error { background: rgba(229,9,20,.15); border: 1px solid var(--primary); color: var(--primary); }

        /* ── ACTIONS ────────────────────────────────── */
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            flex-wrap: wrap;
        }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 700;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            background: transparent;
            cursor: pointer;
            font-family: inherit;
            transition: all var(--transition);
            text-decoration: none;
        }
        .btn-outline:hover { border-color: var(--text-secondary); color: var(--text); }

        @media (max-width: 720px) {
            .create-layout { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="cinema-container">

    <!-- Breadcrumb -->
    <div style="margin-bottom:28px;">
        <a href="dashboard.php" style="color:var(--text-secondary);font-size:13px;">Panel</a>
        <span style="color:var(--text-muted);margin:0 8px;">›</span>
        <a href="products.php" style="color:var(--text-secondary);font-size:13px;">Productos</a>
        <span style="color:var(--text-muted);margin:0 8px;">›</span>
        <span style="color:var(--text);font-size:13px;">Nuevo</span>
    </div>

    <!-- Header -->
    <div class="admin-header">
        <h1>🍿 Nuevo Producto</h1>
        <span class="badge">Admin</span>
    </div>

    <?php if ($error): ?>
        <div class="alert error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" id="productForm">

        <div class="create-layout">

            <!-- ── Columna principal ── -->
            <div class="form-card">
                <h2>Información del producto</h2>

                <div class="form-grid">

                    <!-- Nombre -->
                    <div class="form-group full">
                        <label>Nombre <span>*</span></label>
                        <input type="text" name="nombre"
                               placeholder="Ej: Combo Pareja Clásico"
                               value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                               required>
                    </div>

                    <!-- Precio -->
                    <div class="form-group">
                        <label>Precio <span>*</span></label>
                        <div class="input-prefix">
                            <span>$</span>
                            <input type="number" name="precio"
                                   min="0" step="100"
                                   placeholder="25000"
                                   value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>"
                                   required>
                        </div>
                    </div>

                    <!-- Categoría (select oculto + chips visuales) -->
                    <div class="form-group">
                        <label>Categoría <span>*</span></label>
                        <select name="categoria" id="categoria" required style="display:none">
                            <option value="Combo">Combo</option>
                            <option value="Bebida">Bebida</option>
                            <option value="Snack">Snack</option>
                            <option value="Dulce">Dulce</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <div class="chip-row">
                            <span class="chip active" data-val="Combo">🍿 Combo</span>
                            <span class="chip" data-val="Bebida">🥤 Bebida</span>
                            <span class="chip" data-val="Snack">🧆 Snack</span>
                            <span class="chip" data-val="Dulce">🍬 Dulce</span>
                            <span class="chip" data-val="Otro">📦 Otro</span>
                        </div>
                    </div>

                    <!-- Descripción -->
                    <div class="form-group full">
                        <label>Descripción <span>*</span></label>
                        <textarea name="descripcion"
                                  placeholder="Detalla los items que incluye el combo o las características del producto..."
                                  required><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
                    </div>

                </div><!-- /form-grid -->

                <div class="form-actions">
                    <button type="submit" class="btn-neon">
                        ✅ Guardar Producto
                    </button>
                    <a href="products.php" class="btn-outline">✕ Cancelar</a>
                </div>

            </div><!-- /form-card -->

            <!-- ── Sidebar imagen ── -->
            <div class="sidebar-card">
                <h2>Imagen del producto</h2>

                <div class="img-preview-box" id="preview-box">
                    <span class="placeholder">🖼️</span>
                    <img id="preview-img" src="" alt="Preview">
                </div>

                <label class="file-label" for="imagen">
                    📤 Seleccionar imagen
                </label>
                <input type="file" id="imagen" name="imagen"
                       accept="image/*" required>

                <p style="margin-top:12px;font-size:11px;color:var(--text-muted);text-align:center;">
                    JPG, PNG o WEBP<br>Recomendado: 600×600 px
                </p>
            </div>

        </div><!-- /create-layout -->

    </form>

</div>

<script>
    // ── Chips de categoría ───────────────────────
    const chips    = document.querySelectorAll('.chip');
    const catSelect = document.getElementById('categoria');

    chips.forEach(chip => {
        chip.addEventListener('click', () => {
            chips.forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            catSelect.value = chip.dataset.val;
        });
    });

    // Sincronizar valor previo (si hay error de POST)
    const prev = "<?= htmlspecialchars($_POST['categoria'] ?? 'Combo') ?>";
    chips.forEach(c => {
        c.classList.toggle('active', c.dataset.val === prev);
    });

    // ── Preview imagen ───────────────────────────
    const inputImg   = document.getElementById('imagen');
    const previewImg = document.getElementById('preview-img');
    const placeholder = document.querySelector('.img-preview-box .placeholder');

    inputImg.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src          = e.target.result;
            previewImg.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });
</script>

</body>
</html>
