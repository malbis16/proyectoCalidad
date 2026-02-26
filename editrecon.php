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
        $idReconocimiento = $_POST['idReconocimiento'];
        // Procesar la imagen solo si se carga una nueva
        if (!empty($_FILES['imgRecon']['name'])) {
            $imgRecon = 'images/' . basename($_FILES['imgRecon']['name']);
            move_uploaded_file($_FILES['imgRecon']['tmp_name'], $imgRecon);
        } else {
            $imgRecon = $_POST['imgActual'];
        }

        $premio = $_POST['premio'];
        $revista = $_POST['revista'];

        // Actualizar en la base de datos
        $sql = "UPDATE reconocimientos SET premio = ?, revista = ?, imgRecon = ? WHERE id_Reconocimiento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $premio, $revista, $imgRecon, $idReconocimiento);
        $stmt->execute();
    } elseif ($accion === 'eliminar') {
        $idReconocimiento = $_POST['idReconocimiento'];
        $sql = "DELETE FROM reconocimientos WHERE id_Reconocimiento = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idReconocimiento);
        $stmt->execute();
    } elseif ($accion === 'agregar') {
        // Subir nueva imagen
        $imgRecon = 'images/' . basename($_FILES['imgRecon']['name']);
        move_uploaded_file($_FILES['imgRecon']['tmp_name'], $imgRecon);

        $premio = $_POST['premio'];
        $revista = $_POST['revista'];

        $sql = "INSERT INTO reconocimientos (premio, revista, imgRecon) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $premio, $revista, $imgRecon);
        $stmt->execute();
    }
}

// Obtener la lista de registros para el `SELECT`
$sql = "SELECT id_Reconocimiento, premio, revista, imgRecon FROM reconocimientos";
$result = $conn->query($sql);

// Cargar datos del registro seleccionado
$registro = null;
if (isset($_POST['idReconocimiento']) && $_POST['idReconocimiento'] != '') {
    $idReconocimiento = $_POST['idReconocimiento'];
    $sql = "SELECT * FROM reconocimientos WHERE id_Reconocimiento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idReconocimiento);
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
    <title>Gestionar Reconocimientos - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Gestionar Reconocimientos</h2>

        <!-- Barra de selección de opciones -->
        <form method="POST">
            <label for="idReconocimiento">Seleccionar Reconocimiento</label>
            <select name="idReconocimiento" id="idReconocimiento" onchange="this.form.submit()">
                <option value="">Selecciona un reconocimiento</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Reconocimiento']; ?>" 
                        <?php echo (isset($idReconocimiento) && $idReconocimiento == $row['id_Reconocimiento']) ? 'selected' : ''; ?>>
                        <?php echo $row['premio'] . " - " . $row['revista']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idReconocimiento" value="<?php echo $registro['id_Reconocimiento']; ?>">
            <input type="hidden" name="imgActual" value="<?php echo $registro['imgRecon']; ?>">

            <label for="premio">Premio</label>
            <input type="text" id="premio" name="premio" value="<?php echo $registro['premio']; ?>" required>

            <label for="revista">Revista</label>
            <input type="text" id="revista" name="revista" value="<?php echo $registro['revista']; ?>" required>

            <label for="imgRecon">Imagen</label>
            <input type="file" id="imgRecon" name="imgRecon">
            <p>Imagen actual: <img src="<?php echo $registro['imgRecon']; ?>" alt="" style="width:100px;"></p>

            <button type="submit" name="accion" value="editar">Editar</button>
            <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Estás seguro de eliminar este reconocimiento?')">Eliminar</button>
        </form>
        <?php endif; ?>

        <h3>Agregar Nuevo Reconocimiento</h3>
        <form method="POST" enctype="multipart/form-data">
            <label for="premio">Premio</label>
            <input type="text" id="premio" name="premio" required>

            <label for="revista">Revista</label>
            <input type="text" id="revista" name="revista" required>

            <label for="imgRecon">Imagen</label>
            <input type="file" id="imgRecon" name="imgRecon" required>

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
