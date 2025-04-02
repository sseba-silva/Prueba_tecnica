<?php
include 'conexion.php';

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

switch ($accion) {
    case 'obtener_monedas':
        obtenerMonedas($conn);
        break;
    case 'obtener_bodegas':
        obtenerBodegas($conn);
        break;
    case 'obtener_sucursales':
        obtenerSucursales($conn);
        break;
    case 'insertar_producto':
        insertarProducto($conn);
        break;
    case 'obtener_codigo':
        compararCodigo($conn);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Acción no válida"]);
}

function obtenerMonedas($conn) {
    $sql = "SELECT id_moneda, nombre FROM monedas";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

function obtenerBodegas($conn) {
    $sql = "SELECT id_bodega, nombre FROM bodegas";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

function obtenerSucursales($conn) {
    $bodega_id = intval($_GET['id_bodega'] ?? 0);
    $stmt = $conn->prepare("SELECT id_sucursal, nombre FROM sucursales WHERE id_bodega = ?");
    $stmt->bind_param("i", $bodega_id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

function compararCodigo($conn) {
    $codigo = $_GET['codigo'] ?? ''; // Asegurar que obtienes el código

    $stmt = $conn->prepare("SELECT * FROM productos WHERE codigo = ?");
    $stmt->bind_param("s", $codigo); // "s" porque es un string
    $stmt->execute();
    $result = $stmt->get_result();
    
    $producto = $result->fetch_assoc(); // Obtener un solo resultado
    echo json_encode($producto ? $producto : []);
}

function insertarProducto($conn) {
    $codigo = trim($_POST['codigo'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $bodega = intval($_POST['bodega'] ?? 0);
    $sucursal = intval($_POST['sucursal'] ?? 0);
    $moneda = intval($_POST['moneda'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');

    if (!$codigo || !$nombre || !$bodega || !$sucursal || !$moneda || !$precio || !$descripcion) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO productos (codigo, nombre, id_bodega, id_sucursal, id_moneda, precio, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiids", $codigo, $nombre, $bodega, $sucursal, $moneda, $precio, $descripcion);
    $stmt->execute();

    echo json_encode(["status" => "success", "message" => "Producto guardado con éxito."]);
}
?>
