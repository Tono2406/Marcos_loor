<?php
require 'config.php';

if (!isset($_POST['correo']) || !isset($_POST['contrasena']) || !isset($_POST['pin'])) {
    echo json_encode(array("status" => "error", "message" => "Faltan datos necesarios"));
    exit();
}

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$pin = $_POST['pin'];

// Prevenir inyecciones SQL utilizando consultas preparadas
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verificar la contraseña
    if (password_verify($contrasena, $row['contrasena'])) {
        // Aquí debes verificar el PIN guardado en la base de datos
        if ($row['pin'] == $pin) {
            echo json_encode(array("status" => "success", "message" => "PIN válido"));
        } else {
            echo json_encode(array("status" => "error", "message" => "PIN inválido"));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Correo o contraseña incorrectos"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Correo o contraseña incorrectos"));
}

$stmt->close();
mysqli_close($conn);
?>
