<?php
header('Content-Type: application/json');

// Incluir conexión a la base de datos
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreCompleto = $_POST['nombreCompleto'] ?? '';
    $email = $_POST['email'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $celular = $_POST['celular'] ?? '';
    $motivoContacto = $_POST['motivoContacto'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';

    if (empty($nombreCompleto) || empty($email) || empty($ciudad) || empty($pais) || empty($celular) || empty($mensaje)) {
        echo json_encode(['success' => false]);
        exit;
    }

    $sql = "INSERT INTO contactanos (nombreCompleto, email, ciudad, pais, celular, motivoContacto, mensaje)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nombreCompleto, $email, $ciudad, $pais, $celular, $motivoContacto, $mensaje);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false]);
}
?>
