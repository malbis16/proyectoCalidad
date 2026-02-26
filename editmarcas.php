<?php
session_start();

// Verificación de inicio de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php';

// Procesar las acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = isset($_POST['accion']) ? $_POST['accion'] : null;

    if ($accion === 'editar') {
        $idMarca = $_POST['idMarca'];
        $nombreMarca = $_POST['nombreMarca'];

        // Actualizar en la base de datos
        $sql = "UPDATE marca SET nombreMarca = ? WHERE id_Marca = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nombreMarca, $idMarca);
        $stmt->execute();
    } elseif ($accion === 'eliminar') {
        $idMarca = $_POST['idMarca'];
        $sql = "DELETE FROM marca WHERE id_Marca = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idMarca);
        $stmt->execute();
    } elseif ($accion === 'agregar') {
        $nombreMarca = $_POST['nombreMarca'];

        $sql = "INSERT INTO marca (nombreMarca) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nombreMarca);
        $stmt->execute();
    }
}

// Obtener la lista de marcas para el `SELECT`
$sql = "SELECT id_Marca, nombreMarca FROM marca";
$result = $conn->query($sql);

// Cargar datos del registro seleccionado
$registro = null;
if (isset($_POST['idMarca']) && $_POST['idMarca'] != '') {
    $idMarca = $_POST['idMarca'];
    $sql = "SELECT * FROM marca WHERE id_Marca = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idMarca);
    $stmt->execute();
    $resultRegistro = $stmt->get_result();
    $registro = $resultRegistro->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Marcas - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Gestionar Marcas</h2>

        <!-- Barra de selección de marcas -->
        <form method="POST">
            <label for="idMarca">Seleccionar Marca</label>
            <select name="idMarca" id="idMarca" onchange="this.form.submit()">
                <option value="">Selecciona una marca</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Marca']; ?>" 
                        <?php echo (isset($idMarca) && $idMarca == $row['id_Marca']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nombreMarca']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST">
            <input type="hidden" name="idMarca" value="<?php echo $registro['id_Marca']; ?>">

            <label for="nombreMarca">Nombre de la Marca</label>
            <input type="text" id="nombreMarca" name="nombreMarca" value="<?php echo $registro['nombreMarca']; ?>" required>

            <button type="submit" name="accion" value="editar">Editar</button>
            <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Estás seguro de eliminar esta marca?')">Eliminar</button>
        </form>
        <?php endif; ?>

        <h3>Agregar Nueva Marca</h3>
        <form method="POST">
            <label for="nombreMarca">Nombre de la Marca</label>
            <input type="text" id="nombreMarca" name="nombreMarca" required>

            <button type="submit" name="accion" value="agregar">Agregar</button>
        </form>
    </div>

    <!-- Pie de Página -->
    <footer>
        <p>&copy; 2024 Concesionaria de Autos. Todos los derechos reservados.</p>
    </footer> 

    <script src="./javascript/script.js"></script>   
</body>
</html>
