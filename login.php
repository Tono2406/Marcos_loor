<?php
require 'config.php';

// Verificar que se reciban todos los datos necesarios
if (!isset($_POST['correo']) || !isset($_POST['contrasena'])) {
    echo json_encode(array("status" => "error", "message" => "Faltan datos necesarios"));
    exit();
}

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Prevenir inyecciones SQL utilizando consultas preparadas
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verificar la contraseña
    if (password_verify($contrasena, $row['contrasena'])) {
        // Enviar una respuesta exitosa y permitir la redirección al PIN
        echo json_encode(array("status" => "success", "message" => "Login exitoso"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Correo o contraseña incorrectos"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Correo o contraseña incorrectos"));
}

$stmt->close();
mysqli_close($conn);
?>
