<?php
require 'config.php';

// Verifica si las claves están definidas y no están vacías
$requiredFields = [
    'nombre' => 'Nombre',
    'descripcion' => 'Descripción',
    'categoria' => 'Categoría',
    'precio' => 'Precio',
    'cantidad_stock' => 'Cantidad en stock',
    'marca' => 'Marca',
    'fecha_adquisicion' => 'Fecha de adquisición',
    'fecha_vencimiento' => 'Fecha de vencimiento',
    'proveedor' => 'Proveedor',
    'estado' => 'Estado'
];

$errors = [];

// Verifica cada campo requerido
foreach ($requiredFields as $key => $fieldName) {
    if (empty($_POST[$key])) {
        $errors[] = "$fieldName es obligatorio.";
    } else {
        $$key = $_POST[$key]; // Asigna el valor a una variable con el nombre del campo
    }
}

if (!empty($errors)) {
    echo json_encode(array("status" => "error", "message" => implode(", ", $errors)));
    exit();
}

// Prepara la consulta SQL
$sql = "INSERT INTO productos (nombre, descripcion, categoria, precio, cantidad_stock, marca, fecha_adquisicion, fecha_vencimiento, proveedor, estado) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssdisssss", $nombre, $descripcion, $categoria, $precio, $cantidad_stock, $marca, $fecha_adquisicion, $fecha_vencimiento, $proveedor, $estado);

// Ejecuta la consulta
if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Producto registrado exitosamente"));
} else {
    echo json_encode(array("status" => "error", "message" => "Error al registrar el producto"));
}

// Cierra la conexión
$stmt->close();
$conn->close();
?>
