<?php
// session_start() — gestionado por _layout.php
// Procesar el formulario ANTES de cargar el layout (evita headers already sent)
if (session_status() === PHP_SESSION_NONE) session_start();
require '../config/conection.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pelicula = intval($_POST['id_pelicula']);
    $id_sala     = intval($_POST['id_sala']);
    $fecha       = $conn->real_escape_string($_POST['fecha']);
    $hora        = $conn->real_escape_string($_POST['hora']);
    $precio      = floatval($_POST['precio']);

    // Validaciones
    if (!$id_pelicula) $errores[] = "Selecciona una película.";
    if (!$id_sala)     $errores[] = "Selecciona una sala.";
    if (!$fecha)       $errores[] = "La fecha es obligatoria.";
    if (!$hora)        $errores[] = "La hora es obligatoria.";
    if ($precio <= 0)  $errores[] = "El precio debe ser mayor a 0.";

    // Verificar que no exista ya esa función en esa sala/fecha/hora
    if (empty($errores)) {
        $dup = $conn->query("
            SELECT id_funcion FROM funcion
            WHERE id_sala=$id_sala AND fecha='$fecha' AND hora='$hora'
        ");
        if ($dup->num_rows > 0) {
            $errores[] = "Ya existe una función en esa sala a esa hora y fecha.";
        }
    }

    if (empty($errores)) {
        // Insertar función
        $conn->query("
            INSERT INTO funcion (fecha, hora, precio, id_pelicula, id_sala)
            VALUES ('$fecha', '$hora', $precio, $id_pelicula, $id_sala)
        ");
        $id_funcion = $conn->insert_id;

        // Generar funcion_butaca para todos los asientos de la sala
        $butacas = $conn->query("SELECT id_butaca FROM butaca WHERE id_sala=$id_sala");
        while ($b = $butacas->fetch_assoc()) {
            $conn->query("
                INSERT INTO funcion_butaca (id_funcion, id_butaca, estado)
                VALUES ($id_funcion, {$b['id_butaca']}, 'Disponible')
            ");
        }

        header("Location: funciones.php?msg=ok");
        exit();
    }
}

$listaPeliculas = $conn->query("SELECT id_pelicula, titulo FROM pelicula ORDER BY titulo");
$salas     = $conn->query("SELECT id_sala, nombre, capacidad FROM sala ORDER BY nombre");

$pageTitle  = 'Nueva Función';
$activeMenu = 'funcion_create';
require '_layout.php';
?>

<div class="admin-page-header">
    <div>
        <h1 class="admin-page-title">Nueva Función</h1>
        <p class="admin-page-sub">Programa una función y los asientos se generan automáticamente</p>
    </div>
    <a href="funciones.php" class="btn-admin-ghost">← Volver</a>
</div>

<?php if (!empty($errores)): ?>
<div class="admin-alert error">
    <?php foreach ($errores as $e): ?>
    <div>⚠ <?= $e ?></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="admin-form-card">
    <form method="POST">

        <div class="form-row-2">
            <!-- Película -->
            <div class="form-group">
                <label class="form-label">Película <span class="req">*</span></label>
                <select name="id_pelicula" class="form-control" required>
                    <option value="">— Seleccionar —</option>
                    <?php while ($p = $listaPeliculas->fetch_assoc()): ?>
                    <option value="<?= $p['id_pelicula'] ?>"
                        <?= (isset($_POST['id_pelicula']) && $_POST['id_pelicula']==$p['id_pelicula']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['titulo']) ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Sala -->
            <div class="form-group">
                <label class="form-label">Sala <span class="req">*</span></label>
                <select name="id_sala" class="form-control" required id="salaSelect">
                    <option value="">— Seleccionar —</option>
                    <?php while ($s = $salas->fetch_assoc()): ?>
                    <option value="<?= $s['id_sala'] ?>"
                            data-capacidad="<?= $s['capacidad'] ?>"
                        <?= (isset($_POST['id_sala']) && $_POST['id_sala']==$s['id_sala']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['nombre']) ?> (<?= $s['capacidad'] ?> asientos)
                    </option>
                    <?php endwhile; ?>
                </select>
                <p class="form-hint" id="capacidadHint"></p>
            </div>
        </div>

        <div class="form-row-2">
            <!-- Fecha -->
            <div class="form-group">
                <label class="form-label">Fecha <span class="req">*</span></label>
                <input type="date" name="fecha" class="form-control" required
                       min="<?= date('Y-m-d') ?>"
                       value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>">
            </div>

            <!-- Hora -->
            <div class="form-group">
                <label class="form-label">Hora <span class="req">*</span></label>
                <input type="time" name="hora" class="form-control" required
                       value="<?= htmlspecialchars($_POST['hora'] ?? '') ?>">
            </div>
        </div>

        <!-- Precio -->
        <div class="form-group" style="max-width:280px;">
            <label class="form-label">Precio por boleto (COP) <span class="req">*</span></label>
            <input type="number" name="precio" class="form-control" required
                   min="1000" step="500" placeholder="Ej: 15000"
                   value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>">
        </div>

        <!-- Resumen -->
        <div class="funcion-preview" id="preview" style="display:none;">
            <h4>📋 Resumen</h4>
            <p id="previewTexto"></p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-admin-primary">Crear Función</button>
            <a href="funciones.php" class="btn-admin-ghost">Cancelar</a>
        </div>

    </form>
</div>

<style>
.funcion-preview {
    background: rgba(229,9,20,0.08);
    border: 1px solid rgba(229,9,20,0.25);
    border-radius: 10px;
    padding: 16px 20px;
    margin: 8px 0 20px;
    font-size: 13px;
    color: var(--s-text);
}
.funcion-preview h4 { margin-bottom: 6px; font-size: 13px; color: var(--s-accent); }
.req { color: var(--s-accent); }
</style>

<script>
const salaSelect  = document.getElementById('salaSelect');
const hint        = document.getElementById('capacidadHint');
const preview     = document.getElementById('preview');
const previewText = document.getElementById('previewTexto');

function actualizarPreview() {
    const sala     = salaSelect.options[salaSelect.selectedIndex];
    const cap      = sala?.dataset.capacidad;
    const fecha    = document.querySelector('[name=fecha]').value;
    const hora     = document.querySelector('[name=hora]').value;
    const precio   = document.querySelector('[name=precio]').value;
    const peli     = document.querySelector('[name=id_pelicula]');
    const peliNom  = peli?.options[peli.selectedIndex]?.text;

    if (cap) hint.textContent = `Se generarán ${cap} asientos automáticamente.`;

    if (sala.value && fecha && hora && precio && peli.value) {
        preview.style.display = 'block';
        previewText.textContent =
            `"${peliNom}" en ${sala.text} — ${fecha} a las ${hora} — $${Number(precio).toLocaleString('es-CO')} por boleto`;
    } else {
        preview.style.display = 'none';
    }
}

document.querySelectorAll('select, input').forEach(el => el.addEventListener('change', actualizarPreview));
document.querySelectorAll('input').forEach(el => el.addEventListener('input', actualizarPreview));
</script>

<?php require '_layout_end.php'; ?>