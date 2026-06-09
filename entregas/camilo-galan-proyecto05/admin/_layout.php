<?php
/**
 * _layout.php  — include parcial reutilizable para todas las páginas admin
 *
 * Uso:
 *   $pageTitle = "Películas";         // título en el <title>
 *   $activeMenu = "movies";           // resalta el ítem activo del sidebar
 *   require '_layout.php';            // abre html, head, sidebar y content-wrap
 *
 * Al final de cada página:
 *   require '_layout_end.php';        // cierra el content-wrap y el body
 *
 * Protege la sesión:
 */
// Iniciar sesión de forma segura (evita error si ya fue iniciada por la página padre)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$adminNombre = $_SESSION['admin_nombre'] ?? 'Administrador';

// ── Resumen rápido para el sidebar ──────────────────────────────────────────
require_once '../config/conection.php';

$ventasHoy   = $conn->query("SELECT COUNT(*) AS n FROM venta WHERE estado='PAGADA' AND DATE(fecha)=CURDATE()")->fetch_assoc()['n'] ?? 0;
$ingresoHoy  = $conn->query("SELECT COALESCE(SUM(total),0) AS t FROM venta WHERE estado='PAGADA' AND DATE(fecha)=CURDATE()")->fetch_assoc()['t'] ?? 0;
$peliculas   = $conn->query("SELECT COUNT(*) AS n FROM pelicula")->fetch_assoc()['n'] ?? 0;
$productos   = $conn->query("SELECT COUNT(*) AS n FROM producto WHERE estado='Disponible'")->fetch_assoc()['n'] ?? 0;

