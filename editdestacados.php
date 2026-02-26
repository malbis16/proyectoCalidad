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
        $idADest = $_POST['idADest'];
        $autoDest = $_POST['autoDest'];
        $desDest = $_POST['desDest'];
        $paginaAuto = $_POST['paginaAuto'];

        // Procesar la imagen solo si se carga una nueva
        if (!empty($_FILES['imageDest']['name'])) {
            $imageDest = 'images/' . basename($_FILES['imageDest']['name']);
            move_uploaded_file($_FILES['imageDest']['tmp_name'], $imageDest);
        } else {
            $imageDest = $_POST['imgActual']; // Usar la imagen actual si no se selecciona una nueva
        }

        // Actualizar el registro en la base de datos
        $sql = "UPDATE autos_destacados SET autoDest = ?, desDest = ?, imageDest = ?, paginaAuto = ? WHERE id_ADest = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $autoDest, $desDest, $imageDest, $paginaAuto, $idADest);
        $stmt->execute();
    }
}

// Obtener la lista de autos destacados para el `SELECT`
$sql = "SELECT id_ADest, autoDest FROM autos_destacados";
$result = $conn->query($sql);

// Cargar datos del registro seleccionado
$registro = null;
if (isset($_POST['idADest']) && $_POST['idADest'] != '') {
    $idADest = $_POST['idADest'];
    $sql = "SELECT * FROM autos_destacados WHERE id_ADest = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idADest);
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
    <title>Gestionar Autos Destacados - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Gestionar Autos Destacados</h2>

        <!-- Barra de selección de autos -->
        <form method="POST">
            <label for="idADest">Seleccionar Auto Destacado</label>
            <select name="idADest" id="idADest" onchange="this.form.submit()">
                <option value="">Selecciona un auto</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_ADest']; ?>" 
                        <?php echo (isset($idADest) && $idADest == $row['id_ADest']) ? 'selected' : ''; ?>>
                        <?php echo $row['autoDest']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idADest" value="<?php echo $registro['id_ADest']; ?>">
            <input type="hidden" name="imgActual" value="<?php echo $registro['imageDest']; ?>">

            <label for="autoDest">Nombre del Auto</label>
            <input type="text" id="autoDest" name="autoDest" value="<?php echo $registro['autoDest']; ?>" required>

            <label for="desDest">Descripción</label>
            <textarea id="desDest" name="desDest" required><?php echo $registro['desDest']; ?></textarea>

            <label for="paginaAuto">Página del Auto</label>
            <input type="text" id="paginaAuto" name="paginaAuto" value="<?php echo $registro['paginaAuto']; ?>" required>

            <label for="imageDest">Imagen</label>
            <input type="file" id="imageDest" name="imageDest">
            <p>Imagen actual: <img src="<?php echo $registro['imageDest']; ?>" alt="" style="width:100px;"></p>

            <button type="submit" name="accion" value="editar">Editar</button>
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
