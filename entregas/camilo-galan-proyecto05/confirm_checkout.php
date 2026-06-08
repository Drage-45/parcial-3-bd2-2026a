<?php

session_start();

require 'config/conection.php';

$id_venta = intval($_POST['id_venta']);

$conn->query("
UPDATE venta
SET estado='PAGADA'
WHERE id_venta=$id_venta
");

header("Location: voucher.php?venta=$id_venta");
exit();

?>