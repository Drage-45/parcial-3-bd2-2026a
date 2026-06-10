<?php
session_start();
require '../config/conection.php';

// Si ya está logueado como admin, redirigir al dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo   = trim($_POST['correo']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_admin, nombre, contrasena FROM administrador WHERE correo = ? AND estado = 'activo'");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $admin = $res->fetch_assoc();
        if (password_verify($password, $admin['contrasena'])) {
            $_SESSION['admin_id']     = $admin['id_admin'];
            $_SESSION['admin_nombre'] = $admin['nombre'];
            $_SESSION['admin_correo'] = $correo;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Correo no encontrado o cuenta inactiva.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Cinemas Star</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:      #e50914;
            --primary-hover:#ff2d3a;
            --primary-glow: rgba(229,9,20,.35);
            --bg:           #0d0d0d;
            --bg-card:      #161616;
            --bg-elevated:  #1e1e1e;
            --border:       rgba(255,255,255,.07);
            --text:         #ffffff;
            --text-secondary:#a3a3a3;
            --text-muted:   #555;
            --radius:       14px;
            --transition:   .3s cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* ── Fondo animado ── */
        .bg-grid {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(229,9,20,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(229,9,20,.03) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }
        .bg-glow {
            position: fixed;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(229,9,20,.08) 0%, transparent 70%);
            top: -100px; left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }

        /* ── Login card ── */
        .login-wrap {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        /* Logo arriba */
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .login-logo .logo-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 4px;
            color: var(--text);
        }
        .login-logo .logo-text span { color: var(--primary); }
        .login-logo .admin-badge {
            display: inline-block;
            background: rgba(229,9,20,.12);
            border: 1px solid rgba(229,9,20,.3);
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            margin-top: 6px;
        }

        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 40px 36px;
            box-shadow: 0 32px 80px rgba(0,0,0,.7);
            position: relative;
            overflow: hidden;
        }
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), #ff6b35);
        }

        .card h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.8rem;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }
        .card p {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 28px;
        }

        /* Campos */
        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }
        .field-inner {
            position: relative;
        }
        .field-inner .icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            pointer-events: none;
            opacity: .5;
        }
        .field input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
        }
        .field input::placeholder { color: var(--text-muted); }
        .field input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        /* Toggle contraseña */
        .toggle-pwd {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            opacity: .4;
            transition: opacity var(--transition);
        }
        .toggle-pwd:hover { opacity: .8; }

        /* Error */
        .error-box {
            background: rgba(229,9,20,.1);
            border: 1px solid rgba(229,9,20,.3);
            color: #ff6b6b;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Botón */
        .btn-login {
            width: 100%;
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all var(--transition);
            box-shadow: 0 4px 20px var(--primary-glow);
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(255,45,58,.5);
        }
        .btn-login:active { transform: translateY(0); }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: var(--text-muted);
        }
        .login-footer a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color var(--transition);
        }
        .login-footer a:hover { color: var(--text); }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="bg-glow"></div>

    <div class="login-wrap">

        <div class="login-logo">
            <div class="logo-text">CINEMAS <span>STAR</span></div>
            <div class="admin-badge">Panel de Administración</div>
        </div>

        <div class="card">
            <h2>Acceso Admin</h2>
            <p>Ingresa tus credenciales para continuar</p>

            <?php if ($error): ?>
                <div class="error-box">⚠ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="field">
                    <label>Correo electrónico</label>
                    <div class="field-inner">
                        <span class="icon">✉</span>
                        <input type="email" name="correo" placeholder="admin@cinemasstar.com"
                               value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="field">
                    <label>Contraseña</label>
                    <div class="field-inner">
                        <span class="icon">🔒</span>
                        <input type="password" id="pwd" name="password" placeholder="••••••••" required>
                        <button type="button" class="toggle-pwd" onclick="togglePwd()">👁</button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    🔐 Ingresar al panel
                </button>

            </form>
        </div>

        <div class="login-footer">
            <a href="../index.php">← Volver al sitio público</a>
        </div>

    </div>

    <script>
        function togglePwd() {
            const input = document.getElementById('pwd');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>