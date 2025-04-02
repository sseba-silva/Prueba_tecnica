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
    $sql = "SELECT id_moneda, nombre_moneda FROM monedas";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

function obtenerBodegas($conn) {
    $sql = "SELECT id_bodega, nombre_bodega FROM bodegas";
    $result = $conn->query($sql);
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

function obtenerSucursales($conn) {
    $bodega_id = intval($_GET['id_bodega'] ?? 0);
    $stmt = $conn->prepare("SELECT id_sucursal, nombre_sucursal FROM sucursales WHERE id_bodega = ?");
    $stmt->bind_param("i", $bodega_id);
    $stmt->execute();
    $result = $stmt->get_result();
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}

function compararCodigo($conn) {
    $codigo = $_GET['codigo'] ?? ''; 

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
    $materiales = $_POST['materiales'] ?? []; // Recibir los materiales como array

    if (!$codigo || !$nombre || !$bodega || !$sucursal || !$moneda || !$precio || !$descripcion || count($materiales) < 2) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios y debe seleccionar al menos dos materiales."]);
        return;
    }

    // Insertar el producto
    $stmt = $conn->prepare("INSERT INTO productos (codigo, nombre_producto, id_bodega, id_sucursal, id_moneda, precio, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiids", $codigo, $nombre, $bodega, $sucursal, $moneda, $precio, $descripcion);
    if ($stmt->execute()) {
        $id_producto = $conn->insert_id; // Obtener el ID del producto insertado

        // Insertar materiales asociados al producto
        $stmt_material = $conn->prepare("INSERT INTO productomaterial (id_producto, id_material) VALUES (?, ?)");
        foreach ($materiales as $material) {
            $stmt_material->bind_param("ii", $id_producto, $material);
            $stmt_material->execute();
        }

        echo json_encode(["status" => "success", "message" => "Producto guardado con éxito."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al guardar el producto."]);
    }
}
?>
