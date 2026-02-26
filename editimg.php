<?php
session_start();

// Verificación de inicio de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

// Conexión a la base de datos
include 'conexion.php';

// Inicializar variables
$registro = null;
$mensaje = '';

// Procesar las acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = isset($_POST['accion']) ? $_POST['accion'] : null;

    if (isset($_POST['idModelo']) && !empty($_POST['idModelo'])) {
        $idModelo = $_POST['idModelo'];
        
        // Verificar si el modelo tiene imágenes asociadas
        $sql = "SELECT * FROM imagenautos WHERE id_Modelo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idModelo);
        $stmt->execute();
        $resultRegistro = $stmt->get_result();
        $registro = $resultRegistro->fetch_assoc();
    }

    if ($accion === 'editar' && $registro) {
        // Verificar las imágenes actuales y procesarlas
        $imagenDerecha = isset($_POST['imgDerechaActual']) ? $_POST['imgDerechaActual'] : '';
        if (!empty($_FILES['imagenDerecha']['name'])) {
            $imagenDerecha = 'images/' . basename($_FILES['imagenDerecha']['name']);
            move_uploaded_file($_FILES['imagenDerecha']['tmp_name'], $imagenDerecha);
        }

        $imagenInterior = isset($_POST['imgInteriorActual']) ? $_POST['imgInteriorActual'] : '';
        if (!empty($_FILES['imagenInterior']['name'])) {
            $imagenInterior = 'images/' . basename($_FILES['imagenInterior']['name']);
            move_uploaded_file($_FILES['imagenInterior']['tmp_name'], $imagenInterior);
        }

        $imagenIzquierda = isset($_POST['imgIzquierdaActual']) ? $_POST['imgIzquierdaActual'] : '';
        if (!empty($_FILES['imagenIzquierda']['name'])) {
            $imagenIzquierda = 'images/' . basename($_FILES['imagenIzquierda']['name']);
            move_uploaded_file($_FILES['imagenIzquierda']['tmp_name'], $imagenIzquierda);
        }

        // Actualizar en la base de datos
        $sql = "UPDATE imagenautos 
                SET imagenDerecha = ?, imagenInterior = ?, imagenIzquierda = ? 
                WHERE id_Modelo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $imagenDerecha, $imagenInterior, $imagenIzquierda, $idModelo);
        $stmt->execute();

        $mensaje = 'Imágenes actualizadas correctamente.';
    } elseif ($accion === 'agregar') {
        // Insertar nuevas imágenes
        $imagenDerecha = 'images/' . basename($_FILES['imagenDerecha']['name']);
        move_uploaded_file($_FILES['imagenDerecha']['tmp_name'], $imagenDerecha);

        $imagenInterior = 'images/' . basename($_FILES['imagenInterior']['name']);
        move_uploaded_file($_FILES['imagenInterior']['tmp_name'], $imagenInterior);

        $imagenIzquierda = 'images/' . basename($_FILES['imagenIzquierda']['name']);
        move_uploaded_file($_FILES['imagenIzquierda']['tmp_name'], $imagenIzquierda);

        // Insertar en la base de datos
        $sql = "INSERT INTO imagenautos (id_Modelo, imagenDerecha, imagenInterior, imagenIzquierda) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $idModelo, $imagenDerecha, $imagenInterior, $imagenIzquierda);
        $stmt->execute();

        $mensaje = 'Imágenes agregadas correctamente.';
    } elseif ($accion === 'eliminar' && $registro) {
        // Eliminar las imágenes de la base de datos
        $sql = "DELETE FROM imagenautos WHERE id_Modelo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idModelo);
        $stmt->execute();

        $mensaje = 'Registro de imágenes eliminado correctamente de la base de datos.';
    }
}

// Obtener la lista de modelos para el `SELECT`
$sql = "SELECT id_Modelo, nombreModelo FROM modelo";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Imágenes de Autos</title>
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
        <h2>Gestionar Imágenes de Autos</h2>

        <!-- Mensaje de éxito o error -->
        <?php if ($mensaje): ?>
            <p style="color: green;"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <!-- Barra de selección de modelos -->
        <form method="POST">
            <label for="idModelo">Seleccionar Modelo</label>
            <select name="idModelo" id="idModelo" onchange="this.form.submit()" required>
                <option value="">Selecciona un modelo</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_Modelo']; ?>" 
                        <?php echo (isset($idModelo) && $idModelo == $row['id_Modelo']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row['nombreModelo']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro || isset($idModelo)): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="idModelo" value="<?php echo $idModelo; ?>">
            <input type="hidden" name="imgDerechaActual" value="<?php echo $registro['imagenDerecha'] ?? ''; ?>">
            <input type="hidden" name="imgInteriorActual" value="<?php echo $registro['imagenInterior'] ?? ''; ?>">
            <input type="hidden" name="imgIzquierdaActual" value="<?php echo $registro['imagenIzquierda'] ?? ''; ?>">

            <label for="imagenDerecha">Imagen Derecha</label>
            <input type="file" name="imagenDerecha">
            <?php if ($registro && $registro['imagenDerecha']): ?>
                <p>Imagen actual: <img src="<?php echo $registro['imagenDerecha']; ?>" alt="Imagen Derecha" style="width:100px;"></p>
            <?php endif; ?>

            <label for="imagenInterior">Imagen Interior</label>
            <input type="file" name="imagenInterior">
            <?php if ($registro && $registro['imagenInterior']): ?>
                <p>Imagen actual: <img src="<?php echo $registro['imagenInterior']; ?>" alt="Imagen Interior" style="width:100px;"></p>
            <?php endif; ?>

            <label for="imagenIzquierda">Imagen Izquierda</label>
            <input type="file" name="imagenIzquierda">
            <?php if ($registro && $registro['imagenIzquierda']): ?>
                <p>Imagen actual: <img src="<?php echo $registro['imagenIzquierda']; ?>" alt="Imagen Izquierda" style="width:100px;"></p>
            <?php endif; ?>

            <button type="submit" name="accion" value="<?php echo $registro ? 'editar' : 'agregar'; ?>">
                <?php echo $registro ? 'Editar' : 'Agregar'; ?> Imágenes
            </button>
            <?php if ($registro): ?>
                <button type="submit" name="accion" value="eliminar" onclick="return confirm('¿Estás seguro de eliminar estas imágenes de la base de datos?')">
                    Eliminar Imágenes
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
