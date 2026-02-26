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
        $idHist = $_POST['idHist'];
        $nombreHist = $_POST['nombreHist'];
        $desHist = $_POST['desHist'];

        // Procesar la imagen solo si se carga una nueva
        if (!empty($_FILES['imgHist']['name'])) {
            $imgHist = 'images/' . basename($_FILES['imgHist']['name']);
            move_uploaded_file($_FILES['imgHist']['tmp_name'], $imgHist);
        } else {
            $imgHist = $_POST['imgActual'];
        }

        // Actualizar en la base de datos
        $sql = "UPDATE historia 
                SET nombreHist = ?, desHist = ?, imgHist = ? 
                WHERE id_Hist = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombreHist, $desHist, $imgHist, $idHist);
        $stmt->execute();
    } elseif ($accion === 'eliminar') {
        $idHist = $_POST['idHist'];
        $sql = "DELETE FROM historia WHERE id_Hist = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idHist);
        $stmt->execute();
    } elseif ($accion === 'agregar') {
        $nombreHist = $_POST['nombreHist'];
        $desHist = $_POST['desHist'];

        $imgHist = 'images/' . basename($_FILES['imgHist']['name']);
        move_uploaded_file($_FILES['imgHist']['tmp_name'], $imgHist);

        $sql = "INSERT INTO historia (nombreHist, desHist, imgHist) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nombreHist, $desHist, $imgHist);
        $stmt->execute();
    }
}

// Obtener la lista de registros para el `SELECT`
$sql = "SELECT id_Hist, nombreHist FROM historia";
$result = $conn->query($sql);

// Cargar datos del registro seleccionado
$registro = null;
if (isset($_POST['idHist']) && $_POST['idHist'] != '') {
    $idHist = $_POST['idHist'];
    $sql = "SELECT * FROM historia WHERE id_Hist = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idHist);
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
    <title>Gestionar Historias - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Gestionar Historia</h2>

        <!-- Barra de selección de opciones -->
        <form method="POST">
            <label for="idHist">Seleccionar Historia</label>
            <select name="idHist" id="idHist" onchange="this.form.submit()">
                <option value="">Selecciona una historia</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Hist']; ?>" 
                        <?php echo (isset($idHist) && $idHist == $row['id_Hist']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nombreHist']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idHist" value="<?php echo $registro['id_Hist']; ?>">
            <input type="hidden" name="imgActual" value="<?php echo $registro['imgHist']; ?>">

            <label for="nombreHist">Nombre</label>
            <input type="text" id="nombreHist" name="nombreHist" value="<?php echo $registro['nombreHist']; ?>" required>

            <label for="desHist">Descripción</label>
            <textarea id="desHist" name="desHist" required><?php echo $registro['desHist']; ?></textarea>

            <label for="imgHist">Imagen</label>
            <input type="file" id="imgHist" name="imgHist">
            <p>Imagen actual: <img src="<?php echo $registro['imgHist']; ?>" alt="" style="width:100px;"></p>

            <button type="submit" name="accion" value="editar">Editar</button>
            <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Estás seguro de eliminar esta historia?')">Eliminar</button>
        </form>
        <?php endif; ?>

        <h3>Agregar Nueva Historia</h3>
        <form method="POST" enctype="multipart/form-data">
            <label for="nombreHist">Nombre</label>
            <input type="text" id="nombreHist" name="nombreHist" required>

            <label for="desHist">Descripción</label>
            <textarea id="desHist" name="desHist" required></textarea>

            <label for="imgHist">Imagen</label>
            <input type="file" id="imgHist" name="imgHist" required>

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
