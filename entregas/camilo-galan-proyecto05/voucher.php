<?php
session_start();
require 'config/conection.php';

if (!isset($_GET['venta'])) die("Venta no encontrada");

$id_venta = intval($_GET['venta']);

$sql = "
SELECT b.id_boleto, b.fecha_venta, b.precio,
    c.nombre, c.correo,
    p.titulo, f.fecha, f.hora,
    bu.fila, bu.numero
FROM boleto b
INNER JOIN cliente c         ON b.id_cliente         = c.id_cliente
INNER JOIN funcion_butaca fb ON b.id_funcion_butaca  = fb.id_funcion_butaca
INNER JOIN funcion f         ON fb.id_funcion        = f.id_funcion
INNER JOIN pelicula p        ON f.id_pelicula        = p.id_pelicula
INNER JOIN butaca bu         ON fb.id_butaca         = bu.id_butaca
WHERE b.id_venta = $id_venta
";

$sqlTotal   = "SELECT total FROM venta WHERE id_venta = $id_venta";
$venta      = $conn->query($sqlTotal)->fetch_assoc();
$detalles   = $conn->query("SELECT * FROM venta_detalle WHERE id_venta = $id_venta AND tipo='COMBO'");
$boletos    = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
    </header>

    <div class="cinema-container" style="max-width:620px;">

        <div class="page-header" style="text-align:center;">
            <h1>🎬 Cinemas Star</h1>
            <p>Comprobante de compra</p>
        </div>

        <div class="voucher-wrapper">

            <?php if ($boletos->num_rows > 0): ?>
            <div class="voucher">
                <h2>🎟 Boletos</h2>
                <?php while ($b = $boletos->fetch_assoc()): ?>
                <div class="voucher-row">
                    <span>Película</span><span><?= htmlspecialchars($b['titulo']) ?></span>
                </div>
                <div class="voucher-row">
                    <span>Cliente</span><span><?= htmlspecialchars($b['nombre']) ?></span>
                </div>
                <div class="voucher-row">
                    <span>Correo</span><span><?= htmlspecialchars($b['correo']) ?></span>
                </div>
                <div class="voucher-row">
                    <span>Fecha</span><span><?= htmlspecialchars($b['fecha']) ?></span>
                </div>
                <div class="voucher-row">
                    <span>Hora</span><span><?= substr($b['hora'], 0, 5) ?></span>
                </div>
                <div class="voucher-row">
                    <span>Asiento</span><span><strong><?= $b['fila'] . $b['numero'] ?></strong></span>
                </div>
                <div class="voucher-row">
                    <span>Precio boleto</span>
                    <span>$<?= number_format($b['precio'], 0, ',', '.') ?></span>
                </div>
                <hr class="voucher-divider">
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <?php if ($detalles->num_rows > 0): ?>
            <div class="voucher">
                <h2>🍿 Confitería</h2>
                <?php while ($d = $detalles->fetch_assoc()): ?>
                <div class="voucher-row">
                    <span><?= htmlspecialchars($d['descripcion']) ?> ×<?= $d['cantidad'] ?></span>
                    <span>$<?= number_format($d['precio'], 0, ',', '.') ?></span>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <div class="voucher" style="text-align:center;">
                <p style="font-size:11px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:2px;margin-bottom:4px;">
                    Total pagado
                </p>
                <div class="voucher-total">$<?= number_format($venta['total'], 0, ',', '.') ?></div>
                <a href="index.php" class="btn-neon" style="margin-top:16px;justify-content:center;">
                    Volver al inicio
                </a>
            </div>

        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>