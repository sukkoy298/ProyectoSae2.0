<?php
require_once 'controladores/conexion.php';

$mensaje = "";
$id_requerimiento = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener datos actuales del requerimiento
$requerimiento = null;
if ($id_requerimiento > 0) {
    $sql = "SELECT * FROM requerimiento WHERE Id_requerimiento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_requerimiento);
    $stmt->execute();
    $result = $stmt->get_result();
    $requerimiento = $result->fetch_assoc();
    $stmt->close();
}

if (!$requerimiento) {
    echo "<div class='alert alert-danger'>Requerimiento no encontrado.</div>";
    exit;
}

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sistema = intval($_POST['id_sistema']);
    $descripcion = $_POST['descripcion'];
    $prioridad = $_POST['prioridad'];
    $fecha_creacion = $_POST['fecha_creacion'];
    $id_fase = intval($_POST['id_fase']);
    $estado = $_POST['estado'];

    $sql = "UPDATE requerimiento SET Id_sistema=?, Descripcion=?, Prioridad=?, Fecha_creacion=?, Id_fase=?, Estado=?, Fecha_modificacion=NOW() WHERE Id_requerimiento=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssisi", $id_sistema, $descripcion, $prioridad, $fecha_creacion, $id_fase, $estado, $id_requerimiento);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Requerimiento actualizado correctamente.</div>";
        // Recargar datos actualizados
        $sql = "SELECT * FROM requerimiento WHERE Id_requerimiento = ?";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("i", $id_requerimiento);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $requerimiento = $result->fetch_assoc();
        $stmt2->close();
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar requerimiento: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Obtener opciones de sistemas y fases para los selects
$sistemas = [];
$res = $conn->query("SELECT Id_sistema, Nombre FROM sistema");
while ($row = $res->fetch_assoc()) {
    $sistemas[] = $row;
}
$fases = [];
$res = $conn->query("SELECT Id_fase, Nombre FROM fase");
while ($row = $res->fetch_assoc()) {
    $fases[] = $row;
}
$estados = ['Pendiente', 'En Progreso', 'Finalizado', 'Eliminado'];
$prioridades = ['Baja', 'Media', 'Alta'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Requerimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style= "background-color:#F8F8FF">
<div class="container mt-5">
    <h2>Editar Requerimiento</h2>
    <?php echo $mensaje; ?>
    <form method="post" action="">
        <div class="mb-3">
            <label for="id_sistema" class="form-label">Sistema</label>
            <select class="form-control" id="id_sistema" name="id_sistema" required>
                <?php foreach ($sistemas as $s): ?>
                    <option value="<?php echo $s['Id_sistema']; ?>" <?php if ($requerimiento['Id_sistema'] == $s['Id_sistema']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($s['Nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo htmlspecialchars($requerimiento['Descripcion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="prioridad" class="form-label">Prioridad</label>
            <select class="form-control" id="prioridad" name="prioridad" required>
                <?php foreach ($prioridades as $p): ?>
                    <option value="<?php echo $p; ?>" <?php if ($requerimiento['Prioridad'] == $p) echo 'selected'; ?>><?php echo $p; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_creacion" class="form-label">Fecha de creación</label>
            <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion" value="<?php echo htmlspecialchars($requerimiento['Fecha_creacion']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="id_fase" class="form-label">Fase</label>
            <select class="form-control" id="id_fase" name="id_fase" required>
                <?php foreach ($fases as $f): ?>
                    <option value="<?php echo $f['Id_fase']; ?>" <?php if ($requerimiento['Id_fase'] == $f['Id_fase']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($f['Nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
                <?php foreach ($estados as $e): ?>
                    <option value="<?php echo $e; ?>" <?php if ($requerimiento['Estado'] == $e) echo 'selected'; ?>><?php echo $e; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-secondary" onclick="history.back();">Volver</button>
    </form>
</div>
</body>
</html>