$menuItems = [
    'dashboard' => ['icon'=>'🏠', 'label'=>'Dashboard',  'href'=>'dashboard.php'],
    'movies'    => ['icon'=>'🎬', 'label'=>'Películas',   'href'=>'movies.php'],
    'add_movie' => ['icon'=>'➕', 'label'=>'Nueva Película','href'=>'add_movie.php', 'sub'=>true],
    'products'  => ['icon'=>'🍿', 'label'=>'Productos',   'href'=>'products.php'],
    'product_create'=>['icon'=>'➕','label'=>'Nuevo Producto','href'=>'product_create.php','sub'=>true],
    'sales'     => ['icon'=>'📊', 'label'=>'Ventas del día','href'=>'admin_sales.php'],
    'reports'   => ['icon'=>'📈', 'label'=>'Reportes',    'href'=>'../reports.php'],
    'schedule'  => ['icon'=>'🗓', 'label'=>'Funciones',   'href'=>'../schedule.php'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Panel Admin') ?> — Cinemas Star Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:        #e50914;
            --primary-hover:  #ff2d3a;
            --primary-glow:   rgba(229,9,20,.3);
            --bg:             #0d0d0d;
            --bg-card:        #161616;
            --bg-elevated:    #1e1e1e;
            --sidebar-w:      248px;
            --topbar-h:       60px;
            --border:         rgba(255,255,255,.07);
            --border-accent:  rgba(229,9,20,.35);
            --text:           #ffffff;
            --text-secondary: #a3a3a3;
            --text-muted:     #555;
            --radius-sm:      10px;
            --radius-md:      12px;
            --radius-lg:      16px;
            --transition:     .28s cubic-bezier(.4,0,.2,1);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            transition: transform var(--transition);
        }

        /* Logo */
        .sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-logo .logo-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.35rem;
            letter-spacing: 3px;
            color: var(--text);
            line-height: 1;
        }
        .sidebar-logo .logo-text span { color: var(--primary); }
        .sidebar-logo .badge {
            background: rgba(229,9,20,.15);
            border: 1px solid rgba(229,9,20,.3);
            color: var(--primary);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            padding: 2px 7px;
            border-radius: 20px;
        }

        /* Mini stats */
        .sidebar-stats {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .s-stat {
            background: var(--bg-elevated);
            border-radius: var(--radius-sm);
            padding: 10px 12px;
        }
        .s-stat .s-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
        }
        .s-stat .s-value {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.3rem;
            letter-spacing: 1px;
            color: var(--text);
        }
        .s-stat .s-value.red { color: var(--primary); }

        /* Nav */
        .sidebar-nav {
            flex: 1;
            padding: 12px 10px;
            overflow-y: auto;
        }
        .nav-section-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 10px 10px 6px;
            margin-top: 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition);
            margin-bottom: 2px;
        }
        .nav-item .nav-icon { font-size: 16px; width: 22px; text-align: center; }
        .nav-item.sub {
            padding-left: 44px;
            font-size: 12px;
        }
        .nav-item:hover {
            background: rgba(255,255,255,.05);
            color: var(--text);
        }
        .nav-item.active {
            background: rgba(229,9,20,.12);
            color: var(--primary);
            font-weight: 700;
        }
        .nav-item.active .nav-icon { filter: none; }

        /* Admin info + logout */
        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid var(--border);
        }
        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .admin-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 14px;
            letter-spacing: 1px;
            flex-shrink: 0;
        }
        .admin-info .admin-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }
        .admin-info .admin-role {
            font-size: 10px;
            color: var(--text-muted);
        }
        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 9px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
        }
        .btn-logout:hover {
            background: rgba(229,9,20,.08);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ════════════════════════════════════════
           MAIN CONTENT
        ════════════════════════════════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-h);
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: var(--text);
            padding: 4px;
        }
        .topbar-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.3rem;
            letter-spacing: 2px;
            color: var(--text);
        }
        .topbar-breadcrumb {
            font-size: 12px;
            color: var(--text-muted);
        }
        .topbar-breadcrumb span { color: var(--text-secondary); }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .topbar-date {
            font-size: 12px;
            color: var(--text-muted);
        }
        .topbar-site {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition);
        }
        .topbar-site:hover { border-color: var(--primary); color: var(--text); }

        /* Page area */
        .page-area {
            padding: 32px 28px 60px;
            flex: 1;
        }

        /* ════════════════════════════════════════
           COMPONENTES GLOBALES ADMIN
        ════════════════════════════════════════ */

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
        }
        table th {
            background: var(--bg-elevated);
            color: var(--text-secondary);
            padding: 13px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        table td {
            padding: 13px 16px;
            font-size: 13px;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }
        table tr:last-child td { border-bottom: none; }
        table tr:hover td {
            background: rgba(255,255,255,.025);
            color: var(--text);
        }

        /* Formularios */
        .form-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 32px;
        }
        .form-card-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-secondary);
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 22px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group.full { grid-column: 1/-1; }
        .form-group label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-secondary);
        }
        .form-group label .req { color: var(--primary); }
        .form-group input,
        .form-group select,
        .form-group textarea {
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            font-family: inherit;
            font-size: 14px;
            padding: 11px 14px;
            outline: none;
            width: 100%;
            transition: border-color var(--transition), box-shadow var(--transition);
        }
        .form-group select option { background: var(--bg-card); }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .form-group textarea { min-height: 90px; resize: vertical; }

        /* Botones */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 11px 22px; border-radius: var(--radius-sm); font-family: inherit; font-size: 13px; font-weight: 700; cursor: pointer; transition: all var(--transition); text-decoration: none; border: none; }
        .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 4px 16px var(--primary-glow); }
        .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(255,45,58,.5); }
        .btn-secondary { background: var(--bg-elevated); color: var(--text-secondary); border: 1px solid var(--border); }
        .btn-secondary:hover { border-color: var(--text-secondary); color: var(--text); }
        .btn-danger { background: rgba(229,9,20,.12); color: var(--primary); border: 1px solid rgba(229,9,20,.3); }
        .btn-danger:hover { background: var(--primary); color: #fff; }
        .btn-sm { padding: 7px 14px; font-size: 12px; }

        /* Badges */
        .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .badge-green { background: rgba(34,197,94,.15); color: #22c55e; border: 1px solid rgba(34,197,94,.25); }
        .badge-red   { background: rgba(229,9,20,.15);  color: var(--primary); border: 1px solid rgba(229,9,20,.25); }
        .badge-gray  { background: rgba(255,255,255,.07); color: var(--text-secondary); border: 1px solid var(--border); }

        /* Alerta */
        .alert { padding: 13px 16px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-error   { background: rgba(229,9,20,.1); border: 1px solid rgba(229,9,20,.3); color: #ff6b6b; }
        .alert-success { background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.3); color: #4ade80; }

        /* Overlay móvil */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.6);
            z-index: 199;
        }

        /* ════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════ */
        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-wrap { margin-left: 0; }
            .hamburger { display: block; }
            .page-area { padding: 24px 16px 60px; }
            .form-grid { grid-template-columns: 1fr; }
        }

        /* ── Page header ─────────────────────────────────────────── */
        .admin-page-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 28px; gap: 16px; flex-wrap: wrap;
        }
        .admin-page-title { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 4px; }
        .admin-page-sub   { font-size: 13px; color: var(--s-muted); }

        /* ── Alerts ──────────────────────────────────────────────── */
        .admin-alert {
            padding: 14px 18px; border-radius: 10px; font-size: 13px;
            margin-bottom: 20px; display: flex; flex-direction: column; gap: 4px;
        }
        .admin-alert.success { background: rgba(34,197,94,0.1);  border: 1px solid rgba(34,197,94,0.3);  color: #22c55e; }
        .admin-alert.error   { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.3);  color: #ef4444; }

        /* ── Buttons ─────────────────────────────────────────────── */
        .btn-admin-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--primary); color: #fff; border: none; cursor: pointer;
            padding: 11px 22px; border-radius: 8px; font-size: 13px; font-weight: 700;
            letter-spacing: 0.3px; text-decoration: none; transition: opacity 0.2s;
        }
        .btn-admin-primary:hover { opacity: 0.85; }
        .btn-admin-ghost {
            display: inline-flex; align-items: center; gap: 6px;
            background: transparent; color: var(--s-muted);
            border: 1px solid rgba(255,255,255,0.12); cursor: pointer;
            padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-admin-ghost:hover { color: var(--s-text); border-color: rgba(255,255,255,0.25); }
        .btn-admin-danger {
            display: inline-flex; align-items: center;
            background: rgba(239,68,68,0.12); color: #ef4444;
            border: 1px solid rgba(239,68,68,0.3); cursor: pointer;
            padding: 7px 14px; border-radius: 6px; font-size: 12px; font-weight: 700;
            text-decoration: none; transition: all 0.2s;
        }
        .btn-admin-danger:hover { background: rgba(239,68,68,0.22); }

        /* ── Form card ───────────────────────────────────────────── */
        .admin-form-card {
            background: var(--bg-card); border: 1px solid var(--s-border);
            border-radius: 14px; padding: 32px;
        }
        .form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 20px; }
        .form-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--s-muted); }
        .form-control {
            background: var(--bg); border: 1px solid var(--s-border); color: var(--s-text);
            padding: 11px 14px; border-radius: 8px; font-size: 14px; font-family: inherit;
            transition: border-color 0.2s; width: 100%;
        }
        .form-control:focus { outline: none; border-color: var(--primary); }
        .form-control option { background: var(--bg-card); }
        .form-hint { font-size: 12px; color: var(--s-muted); margin-top: 2px; }
        .form-actions { display: flex; gap: 12px; align-items: center; margin-top: 8px; }

        /* ── Table ───────────────────────────────────────────────── */
        .admin-table-wrap { background: var(--bg-card); border: 1px solid var(--s-border); border-radius: 14px; overflow: hidden; }
        .admin-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .admin-table thead tr { background: rgba(255,255,255,0.04); }
        .admin-table th { padding: 14px 16px; text-align: left; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; color: var(--s-muted); font-weight: 700; border-bottom: 1px solid var(--s-border); }
        .admin-table td { padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: middle; }
        .admin-table tbody tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover { background: rgba(255,255,255,0.02); }

        /* ── Select filter ───────────────────────────────────────── */
        .admin-select {
            background: var(--bg-card); border: 1px solid var(--s-border); color: var(--s-text);
            padding: 9px 14px; border-radius: 8px; font-size: 13px; font-family: inherit; min-width: 220px;
        }
        .admin-select:focus { outline: none; border-color: var(--primary); }

        @media(max-width:640px) {
            .form-row-2 { grid-template-columns: 1fr; }
            .admin-page-header { flex-direction: column; }
        }
    </style>
