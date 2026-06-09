<?php

$host     = "localhost";
$user     = "root";
$password = "";
$database = "cinema_db";

// Sincronizar zona horaria de PHP con Colombia (UTC-5)
date_default_timezone_set('America/Bogota');

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Sincronizar zona horaria de MySQL con la misma
$conn->query("SET time_zone = '-05:00'");
?>