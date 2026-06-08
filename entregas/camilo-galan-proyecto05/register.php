<?php
session_start();
require 'config/conection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = trim($_POST['nombre']);
    $correo   = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $password = $_POST['password'];

    if (strlen($password) < 8) {
        $error = "La contraseña debe tener mínimo 8 caracteres";
    } elseif (strlen($telefono) < 7) {
        $error = "Teléfono inválido";
    } else {
        $stmt = $conn->prepare("SELECT id_cliente FROM cliente WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $error = "El correo ya está registrado";
        } else {
            $hash  = password_hash($password, PASSWORD_DEFAULT);
            $fecha = date('Y-m-d');

            $stmt = $conn->prepare(
                "INSERT INTO cliente (nombre, correo, contrasena, telefono, fecha_registro) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssss", $nombre, $correo, $hash, $telefono, $fecha);

            if ($stmt->execute()) {
                $_SESSION['usuario_id']     = $conn->insert_id;
                $_SESSION['usuario_nombre'] = $nombre;
                $_SESSION['usuario_correo'] = $correo;
                $_SESSION['logueado']       = true;
                header("Location: index.php");
                exit();
            } else {
                $error = "Error al registrar usuario";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro — Cinemas Star</title>
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
            <button class="cartelera" onclick="window.location.href='login.php'">Iniciar Sesión</button>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Crear Cuenta</h2>
            <p class="auth-subtitle">Únete a Cinemas Star</p>

            <?php if ($error): ?>
                <div class="error-msg">⚠ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           placeholder="Tu nombre" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" class="form-control"
                           placeholder="tucorreo@ejemplo.com" required>
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" class="form-control"
                           placeholder="3001234567" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control"
                           placeholder="Mínimo 8 caracteres" required>
                </div>
                <button type="submit" class="btn-auth">Registrarse</button>
            </form>

            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Ingresa aquí</a></p>
            </div>
        </div>
    </div>

</body>
</html>