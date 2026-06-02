<?php
session_start();
require 'config/conection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);

    $password = $_POST['password'];

    if(strlen($password) < 8){

        $error = "La contraseña debe tener mínimo 8 caracteres";

    } else {

        $stmt = $conn->prepare(
            "SELECT id_cliente
             FROM cliente
             WHERE correo = ?"
        );

        $stmt->bind_param("s", $correo);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if($resultado->num_rows > 0){

            $error = "El correo ya está registrado";

        } else {

            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $fecha = date('Y-m-d');

            $stmt = $conn->prepare(
                "INSERT INTO cliente
                (
                    nombre,
                    correo,
                    contrasena,
                    telefono,
                    fecha_registro
                )
                VALUES
                (
                    ?, ?, ?, ?, ?
                )"
            );

            $stmt->bind_param(
                "sssss",
                $nombre,
                $correo,
                $hash,
                $telefono,
                $fecha
            );

            if($stmt->execute()){

                $_SESSION['usuario_id'] =
                $conn->insert_id;

                $_SESSION['usuario_nombre'] =
                $nombre;

                header("Location: index.php");
                exit();

            } else {

                $error =
                "Error al registrar usuario";
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
    <title>Registro - Cinemas Star</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
            padding: 40px 20px;
        }
        .auth-card {
            background-color: #1a1a1a;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(221, 184, 254, 0.15);
            text-align: center;
        }
        .auth-card h2 {
            color: #ddb8f5;
            font-size: 32px;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-control {
            width: 100%;
            padding: 14px;
            background: #262626;
            border: 1px solid transparent;
            border-radius: 8px;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: #ddb8f5;
            box-shadow: 0 0 15px rgba(221, 184, 254, 0.2);
            background: #1a1a1a;
        }
        .btn-auth {
            width: 100%;
            background-color: #ddb8f5;
            color: #000000;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .btn-auth:hover {
            background-color: #cb00e6;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(221, 184, 254, 0.3);
        }
        .auth-footer {
            margin-top: 25px;
            color: #888888;
            font-size: 14px;
        }
        .auth-footer a {
            color: #ddb8f5;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .auth-footer a:hover {
            color: #ffffff;
        }
        .error-msg {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 71, 87, 0.3);
        }
    </style>
</head>
<body>
    <header class="menu">
        <a href="index.php"><img class="logo" src="Images/logo.svg" alt="Cinemas Star"></a>
        <nav class="nav-menu">
            <ul class="principal">
                <li><a href="index.php" class="nav-link">Inicio</a></li>
                <li><a href="snacks.html" class="nav-link">Confiteria</a></li>
                <li><a href="premier.html" class="nav-link">Estrenos</a></li>
                <li><a href="#" class="nav-link">Contacto</a></li>
            </ul>    
        </nav>
        <div class="but-cart">
            <button class="cartelera" onclick="window.location.href='login.php'">Iniciar Sesión</button>
        </div>
    </header>

    <div class="auth-wrapper">
        <div class="auth-card">
            <h2>Crear Cuenta</h2>
            
            <?php if($error != "") echo "<div class='error-msg'>$error</div>"; ?>
            
            <form method="POST">

                <div class="form-group">
                    <input
                    type="text"
                    name="nombre"
                    class="form-control"
                    placeholder="Nombre Completo"
                    required>
                </div>

                <div class="form-group">
                    <input
                    type="email"
                    name="correo"
                    class="form-control"
                    placeholder="Correo"
                    required>
                </div>

                <div class="form-group">
                    <input
                    type="text"
                    name="telefono"
                    class="form-control"
                    placeholder="Teléfono"
                    required>
                </div>

                <div class="form-group">
                    <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Contraseña"
                    required>
                </div>

                <button
                type="submit"
                class="btn-auth">
                    Registrarse
                </button>

            </form>
            
            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Ingresa aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>