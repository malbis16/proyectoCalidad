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
    $idModeloSeleccionado = $_POST['id_Modelo'] ?? null;
    $accion = $_POST['accion'] ?? null;

    if ($accion === 'guardar') {
        $nombreModelo = $_POST['nombreModelo'];
        $precio = $_POST['precio'];
        $idMarca = $_POST['id_Marca'];
        $paginaModelo = $_POST['paginaModelo'];

        // Verificar si ya existe un registro para este modelo
        if ($idModeloSeleccionado) {
            // Actualizar el registro existente
            $sql = "UPDATE modelo 
                    SET nombreModelo = ?, precio = ?, id_Marca = ?, paginaModelo = ? 
                    WHERE id_Modelo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdiss", $nombreModelo, $precio, $idMarca, $paginaModelo, $idModeloSeleccionado);
        } else {
            // Insertar un nuevo registro
            $sql = "INSERT INTO modelo (nombreModelo, precio, id_Marca, paginaModelo) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdis", $nombreModelo, $precio, $idMarca, $paginaModelo);
        }
        $stmt->execute();
    } elseif ($accion === 'eliminar' && $idModeloSeleccionado) {
        // Eliminar el registro del modelo
        $sql = "DELETE FROM modelo WHERE id_Modelo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idModeloSeleccionado);
        $stmt->execute();
    }
}

// Obtener la lista de modelos para el SELECT
$sql = "SELECT id_Modelo, nombreModelo FROM modelo";
$resultModelos = $conn->query($sql);

// Obtener las marcas para el SELECT
$sql = "SELECT id_Marca, nombreMarca FROM marca";
$resultMarcas = $conn->query($sql);

// Si se selecciona un modelo, cargar sus datos
if ($idModeloSeleccionado) {
    $sql = "SELECT * FROM modelo WHERE id_Modelo = ?";
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
    <title>Gestionar Modelos - MALBIS</title>
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
        <h2>Gestionar Modelos</h2>

        <!-- Formulario para seleccionar modelo -->
        <form method="POST">
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

        <?php if ($idModeloSeleccionado || !$registro): ?>
            <form method="POST">
                <input type="hidden" name="id_Modelo" value="<?php echo $idModeloSeleccionado; ?>">

                <label for="nombreModelo">Nombre del Modelo</label>
                <input type="text" id="nombreModelo" name="nombreModelo" 
                       value="<?php echo $registro ? $registro['nombreModelo'] : ''; ?>" required>

                <label for="precio">Precio</label>
                <input type="number" id="precio" name="precio" step="0.01"
                       value="<?php echo $registro ? $registro['precio'] : ''; ?>" required>

                <label for="id_Marca">Marca</label>
                <select name="id_Marca" id="id_Marca" required>
                    <option value="">Selecciona una marca</option>
                    <?php while ($row = $resultMarcas->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_Marca']; ?>" 
                            <?php echo ($registro && $registro['id_Marca'] == $row['id_Marca']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['nombreMarca']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="paginaModelo">Página del Modelo</label>
                <input type="text" id="paginaModelo" name="paginaModelo" 
                       value="<?php echo $registro ? $registro['paginaModelo'] : ''; ?>" required>

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
