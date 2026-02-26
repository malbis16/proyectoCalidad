<?php
session_start();

// Verificación de inicio de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php';

// Inicialización de variables
$registro = null;
$idModeloSeleccionado = null;

// Procesar las acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idModeloSeleccionado = $_POST['id_Modelo'];
    $accion = isset($_POST['accion']) ? $_POST['accion'] : null;

    if ($accion === 'guardar') {
        $suspDelantera = $_POST['suspDelantera'];
        $suspTrasera = $_POST['suspTrasera'];
        $frenosDelanteros = $_POST['frenosDelanteros'];
        $frenosTraseros = $_POST['frenosTraseros'];

        // Verificar si ya existe un registro para este modelo
        $sql = "SELECT * FROM chasis WHERE id_Modelo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idModeloSeleccionado);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar el registro existente
            $sql = "UPDATE chasis 
                    SET suspDelantera = ?, suspTrasera = ?, frenosDelanteros = ?, frenosTraseros = ? 
                    WHERE id_Modelo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $suspDelantera, $suspTrasera, $frenosDelanteros, $frenosTraseros, $idModeloSeleccionado);
        } else {
            // Insertar un nuevo registro
            $sql = "INSERT INTO chasis (id_Modelo, suspDelantera, suspTrasera, frenosDelanteros, frenosTraseros) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $idModeloSeleccionado, $suspDelantera, $suspTrasera, $frenosDelanteros, $frenosTraseros);
        }
        $stmt->execute();
    } elseif ($accion === 'eliminar') {
        // Eliminar registro de chasis asociado al modelo
        $sql = "DELETE FROM chasis WHERE id_Modelo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idModeloSeleccionado);
        $stmt->execute();
    }
}

// Obtener la lista de modelos para el SELECT
$sql = "SELECT id_Modelo, nombreModelo FROM modelo";
$resultModelos = $conn->query($sql);

// Si se selecciona un modelo, cargar sus datos
if ($idModeloSeleccionado) {
    $sql = "SELECT * FROM chasis WHERE id_Modelo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idModeloSeleccionado);
    $stmt->execute();
    $result = $stmt->get_result();
    $registro = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Chasis - MALBIS</title>
    <link rel="stylesheet" href="./css/edits.css">
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

    <div class="container">
        <h2>Gestionar Chasis</h2>

        <!-- Formulario único -->
        <form method="POST">
            <!-- Selección de modelo -->
            <label for="id_Modelo">Seleccionar Modelo</label>
            <select name="id_Modelo" id="id_Modelo" onchange="this.form.submit()">
                <option value="">Selecciona un modelo</option>
                <?php while ($row = $resultModelos->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Modelo']; ?>" 
                        <?php echo ($idModeloSeleccionado == $row['id_Modelo']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nombreModelo']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($idModeloSeleccionado): ?>
            <form method="POST">
                <input type="hidden" name="id_Modelo" value="<?php echo $idModeloSeleccionado; ?>">

                <label for="suspDelantera">Suspensión Delantera</label>
                <input type="text" id="suspDelantera" name="suspDelantera" 
                       value="<?php echo $registro ? $registro['suspDelantera'] : ''; ?>" required>

                <label for="suspTrasera">Suspensión Trasera</label>
                <input type="text" id="suspTrasera" name="suspTrasera" 
                       value="<?php echo $registro ? $registro['suspTrasera'] : ''; ?>" required>

                <label for="frenosDelanteros">Frenos Delanteros</label>
                <input type="text" id="frenosDelanteros" name="frenosDelanteros" 
                       value="<?php echo $registro ? $registro['frenosDelanteros'] : ''; ?>" required>

                <label for="frenosTraseros">Frenos Traseros</label>
                <input type="text" id="frenosTraseros" name="frenosTraseros" 
                       value="<?php echo $registro ? $registro['frenosTraseros'] : ''; ?>" required>

                <button type="submit" name="accion" value="guardar">
                    <?php echo $registro ? 'Editar' : 'Agregar'; ?>
                </button>
                
                <?php if ($registro): ?>
                    <button type="submit" name="accion" value="eliminar" 
                            onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                        Eliminar
                    </button>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>

    <!-- Pie de Página -->
    <footer>
        <p>&copy; 2024 Concesionaria de Autos. Todos los derechos reservados.</p>
    </footer> 

    <script src="./javascript/script.js"></script>   
</body>
</html>
