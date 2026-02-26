<?php 
// Incluir el archivo de conexión
include 'conexion.php';

// Obtener los datos del formulario
$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Aplicar SHA2 a la contraseña ingresada
$hashedPassword = hash('sha256', $password);

// Preparar la consulta para evitar inyecciones SQL
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND password = ?");
if ($stmt === false) {
    // Si hubo un error en la preparación de la consulta, muestra un mensaje de error
    die("Error al preparar la consulta: " . $conn->error);
}
$stmt->bind_param("ss", $usuario, $hashedPassword);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el usuario
if ($result->num_rows > 0) {
    session_start();
    $_SESSION['usuario'] = $usuario;
    header("Location: ../ProyectoPruebas/panelAdm.php");
    exit();
} else {
    header("Location: ../ProyectoPruebas/inicio.html");
    exit();
}

// Cerrar la consulta y la conexión
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
