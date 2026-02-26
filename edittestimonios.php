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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    $idTestimonio = $_POST['idTestimonio'];
    $testimonio = $_POST['testimonio'];
    $persona = $_POST['persona'];

    // Actualizar el testimonio en la base de datos
    $sql = "UPDATE testimonios SET testimonio = ?, persona = ? WHERE id_testimonio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $testimonio, $persona, $idTestimonio);
    $stmt->execute();
}

// Obtener la lista de testimonios para el `SELECT`
$sql = "SELECT id_testimonio, testimonio, persona FROM testimonios";
$result = $conn->query($sql);

// Cargar datos del testimonio seleccionado
$registro = null;
if (isset($_POST['idTestimonio']) && $_POST['idTestimonio'] != '') {
    $idTestimonio = $_POST['idTestimonio'];
    $sql = "SELECT * FROM testimonios WHERE id_testimonio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idTestimonio);
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
    <title>Editar Testimonios - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
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
        <h2>Editar Testimonios</h2>

        <!-- Barra de selección de opciones -->
        <form method="POST">
            <label for="idTestimonio">Seleccionar Testimonio</label>
            <select name="idTestimonio" id="idTestimonio" onchange="this.form.submit()">
                <option value="">Selecciona un testimonio</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_testimonio']; ?>" 
                        <?php echo (isset($idTestimonio) && $idTestimonio == $row['id_testimonio']) ? 'selected' : ''; ?>>
                        <?php echo $row['testimonio'] . " - " . $row['persona']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($registro): ?>
        <form method="POST">
            <input type="hidden" name="idTestimonio" value="<?php echo $registro['id_testimonio']; ?>">

            <label for="testimonio">Testimonio</label>
            <textarea id="testimonio" name="testimonio" required><?php echo $registro['testimonio']; ?></textarea>

            <label for="persona">Persona</label>
            <input type="text" id="persona" name="persona" value="<?php echo $registro['persona']; ?>" required>

            <button type="submit" name="accion" value="editar">Actualizar Testimonio</button>
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
