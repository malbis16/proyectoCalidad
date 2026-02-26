<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php'; 

// Consultar los mensajes
$sql = "SELECT nombreCompleto, email, ciudad, pais, celular, motivoContacto, mensaje FROM contactanos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leer Mensajes - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
    <link rel="stylesheet" href="./css/mensajes.css">
</head>
<body>
    <!-- Barra de Menú Superior -->
    <div class="barra-superior">
        <div class="barra-superior-logo">
            <img src="./images/logo4.jpg" alt="Logo">
        </div>
        <div class="barra-superior-menu">
            <a href="./panelAdm.php">Volver al Panel</a>
            <a href="cierreinicio.php">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Contenedor de Mensajes -->
    <div class="mensajes-contenedor">
        <h2>Mensajes Recibidos</h2>

        <!-- Tabla de mensajes -->
        <table class="tabla-mensajes">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Ciudad</th>
                    <th>País</th>
                    <th>Celular</th>
                    <th>Motivo del Contacto</th>
                    <th>Mensaje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si hay mensajes
                if ($result->num_rows > 0) {
                    // Mostrar cada mensaje
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombreCompleto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pais']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['celular']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['motivoContacto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['mensaje']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay mensajes para mostrar</td></tr>";
                }
                ?>
            </tbody>            
        </table>
    </div>

    <!-- Pie de Página -->
    <footer>
        <p>&copy; 2024 Concesionaria de Autos. Todos los derechos reservados.</p>
    </footer>

    <?php
    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>
