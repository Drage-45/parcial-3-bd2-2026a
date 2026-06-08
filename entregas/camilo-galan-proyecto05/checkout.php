<?php
session_start();
require 'config/conection.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];

$sqlVenta = "SELECT * FROM venta WHERE id_cliente = $id_cliente AND estado='PENDIENTE' LIMIT 1";
$resVenta = $conn->query($sqlVenta);

if ($resVenta->num_rows == 0) {
    die("No hay compras pendientes");
}

$venta    = $resVenta->fetch_assoc();
$id_venta = $venta['id_venta'];

$sqlProductos = "
SELECT vd.descripcion, vd.cantidad, vd.precio, (vd.cantidad * vd.precio) subtotal
FROM venta_detalle vd
WHERE vd.id_venta = $id_venta AND vd.tipo='COMBO'
";
$productos  = $conn->query($sqlProductos);
$totalComida = 0;
$items       = [];

while ($p = $productos->fetch_assoc()) {
    $totalComida += $p['subtotal'];
    $items[]      = $p;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Compra — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php" class="nav-link">Inicio</a></li>
            </ul>
        </nav>
        <div class="but-cart">
            <button class="cartelera" onclick="window.location.href='cart.php'">← Volver al carrito</button>
        </div>
    </header>

    <div class="cinema-container" style="max-width:680px;">

        <div class="page-header">
            <h1>Confirmar Compra</h1>
            <p>Revisa los detalles antes de pagar</p>
        </div>

        <!-- Confitería -->
        <?php if (count($items) > 0): ?>
        <div class="checkout-section">
            <h2>🍿 Confitería</h2>
            <?php foreach ($items as $p): ?>
            <div class="checkout-row">
                <span><?= htmlspecialchars($p['descripcion']) ?> ×<?= $p['cantidad'] ?></span>
                <span>$<?= number_format($p['subtotal'], 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
            <div class="checkout-row" style="margin-top:8px;">
                <span style="font-weight:700;color:var(--text);">Subtotal confitería</span>
                <span class="checkout-total">$<?= number_format($totalComida, 0, ',', '.') ?></span>
            </div>
        </div>
        <?php endif; ?>

        <!-- Boletos (sección informativa) -->
        <div class="checkout-section">
            <h2>🎬 Boletos</h2>
            <p style="color:var(--text-secondary);font-size:14px;">
                Los boletos seleccionados se incluirán automáticamente al confirmar el pago.
            </p>
        </div>

        <!-- Total y acción -->
        <div class="checkout-section">
            <div class="summary-total">
                <span style="font-size:15px;font-weight:700;">Total a pagar</span>
                <strong>$<?= number_format($totalComida, 0, ',', '.') ?></strong>
            </div>

            <form action="process_checkout.php" method="POST" style="margin-top:20px;">
                <input type="hidden" name="comida"      value="<?= $totalComida ?>">
                <input type="hidden" name="confirmado"  value="1">
                <button type="submit" class="btn-neon" style="width:100%;justify-content:center;">
                    Confirmar y pagar
                </button>
            </form>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>