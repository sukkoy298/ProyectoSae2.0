<?php
require_once 'controladores/conexion.php';

$mensaje = "";

// Obtener id_sistema por GET si existe
$id_sistema_get = isset($_GET['id_sistema']) ? intval($_GET['id_sistema']) : null;

// Registrar requerimiento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar_requerimiento'])) {
    $id_sistema = intval($_POST['id_sistema']);
    $descripcion = $_POST['descripcion'];
    $prioridad = $_POST['prioridad'];
    $fecha_creacion = date('Y-m-d');
    $id_fase = intval($_POST['id_fase']);
    $estado = "Pendiente"; // Siempre pendiente

    $sql = "INSERT INTO requerimiento (Id_sistema, Descripcion, Prioridad, Fecha_creacion, Id_fase, Estado)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssis", $id_sistema, $descripcion, $prioridad, $fecha_creacion, $id_fase, $estado);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Requerimiento registrado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al registrar requerimiento: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Obtener sistemas para el select
$sistemas = [];
$resSistemas = $conn->query("SELECT s.Id_sistema, s.Nombre, c.Nombre_cliente FROM sistema s JOIN cliente c ON s.Id_cliente = c.Id_cliente");
if ($resSistemas && $resSistemas->num_rows > 0) {
    while($row = $resSistemas->fetch_assoc()) {
        $sistemas[] = $row;
    }
}

// Obtener fases para el select
$fases = [];
$resFases = $conn->query("SELECT Id_fase, Nombre FROM fase");
if ($resFases && $resFases->num_rows > 0) {
    while($row = $resFases->fetch_assoc()) {
        $fases[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Requerimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Requerimiento</h2>
    <?php echo $mensaje; ?>
    <form method="post" class="mb-5">
        <input type="hidden" name="registrar_requerimiento" value="1">
        <div class="mb-3">
            <label for="id_sistema" class="form-label">Sistema</label>
            <?php if ($id_sistema_get): ?>
                <?php
                // Buscar el nombre del sistema seleccionado
                $nombre_sistema = '';
                foreach($sistemas as $sistema) {
                    if ($sistema['Id_sistema'] == $id_sistema_get) {
                        $nombre_sistema = $sistema['Nombre'] . " (Cliente: " . $sistema['Nombre_cliente'] . ")";
                        break;
                    }
                }
                ?>
                <input type="hidden" name="id_sistema" value="<?php echo $id_sistema_get; ?>">
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($nombre_sistema); ?>" disabled>
            <?php else: ?>
                <select class="form-select" id="id_sistema" name="id_sistema" required>
                    <option value="">Seleccione un sistema</option>
                    <?php foreach($sistemas as $sistema): ?>
                        <option value="<?php echo $sistema['Id_sistema']; ?>">
                            <?php echo htmlspecialchars($sistema['Nombre']) . " (Cliente: " . htmlspecialchars($sistema['Nombre_cliente']) . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
        </div>
        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select class="form-select" id="prioridad" name="prioridad" required>
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_fase" class="form-label">Fase</label>
            <select class="form-select" id="id_fase" name="id_fase" required>
                <option value="">Seleccione una fase</option>
                <?php foreach($fases as $fase): ?>
                    <option value="<?php echo $fase['Id_fase']; ?>"><?php echo htmlspecialchars($fase['Nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" disabled required>
                <option value="Pendiente">Pendiente</option>
                <option value="En proceso">En proceso</option>
                <option value="Finalizado">Finalizado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Requerimiento</button>
        <a href="panelControl.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>