<?php
session_start();
require 'config/conection.php';

if (!isset($_GET['id_funcion'])) die("Función no encontrada");

$id_funcion = intval($_GET['id_funcion']);

$sqlButacas = "
SELECT
    b.id_butaca, b.fila, b.numero,
    fb.estado, fb.id_funcion_butaca,
    f.precio
FROM butaca b
INNER JOIN funcion_butaca fb ON b.id_butaca    = fb.id_butaca
INNER JOIN funcion f         ON fb.id_funcion  = f.id_funcion
WHERE fb.id_funcion = $id_funcion
ORDER BY b.fila, b.numero
";

$butacas = $conn->query($sqlButacas);
if (!$butacas) die("Error SQL: " . $conn->error);

$precio = 0;
if ($butacas->num_rows > 0) {
    $primera = $butacas->fetch_assoc();
    $precio  = $primera['precio'];
    $butacas->data_seek(0);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Asientos — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="javascript:history.back()" class="nav-link">← Volver a horarios</a></li>
            </ul>
        </nav>
    </header>

    <section id="sala-cine" class="sala-cine">

        <h2 id="titullo-asientos">Selecciona tus Asientos</h2>

        <div class="pantalla-curva"></div>

        <div class="contenedor-asientos">
            <?php
            $filaActual = "";
            while ($b = $butacas->fetch_assoc()) {
                if ($filaActual !== $b['fila']) {
                    if ($filaActual !== "") echo "</div>";
                    $filaActual = $b['fila'];
                    echo '<div class="fila"><span class="fila-letra">' . $b['fila'] . '</span>';
                }
                $clase = ($b['estado'] === 'Ocupado') ? 'ocupado' : '';
                echo '<div class="asiento ' . $clase . '"
                    data-id="'     . $b['id_funcion_butaca'] . '"
                    data-numero="' . $b['fila'] . $b['numero'] . '">'
                    . $b['numero'] .
                '</div>';
            }
            echo "</div>";
            ?>
        </div>

        <!-- Leyenda -->
        <ul class="leyenda">
            <li><div class="asiento"></div> Disponible</li>
            <li><div class="asiento seleccionado"></div> Seleccionado</li>
            <li><div class="asiento ocupado"></div> Ocupado</li>
        </ul>

        <!-- Resumen -->
        <p class="resumen-texto">
            Asientos seleccionados: <span id="lista-asientos">—</span>
        </p>
        <p class="resumen-total">
            Total: $<span id="precio-total"><?= number_format($precio, 0, ',', '.') ?></span>
        </p>

        <form action="process_purchase.php" method="POST">
            <input type="hidden" name="id_funcion" value="<?= $id_funcion ?>">
            <input type="hidden" name="asientos"   id="asientos">
            <button type="submit" class="btn-neon" id="btn-comprar-final" disabled>
                Confirmar Compra
            </button>
        </form>

    </section>

    <script src="script.js"></script>
</body>
</html>