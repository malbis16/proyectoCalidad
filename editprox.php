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
        $idProx = $_POST['idProx'];
        $nombreProx = $_POST['nombreProx'];
        $fechaProx = $_POST['fechaProx'];
        $desProx = $_POST['desProx'];

        // Procesar la imagen solo si se carga una nueva
        if (!empty($_FILES['imagenProx']['name'])) {
            $imagenProx = 'images/' . basename($_FILES['imagenProx']['name']);
            move_uploaded_file($_FILES['imagenProx']['tmp_name'], $imagenProx);
        } else {
            $imagenProx = $_POST['imagenActual'];
        }

        // Actualizar en la base de datos
        $sql = "UPDATE proximamente 
                SET nombreProx = ?, fechaProx = ?, desProx = ?, imagenProx = ? 
                WHERE id_Prox = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombreProx, $fechaProx, $desProx, $imagenProx, $idProx);
        $stmt->execute();
    } elseif ($accion === 'eliminar') {
        $idProx = $_POST['idProx'];
        $sql = "DELETE FROM proximamente WHERE id_Prox = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idProx);
        $stmt->execute();
    } elseif ($accion === 'agregar') {
        $nombreProx = $_POST['nombreProx'];
        $fechaProx = $_POST['fechaProx'];
        $desProx = $_POST['desProx'];

        $imagenProx = 'images/' . basename($_FILES['imagenProx']['name']);
        move_uploaded_file($_FILES['imagenProx']['tmp_name'], $imagenProx);

        $sql = "INSERT INTO proximamente (nombreProx, fechaProx, desProx, imagenProx) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombreProx, $fechaProx, $desProx, $imagenProx);
        $stmt->execute();
    }
}

// Obtener la lista de registros para el `SELECT`
$sql = "SELECT id_Prox, nombreProx FROM proximamente";
$result = $conn->query($sql);

// Cargar datos del registro seleccionado
$registro = null;
if (isset($_POST['idProx']) && $_POST['idProx'] != '') {
    $idProx = $_POST['idProx'];
    $sql = "SELECT * FROM proximamente WHERE id_Prox = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idProx);
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
    <title>Editar Proximamente - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Editar Proximamente</h2>

        <!-- Barra de selección de opciones -->
        <form method="POST">
            <label for="idProx">Seleccionar ID</label>
            <select name="idProx" id="idProx" onchange="this.form.submit()">
                <option value="">Selecciona un auto</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Prox']; ?>" 
                        <?php echo (isset($idProx) && $idProx == $row['id_Prox']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nombreProx']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idProx" value="<?php echo $registro['id_Prox']; ?>">
            <input type="hidden" name="imagenActual" value="<?php echo $registro['imagenProx']; ?>">

            <label for="nombreProx">Nombre</label>
            <input type="text" id="nombreProx" name="nombreProx" value="<?php echo $registro['nombreProx']; ?>" required>

            <label for="fechaProx">Fecha</label>
            <input type="date" id="fechaProx" name="fechaProx" value="<?php echo $registro['fechaProx']; ?>" required>

            <label for="desProx">Descripción</label>
            <textarea id="desProx" name="desProx" required><?php echo $registro['desProx']; ?></textarea>

            <label for="imagenProx">Imagen</label>            
            <input type="file" id="imagenProx" name="imagenProx" accept=".jpg, .jpeg, .png">
            <p>Imagen actual: <img src="<?php echo $registro['imagenProx']; ?>" alt="" style="width:100px;"></p>

            <button type="submit" name="accion" value="editar">Editar</button>
            <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</button>
        </form>
        <?php endif; ?>

        <h3>Agregar Nuevo</h3>
        <form method="POST" enctype="multipart/form-data">
            <label for="nombreProx">Nombre</label>
            <input type="text" id="nombreProx" name="nombreProx" required>

            <?php 
            // 1. Fecha actual para el límite mínimo
            $fechaHoy = date('Y-m-d');             
            // 2. Fecha máxima 5 años
            $fechaMax = date('Y-m-d', strtotime('+5 years')); 
            ?>

            <label for="fechaProx">Fecha</label>
            <input type="date" id="fechaProx" name="fechaProx" min="<?php echo $fechaHoy; ?>" max="<?php echo $fechaMax; ?>" required>
            

            <label for="desProx">Descripción</label>
            <textarea id="desProx" name="desProx" required></textarea>

            <label for="imagenProx">Imagen</label>            
            <input type="file" id="imagenProx" name="imagenProx" accept=".jpg, .jpeg, .png" required>
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
