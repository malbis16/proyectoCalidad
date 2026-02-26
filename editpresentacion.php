<?php
session_start();

// Verificación de inicio de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php';

// Procesar la acción de editar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $idInfor = $_POST['idInfor'];
    $nombreInfor = $_POST['nombreInfor'];
    $desInfor = $_POST['desInfor'];

    // Procesar la imagen solo si se carga una nueva
    if (!empty($_FILES['imgInfor']['name'])) {
        $imgInfor = 'images/' . basename($_FILES['imgInfor']['name']);
        move_uploaded_file($_FILES['imgInfor']['tmp_name'], $imgInfor);
    } else {
        $imgInfor = $_POST['imgActual'];
    }

    // Actualizar en la base de datos
    $sql = "UPDATE indexinfor 
            SET nombreInfor = ?, desInfor = ?, imgInfor = ? 
            WHERE id_Infor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nombreInfor, $desInfor, $imgInfor, $idInfor);
    $stmt->execute();

    // Establecer mensaje de éxito
    $_SESSION['mensaje'] = "Edición realizada con éxito!";
    header("Location: editpresentacion.php");
    exit();
}

// Obtener la lista de registros para el `SELECT`
$sql = "SELECT id_Infor, nombreInfor FROM indexinfor";
$result = $conn->query($sql);

// Cargar datos del registro seleccionado
$registro = null;
if (isset($_POST['idInfor']) && $_POST['idInfor'] != '') {
    $idInfor = $_POST['idInfor'];
    $sql = "SELECT * FROM indexinfor WHERE id_Infor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idInfor);
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
    <title>Gestionar Información - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Gestionar Información</h2>

        <!-- Mostrar mensaje de edición exitosa -->
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo '<div class="mensaje-exito">' . $_SESSION['mensaje'] . '</div>';
            unset($_SESSION['mensaje']);
        }
        ?>

        <!-- Barra de selección de opciones -->
        <form method="POST">
            <label for="idInfor">Seleccionar Información</label>
            <select name="idInfor" id="idInfor" onchange="this.form.submit()">
                <option value="">Selecciona una información</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Infor']; ?>" 
                        <?php echo (isset($idInfor) && $idInfor == $row['id_Infor']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nombreInfor']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idInfor" value="<?php echo $registro['id_Infor']; ?>">
            <input type="hidden" name="imgActual" value="<?php echo $registro['imgInfor']; ?>">

            <label for="nombreInfor">Nombre</label>
            <input type="text" id="nombreInfor" name="nombreInfor" value="<?php echo $registro['nombreInfor']; ?>" required>

            <label for="desInfor">Descripción</label>
            <textarea id="desInfor" name="desInfor" required><?php echo $registro['desInfor']; ?></textarea>

            <label for="imgInfor">Imagen</label>
            <input type="file" id="imgInfor" name="imgInfor">
            <p>Imagen actual: <img src="<?php echo $registro['imgInfor']; ?>" alt="" style="width:100px;"></p>

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
