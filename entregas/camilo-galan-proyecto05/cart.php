<?php
session_start();
require 'config/conection.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_cliente = $_SESSION['usuario_id'];

// Traer TODOS los ítems del carrito (COMBO y BOLETO)
$sql = "
SELECT
    vd.id_detalle, vd.descripcion, vd.cantidad, vd.precio, vd.tipo,
    (vd.cantidad * vd.precio) AS subtotal,
    COALESCE(p.imagen, '') AS imagen,
    COALESCE(p.nombre, vd.descripcion) AS nombre
FROM venta_detalle vd
LEFT JOIN producto p    ON vd.id_producto = p.id_producto
INNER JOIN venta v      ON vd.id_venta    = v.id_venta
WHERE v.id_cliente = $id_cliente
  AND v.estado     = 'PENDIENTE'
ORDER BY vd.tipo DESC, vd.id_detalle DESC
";

$productos = $conn->query($sql);
$total     = 0;
$items     = [];

while ($p = $productos->fetch_assoc()) {
    $total  += $p['subtotal'];
    $items[] = $p;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php"    class="nav-link">Inicio</a></li>
                <li><a href="snacks.php"   class="nav-link">Confitería</a></li>
                <li><a href="premier.html" class="nav-link">Estrenos</a></li>
            </ul>
        </nav>
        <div class="but-cart">
            <span>Hola, <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></span>
            <button class="cartelera" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
        </div>
    </header>

    <div class="cinema-container">

        <div class="page-header">
            <h1>Mi Carrito</h1>
            <p>Revisa y confirma tus productos antes de pagar</p>
        </div>

        <?php if (count($items) > 0): ?>

        <div class="cart-layout">

            <!-- Ítems -->
            <div class="cart-items">
                <?php foreach ($items as $p): ?>
                <div class="bloque-card">
                    <?php if (!empty($p['imagen'])): ?>
                    <img src="<?= htmlspecialchars($p['imagen']) ?>" class="bloque-img" alt="<?= htmlspecialchars($p['nombre']) ?>">
                    <?php else: ?>
                    <div class="bloque-img" style="display:flex;align-items:center;justify-content:center;font-size:40px;">
                        <?= $p['tipo'] === 'BOLETO' ? '🎬' : '🍿' ?>
                    </div>
                    <?php endif; ?>
                    <div class="bloque-info">
                        <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                        <?php if ($p['tipo'] === 'BOLETO'): ?>
                            <span style="font-size:11px;background:rgba(255,180,0,0.15);color:#ffb400;padding:2px 8px;border-radius:20px;font-weight:700;">BOLETO</span>
                        <?php else: ?>
                            <span style="font-size:11px;background:rgba(0,200,100,0.15);color:#00c864;padding:2px 8px;border-radius:20px;font-weight:700;">COMBO</span>
                        <?php endif; ?>
                        <div class="bloque-footer">
                            <div>
                                <div class="cart-item-qty">× <?= $p['cantidad'] ?></div>
                                <span class="bloque-precio">
                                    $<?= number_format($p['subtotal'], 0, ',', '.') ?>
                                </span>
                            </div>
                            <form action="delete_cart.php" method="POST">
                                <input type="hidden" name="id_detalle" value="<?= $p['id_detalle'] ?>">
                                <button type="submit" class="btn-comprar" style="background:transparent;border:1px solid rgba(255,255,255,0.15);color:var(--text-secondary);font-size:11px;">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Resumen lateral -->
            <div class="cart-summary">
                <h3>Resumen de compra</h3>

                <?php foreach ($items as $p): ?>
                <div class="summary-row">
                    <span><?= htmlspecialchars($p['nombre']) ?> ×<?= $p['cantidad'] ?></span>
                    <span>$<?= number_format($p['subtotal'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>

                <div class="summary-total">
                    <span>Total</span>
                    <strong>$<?= number_format($total, 0, ',', '.') ?></strong>
                </div>

                <a href="checkout.php" class="btn-neon" style="width:100%;margin-top:20px;display:flex;justify-content:center;">
                    Continuar compra
                </a>
                <a href="snacks.php" style="display:block;text-align:center;margin-top:12px;font-size:13px;color:var(--text-secondary);">
                    ← Agregar combos
                </a>
            </div>

        </div>

        <?php else: ?>

        <div class="cart-empty">
            <p style="font-size:48px;margin-bottom:16px;">🛒</p>
            <p>Tu carrito está vacío</p>
            <a href="index.php" class="btn-neon">Ver cartelera</a>
        </div>

        <?php endif; ?>

    </div>

    <script src="script.js"></script>
</body>
</html>
