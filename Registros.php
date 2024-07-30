<?php
require 'config.php';

// Obtener los datos del formulario
$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
$correo = isset($_POST['correo']) ? $_POST['correo'] : '';
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
$pin = isset($_POST['pin']) ? $_POST['pin'] : '';

// Validar que todos los campos no estén vacíos
if (empty($nombre) || empty($correo) || empty($contrasena) || empty($pin)) {
    echo json_encode(array("status" => "error", "message" => "Todos los campos son obligatorios"));
    exit();
}

// Validar el pin (exactamente 4 dígitos)
if (!preg_match('/^\d{4}$/', $pin)) {
    echo json_encode(array("status" => "error", "message" => "El pin debe tener exactamente 4 dígitos"));
    exit();
}

// Validar la contraseña
$regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/";
if (!preg_match($regex, $contrasena)) {
    echo json_encode(array("status" => "error", "message" => "La contraseña no cumple con los requisitos de seguridad"));
    exit();
}

// Hashear la contraseña
$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

// Consultas preparadas para evitar inyección SQL
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contrasena, pin) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $correo, $contrasena_hash, $pin);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success"));
} else {
    echo json_encode(array("status" => "error", "message" => "El registro falló"));
}

// Cerrar la declaración y la conexión
$stmt->close();
$conn->close();
?>