</head>
<body>

<!-- ── SIDEBAR ── -->
<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">
        <div>
            <div class="logo-text">CINEMAS <span>STAR</span></div>
        </div>
        <span class="badge">ADMIN</span>
    </div>

    <!-- Mini stats del día -->
    <div class="sidebar-stats">
        <div class="s-stat">
            <div class="s-label">Ventas hoy</div>
            <div class="s-value red"><?= $ventasHoy ?></div>
        </div>
        <div class="s-stat">
            <div class="s-label">Ingresos</div>
            <div class="s-value">$<?= number_format($ingresoHoy/1000, 0) ?>K</div>
        </div>
        <div class="s-stat">
            <div class="s-label">Películas</div>
            <div class="s-value"><?= $peliculas ?></div>
        </div>
        <div class="s-stat">
            <div class="s-label">Productos</div>
            <div class="s-value"><?= $productos ?></div>
        </div>
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">

        <div class="nav-section-label">Principal</div>
        <a href="dashboard.php"
           class="nav-item <?= ($activeMenu??'')==='dashboard'?'active':'' ?>">
            <span class="nav-icon">🏠</span> Dashboard
        </a>
        <a href="admin_sales.php"
           class="nav-item <?= ($activeMenu??'')==='sales'?'active':'' ?>">
            <span class="nav-icon">📊</span> Ventas del día
        </a>
        <a href="../reports.php"
           class="nav-item <?= ($activeMenu??'')==='reports'?'active':'' ?>">
            <span class="nav-icon">📈</span> Reportes
        </a>

        <div class="nav-section-label">Contenido</div>
        <a href="movies.php"
           class="nav-item <?= ($activeMenu??'')==='movies'?'active':'' ?>">
            <span class="nav-icon">🎬</span> Películas
        </a>
        <a href="add_movie.php"
           class="nav-item sub <?= ($activeMenu??'')==='add_movie'?'active':'' ?>">
            <span class="nav-icon">➕</span> Nueva película
        </a>
        <a href="products.php"
           class="nav-item <?= ($activeMenu??'')==='products'?'active':'' ?>">
            <span class="nav-icon">🍿</span> Productos
        </a>
        <a href="product_create.php"
           class="nav-item sub <?= ($activeMenu??'')==='product_create'?'active':'' ?>">
            <span class="nav-icon">➕</span> Nuevo producto
        </a>

        <div class="nav-section-label">Sitio</div>
        <a href="funciones.php" class="nav-item <?= ($activeMenu??'')=='funciones'?'active':'' ?>">
            <span class="nav-icon">🗓</span> Funciones
        </a>
        <a href="funcion_create.php" class="nav-item sub <?= ($activeMenu??'')=='funcion_create'?'active':'' ?>">
            <span class="nav-icon">➕</span> Nueva Función
        </a>
        <a href="../index.php" class="nav-item" target="_blank">
            <span class="nav-icon">🌐</span> Ver sitio
        </a>

    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar"><?= strtoupper(substr($adminNombre,0,2)) ?></div>
            <div>
                <div class="admin-name"><?= htmlspecialchars($adminNombre) ?></div>
                <div class="admin-role">Administrador</div>
            </div>
        </div>
        <a href="logout.php" class="btn-logout">🚪 Cerrar sesión</a>
    </div>

</aside>

<!-- Overlay móvil -->
<div class="sidebar-overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- ── MAIN WRAP ── -->
<div class="main-wrap">

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="openSidebar()">☰</button>
            <div>
                <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Panel') ?></div>
            </div>
        </div>
        <div class="topbar-right">
            <span class="topbar-date" id="live-date"></span>
            <a href="../index.php" class="topbar-site" target="_blank">🌐 Sitio público</a>
        </div>
    </header>

    <!-- Contenido de la página -->
    <main class="page-area">

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('overlay').classList.add('open');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.remove('open');
    }
    // Fecha/hora en topbar
    function updateDate() {
        const now  = new Date();
        const opts = { weekday:'short', day:'numeric', month:'short', hour:'2-digit', minute:'2-digit' };
        document.getElementById('live-date').textContent = now.toLocaleDateString('es-CO', opts);
    }
    updateDate();
    setInterval(updateDate, 30000);
</script>
