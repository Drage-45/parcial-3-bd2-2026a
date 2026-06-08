<?php
session_start();
require 'config/conection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo   = trim($_POST['correo']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_cliente, nombre, contrasena FROM cliente WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($password, $usuario['contrasena'])) {
            $_SESSION['usuario_id']     = $usuario['id_cliente'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['logueado']       = true;
            header("Location: index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Correo no registrado";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="menu scrolled" id="navbar">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php"   class="nav-link">Inicio</a></li>
                <li><a href="snacks.php"  class="nav-link">Confitería</a></li>
                <li><a href="premier.html" class="nav-link">Estrenos</a></li>
            </ul>
        </nav>
        <div class="but-cart">
            <button class="cartelera" onclick="window.location.href='register.php'">Registrarse</button>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Bienvenido</h2>
            <p class="auth-subtitle">Inicia sesión para continuar</p>

            <?php if ($error): ?>
                <div class="error-msg">⚠ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-control"
                           placeholder="tucorreo@ejemplo.com" required
                           value="<?= isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '' ?>">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn-auth">Ingresar</button>
            </form>

            <div class="auth-footer">
                <p>¿Aún no eres miembro? <a href="register.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>

</body>
</html>