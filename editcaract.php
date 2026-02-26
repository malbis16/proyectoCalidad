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
        $cilindrada = $_POST['cilindrada'];
        $configuracion = $_POST['configuracion'];
        $potencia = $_POST['potencia'];
        $torque = $_POST['torque'];

        // Verificar si ya existe un registro para este modelo
        $sql = "SELECT * FROM caracteristicas WHERE id_Modelo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idModeloSeleccionado);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Actualizar el registro existente
            $sql = "UPDATE caracteristicas 
                    SET cilindrada = ?, configuracion = ?, potencia = ?, torque = ? 
                    WHERE id_Modelo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $cilindrada, $configuracion, $potencia, $torque, $idModeloSeleccionado);
        } else {
            // Insertar un nuevo registro
            $sql = "INSERT INTO caracteristicas (id_Modelo, cilindrada, configuracion, potencia, torque) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $idModeloSeleccionado, $cilindrada, $configuracion, $potencia, $torque);
        }
        $stmt->execute();
    } elseif ($accion === 'eliminar') {
        // Eliminar registro de caracteristicas asociado al modelo
        $sql = "DELETE FROM caracteristicas WHERE id_Modelo = ?";
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
    $sql = "SELECT * FROM caracteristicas WHERE id_Modelo = ?";
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
    <title>Gestionar Características - MALBIS</title>
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
        <h2>Gestionar Características</h2>

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

                <label for="cilindrada">Cilindrada</label>
                <input type="text" id="cilindrada" name="cilindrada" 
                       value="<?php echo $registro ? $registro['cilindrada'] : ''; ?>" required>

                <label for="configuracion">Configuración</label>
                <input type="text" id="configuracion" name="configuracion" 
                       value="<?php echo $registro ? $registro['configuracion'] : ''; ?>" required>

                <label for="potencia">Potencia</label>
                <input type="text" id="potencia" name="potencia" 
                       value="<?php echo $registro ? $registro['potencia'] : ''; ?>" required>

                <label for="torque">Torque</label>
                <input type="text" id="torque" name="torque" 
                       value="<?php echo $registro ? $registro['torque'] : ''; ?>" required>

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
