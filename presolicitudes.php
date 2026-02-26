<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php'; 

// Consultar los datos
$sql = "SELECT nombre, apellidos, direccion, ciudad, pais, modelo, sucursal, idioma_preferido FROM presolicitud";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leer Pre-Solicitudes - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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

    <!-- Contenedor de Pre-Solicitudes -->
    <div class="mensajes-contenedor">
        <h2>Pre-Solicitudes Recibidas</h2>

        <!-- Tabla de pre-solicitudes -->
        <table class="tabla-mensajes">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>País</th>
                    <th>Modelo de Auto</th>
                    <th>Sucursal</th>
                    <th>Idioma Preferido</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verificar si hay pre-solicitudes
                if ($result->num_rows > 0) {
                    // Mostrar cada pre-solicitud
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['apellidos']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ciudad']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['pais']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['modelo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sucursal']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['idioma_preferido']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No hay pre-solicitudes para mostrar</td></tr>";
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
