<?php

session_start();

require 'config/conection.php';


if(!isset($_SESSION['usuario_id'])){
    die("Debe iniciar sesión");
}


if(
!isset($_POST['id_funcion']) &&
!isset($_POST['asientos'])
){
    die("No hay productos para comprar");
}

$id_cliente = $_SESSION['usuario_id'];

$id_funcion = intval($_POST['id_funcion']);

$asientos = explode(",", $_POST['asientos']);



// Buscar precio de la función
$sqlPrecio = "SELECT precio FROM funcion WHERE id_funcion=$id_funcion";
$resultado  = $conn->query($sqlPrecio);
$funcion    = $resultado->fetch_assoc();
$precio     = $funcion['precio'];



// Buscar venta PENDIENTE del cliente (carrito activo) o crear una nueva
$sqlVenta = "
SELECT id_venta
FROM venta
WHERE id_cliente = $id_cliente
  AND estado = 'PENDIENTE'
LIMIT 1
";

$resVenta = $conn->query($sqlVenta);

if($resVenta->num_rows > 0){
    $venta    = $resVenta->fetch_assoc();
    $id_venta = $venta['id_venta'];
}else{
    $conn->query("
    INSERT INTO venta (id_cliente, total, estado)
    VALUES ($id_cliente, 0, 'PENDIENTE')
    ");
    $id_venta = $conn->insert_id;
}


if(count($asientos) > 0){

    foreach($asientos as $id_funcion_butaca){

        $id_funcion_butaca = intval($id_funcion_butaca);

        // Validar disponibilidad
        $check  = "SELECT estado FROM funcion_butaca WHERE id_funcion_butaca=$id_funcion_butaca";
        $estado = $conn->query($check)->fetch_assoc();

        if($estado['estado'] != 'Disponible'){
            die("Un asiento ya fue ocupado");
        }

        // Obtener datos del asiento (fila + número) para descripción
        $sqlButaca  = "SELECT b.fila, b.numero FROM butaca b INNER JOIN funcion_butaca fb ON b.id_butaca = fb.id_butaca WHERE fb.id_funcion_butaca = $id_funcion_butaca";
        $resButaca  = $conn->query($sqlButaca)->fetch_assoc();
        $descripcion = "Asiento " . $resButaca['fila'] . $resButaca['numero'];

        // Crear boleto
        $sqlBoleto = "
        INSERT INTO boleto (fecha_venta, precio, id_cliente, id_funcion_butaca, id_venta)
        VALUES (NOW(), $precio, $id_cliente, $id_funcion_butaca, $id_venta)
        ";
        if(!$conn->query($sqlBoleto)){
            die("Error al crear boleto: " . $conn->error);
        }

        // Guardar detalle en la venta
        $conn->query("
        INSERT INTO venta_detalle (id_venta, tipo, descripcion, cantidad, precio)
        VALUES ($id_venta, 'BOLETO', '$descripcion', 1, $precio)
        ");

        // Bloquear asiento
        $conn->query("
        UPDATE funcion_butaca
        SET estado = 'Ocupado'
        WHERE id_funcion_butaca = $id_funcion_butaca
        ");
    
    }
}


// Actualizar total de la venta sumando todos los detalles
$conn->query("
UPDATE venta v
SET v.total = (
    SELECT COALESCE(SUM(vd.cantidad * vd.precio), 0)
    FROM venta_detalle vd
    WHERE vd.id_venta = v.id_venta
)
WHERE v.id_venta = $id_venta
");



// Redirigir al carrito para que el cliente vea sus boletos y pueda agregar combos
header("Location: cart.php");
exit();

?>
