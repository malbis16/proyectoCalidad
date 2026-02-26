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
        $idCarrusel = $_POST['idCarrusel'];
        // Procesar la imagen solo si se carga una nueva
        if (!empty($_FILES['imgCarrusel']['name'])) {
            $imgCarrusel = 'images/' . basename($_FILES['imgCarrusel']['name']);
            move_uploaded_file($_FILES['imgCarrusel']['tmp_name'], $imgCarrusel);
        } else {
            $imgCarrusel = $_POST['imgActual'];
        }

        // Actualizar en la base de datos
        $sql = "UPDATE indexcarrusel SET imgCarrusel = ? WHERE id_Carrusel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $imgCarrusel, $idCarrusel);
        $stmt->execute();
    } elseif ($accion === 'eliminar') {
        $idCarrusel = $_POST['idCarrusel'];
        $sql = "DELETE FROM indexcarrusel WHERE id_Carrusel = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCarrusel);
        $stmt->execute();
    } elseif ($accion === 'agregar') {
        // Subir nueva imagen
        $imgCarrusel = 'images/' . basename($_FILES['imgCarrusel']['name']);
        move_uploaded_file($_FILES['imgCarrusel']['tmp_name'], $imgCarrusel);

        $sql = "INSERT INTO indexcarrusel (imgCarrusel) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $imgCarrusel);
        $stmt->execute();
    }
}

// Obtener la lista de registros para el `SELECT`
$sql = "SELECT id_Carrusel, imgCarrusel FROM indexcarrusel";
$result = $conn->query($sql);

// Cargar datos del registro seleccionado
$registro = null;
if (isset($_POST['idCarrusel']) && $_POST['idCarrusel'] != '') {
    $idCarrusel = $_POST['idCarrusel'];
    $sql = "SELECT * FROM indexcarrusel WHERE id_Carrusel = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCarrusel);
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
    <title>Gestionar Carrusel - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Gestionar Carrusel</h2>

        <!-- Barra de selección de opciones -->
        <form method="POST">
            <label for="idCarrusel">Seleccionar Imagen del Carrusel</label>
            <select name="idCarrusel" id="idCarrusel" onchange="this.form.submit()">
                <option value="">Selecciona una imagen</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Carrusel']; ?>" 
                        <?php echo (isset($idCarrusel) && $idCarrusel == $row['id_Carrusel']) ? 'selected' : ''; ?>>
                        <?php echo "Imagen " . $row['id_Carrusel']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idCarrusel" value="<?php echo $registro['id_Carrusel']; ?>">
            <input type="hidden" name="imgActual" value="<?php echo $registro['imgCarrusel']; ?>">

            <label for="imgCarrusel">Imagen</label>
            <input type="file" id="imgCarrusel" name="imgCarrusel">
            <p>Imagen actual: <img src="<?php echo $registro['imgCarrusel']; ?>" alt="" style="width:100px;"></p>

            <button type="submit" name="accion" value="editar">Editar</button>
            <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Estás seguro de eliminar esta imagen del carrusel?')">Eliminar</button>
        </form>
        <?php endif; ?>

        <h3>Agregar Nueva Imagen al Carrusel</h3>
        <form method="POST" enctype="multipart/form-data">
            <label for="imgCarrusel">Imagen</label>
            <input type="file" id="imgCarrusel" name="imgCarrusel" required>

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
