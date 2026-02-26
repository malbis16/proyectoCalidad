<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

// Inicializamos las variables para el filtro
$orden_precio = isset($_GET['orden_precio']) ? $_GET['orden_precio'] : 'ASC';
$marca_filtro = isset($_GET['marca']) ? $_GET['marca'] : '';

// Consulta para obtener las marcas para el filtro de marcas
$sql_marcas = "SELECT DISTINCT nombreMarca FROM vista_catalogo";
$result_marcas = $conn->query($sql_marcas);

// Consulta con los filtros de orden y marca
$sql = $conn->prepare("SELECT id_Modelo, nombreModelo, precio, nombreMarca, desCat, imagenDerecha, paginaModelo 
                        FROM vista_catalogo 
                        WHERE nombreMarca LIKE ? 
                        ORDER BY precio $orden_precio");
$sql->bind_param("s", $marca_param);
$marca_param = $marca_filtro === "mostrar_todos" ? "%" : "%$marca_filtro%";
$sql->execute();
$result = $sql->get_result();

$autos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Autos</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_catalogo.css">
</head>
<body>
    <!-- Barra de Menú Superior -->
    <div class="barra-superior">
        <div class="barra-superior-logo">
            <img src="./images/logo4.jpg" alt="Logo de la Empresa">
        </div>
        <div class="barra-superior-menu">
            <a href="index.php">Inicio</a>
            <a href="historia.php">Historia</a>
            <a href="proxi.php">Próximamente</a>
            <a href="catalogo.php">Catálogo de Vehículos</a>
            <a href="contacto.html">Contacto</a>
        </div>
    </div>

    <section class="contenido">
        <div class="autos-destacados">
            <h3 class="titulo-seccion">Catálogo de Autos</h3>
            <hr>

            <!-- Filtros -->
            <form method="GET" class="filtros">
                <div class="filtro">
                    <label for="orden_precio">Ordenar por precio:</label>
                    <select name="orden_precio" id="orden_precio">
                        <option value="ASC" <?php echo $orden_precio == 'ASC' ? 'selected' : ''; ?>>De menor a mayor</option>
                        <option value="DESC" <?php echo $orden_precio == 'DESC' ? 'selected' : ''; ?>>De mayor a menor</option>
                    </select>
                </div>
                <div class="filtro">
                    <label for="marca">Marca:</label>
                    <select name="marca" id="marca">
                        <option value="mostrar_todos" <?php echo $marca_filtro == 'mostrar_todos' ? 'selected' : ''; ?>>Mostrar Todos</option>
                        <?php while ($row = $result_marcas->fetch_assoc()): ?>
                            <option value="<?php echo $row['nombreMarca']; ?>" 
                                    <?php echo $row['nombreMarca'] == $marca_filtro ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['nombreMarca']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Filtrar</button>
            </form>

            <!-- Tarjetas de autos -->
            <div class="tarjetas-autos">
                <?php if (!empty($autos)): ?>
                    <?php foreach ($autos as $auto): ?>
                        <div class="tarjeta-auto">
                            <img src="<?php echo $auto['imagenDerecha'] ?: 'ruta/a/imagen-default.jpg'; ?>" 
                                 alt="Imagen de <?php echo htmlspecialchars($auto['nombreModelo']); ?>">
                            <h4><?php echo htmlspecialchars($auto['nombreModelo']); ?></h4>
                            <p><strong>Marca:</strong> <?php echo htmlspecialchars($auto['nombreMarca']); ?></p>
                            <p><strong>Precio:</strong> $<?php echo number_format($auto['precio'], 2); ?></p>
                            <p><?php echo htmlspecialchars($auto['desCat']); ?></p>
                            <a href="<?php echo $auto['paginaModelo']; ?>" class="btn">Ver más</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay autos disponibles para los filtros seleccionados.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer>
        <div class="enlaces">
            <a href="./docs/privacidad.pdf" target="_blank">Política de Privacidad</a>
            <a href="./docs/terminos.pdf" target="_blank">Términos de Servicio</a>
            <a href="contacto.html">Contacto</a>
        </div>
    </footer>

    <script src="./javascript/script.js"></script>
</body>
</html>
