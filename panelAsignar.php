<?php
require_once 'controladores/conexion.php';

$mensaje = "";

// Obtener id del desarrollador por GET
$id_desarrollador = isset($_GET['id_desarrollador']) ? intval($_GET['id_desarrollador']) : null;

// Obtener fases para el select
$fases = [];
$resFases = $conn->query("SELECT Id_fase, Nombre FROM fase");
if ($resFases && $resFases->num_rows > 0) {
    while($row = $resFases->fetch_assoc()) {
        $fases[] = $row;
    }
}

// Registrar reporte/cambio y asignación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar_reporte'])) {
    $id_requerimiento = intval($_POST['id_requerimiento']);
    $cambio_realizado = $_POST['cambio_realizado'];
    $fecha_cambio = date('Y-m-d');
    $id_fase_nueva = intval($_POST['id_fase']);

    // Obtener id_fase actual del requerimiento
    $id_fase_actual = 1;
    $resFase = $conn->query("SELECT Id_fase FROM requerimiento WHERE Id_requerimiento = $id_requerimiento");
    if ($resFase && $rowFase = $resFase->fetch_assoc()) {
        $id_fase_actual = intval($rowFase['Id_fase']);
    }

    // Verificar si ya existe una asignación activa para este requerimiento
    $resAsignacion = $conn->query("SELECT id_asignacion FROM asignacion_requerimientos WHERE id_requerimiento = $id_requerimiento AND activo = 1");
    if ($resAsignacion && $resAsignacion->num_rows > 0) {
        $mensaje = "<div class='alert alert-danger'>Este requerimiento ya tiene una asignación activa. No se puede volver a asignar hasta que se desactive la anterior.</div>";
    } else {
        // Si hay cambio de fase, actualizar requerimiento y registrar en reporte
        $cambio_fase = ($id_fase_nueva !== $id_fase_actual);
        if ($cambio_fase) {
            $conn->query("UPDATE requerimiento SET Id_fase = $id_fase_nueva WHERE Id_requerimiento = $id_requerimiento");
            $cambio_realizado = "[Cambio de fase] " . $cambio_realizado;
        }

        // Insertar en reporte
        $sql = "INSERT INTO reporte (Id_requerimiento, Cambio_realizado, Fecha_cambio, Id_desarrollador)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $id_requerimiento, $cambio_realizado, $fecha_cambio, $id_desarrollador);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Cambio registrado y requerimiento asignado correctamente.</div>";
            // Actualizar Fecha_modificacion en requerimiento
            $conn->query("UPDATE requerimiento SET Fecha_modificacion = '$fecha_cambio' WHERE Id_requerimiento = $id_requerimiento");
            // cambiar estado del requerimiento a "En Proceso" (suponiendo que hay un campo 'Estado' o similar)
            $conn->query("UPDATE requerimiento SET Estado = 'En Proceso', Fecha_modificacion = '$fecha_cambio' WHERE Id_requerimiento = $id_requerimiento");
            // Desactivar cualquier asignación anterior (por seguridad, aunque no debería haber)
            $conn->query("UPDATE asignacion_requerimientos SET activo = 0 WHERE id_requerimiento = $id_requerimiento AND activo = 1");
            // Insertar nueva asignación activa
            $conn->query("INSERT INTO asignacion_requerimientos (id_requerimiento, id_desarrollador, id_fase, activo) VALUES ($id_requerimiento, $id_desarrollador, $id_fase_nueva, 1)");
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al registrar cambio: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}

// Desactivar asignación y crear reporte de terminado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['desactivar_asignacion'])) {
    $id_asignacion = intval($_POST['id_asignacion']);
    $id_requerimiento = intval($_POST['id_requerimiento']);
    $id_desarrollador_asig = intval($_POST['id_desarrollador_asig']);
    $fecha_cambio = date('Y-m-d');

    // Desactivar la asignación
    $conn->query("UPDATE asignacion_requerimientos SET activo = 0 WHERE id_asignacion = $id_asignacion");

    // Cambiar el estado del requerimiento a terminado (suponiendo que hay un campo 'Estado' o similar)
    $conn->query("UPDATE requerimiento SET Estado = 'Terminado', Fecha_modificacion = '$fecha_cambio' WHERE Id_requerimiento = $id_requerimiento");

    // Crear reporte de terminado
    $sql = "INSERT INTO reporte (Id_requerimiento, Cambio_realizado, Fecha_cambio, Id_desarrollador)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $terminado = "Terminado por el desarrollador";
    $stmt->bind_param("issi", $id_requerimiento, $terminado, $fecha_cambio, $id_desarrollador_asig);
    $stmt->execute();
    $stmt->close();

    $mensaje = "<div class='alert alert-success'>Asignación desactivada, requerimiento marcado como terminado y reporte generado.</div>";
}

// Obtener requerimientos disponibles para este desarrollador
$requerimientos = [];
if ($id_desarrollador) {
    $sql = "
        SELECT r.Id_requerimiento, r.Descripcion, r.Prioridad, r.Fecha_creacion, r.Id_fase, s.Nombre AS Sistema, c.Nombre_cliente
        FROM requerimiento r
        INNER JOIN sistema s ON r.Id_sistema = s.Id_sistema
        INNER JOIN cliente c ON s.Id_cliente = c.Id_cliente
        WHERE r.Fecha_modificacion IS NULL
        AND NOT EXISTS (
            SELECT 1 FROM asignacion_requerimientos ar
            WHERE ar.id_requerimiento = r.Id_requerimiento AND ar.activo = 1
        )
        ORDER BY r.Fecha_creacion ASC
    ";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        while($row = $res->fetch_assoc()) {
            $requerimientos[] = $row;
        }
    }
}

// Mostrar requerimientos asignados y permitir cambiar a "En Proceso"
$asignados = [];
$sql = "
    SELECT ar.id_asignacion, ar.id_requerimiento, ar.id_desarrollador, ar.id_fase, r.Descripcion, r.Prioridad, r.Fecha_creacion, d.Nombre AS NombreDesarrollador, f.Nombre AS NombreFase, r.Estado
    FROM asignacion_requerimientos ar
    INNER JOIN requerimiento r ON ar.id_requerimiento = r.Id_requerimiento
    INNER JOIN desarrollador d ON ar.id_desarrollador = d.Id_Desarrollador
    INNER JOIN fase f ON ar.id_fase = f.Id_fase
    WHERE ar.activo = 1 and r.estado = 'En Proceso' 
    AND ar.id_desarrollador = $id_desarrollador
";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $asignados[] = $row;
    }
}


// Obtener nombre del desarrollador
$nombre_desarrollador = "";
if ($id_desarrollador) {
    $resDev = $conn->query("SELECT Nombre FROM desarrollador WHERE Id_Desarrollador = $id_desarrollador");
    if ($resDev && $row = $resDev->fetch_assoc()) {
        $nombre_desarrollador = $row['Nombre'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Requerimientos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Asignar Requerimiento a Desarrollador</h2>
    <?php if ($nombre_desarrollador): ?>
        <div class="alert alert-info">Desarrollador: <strong><?php echo htmlspecialchars($nombre_desarrollador); ?></strong></div>
    <?php endif; ?>
    <?php echo $mensaje; ?>

    <div class="row">
        <!-- Columna 1: Formulario de asignación -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Asignar Requerimiento</h5>
                </div>
                <div class="card-body">
                    <?php if ($id_desarrollador && count($requerimientos) > 0): ?>
                        <form method="post">
                            <input type="hidden" name="registrar_reporte" value="1">
                            <div class="mb-3">
                                <label for="id_requerimiento" class="form-label">Requerimiento</label>
                                <select class="form-select" id="id_requerimiento" name="id_requerimiento" required>
                                    <option value="">Seleccione un requerimiento</option>
                                    <?php foreach($requerimientos as $req): ?>
                                        <option value="<?php echo $req['Id_requerimiento']; ?>">
                                            <?php echo htmlspecialchars($req['Descripcion']) . " | Sistema: " . htmlspecialchars($req['Sistema']) . " | Cliente: " . htmlspecialchars($req['Nombre_cliente']); ?>
                                        </option>
                                    <?php endforeach; ?>
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
                                <label for="cambio_realizado" class="form-label">Descripción del cambio realizado</label>
                                <textarea class="form-control" id="cambio_realizado" name="cambio_realizado" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar Cambio</button>
                            <button type="button" class="btn btn-secondary" onclick="history.back();">Volver</button>
                        </form>
                    <?php elseif ($id_desarrollador): ?>
                        <div class="alert alert-warning">No hay requerimientos disponibles para asignar.</div>
                    <?php else: ?>
                        <div class="alert alert-danger">No se ha seleccionado un desarrollador.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Columna 2: Requerimientos asignados -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Requerimientos Asignados</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Req.</th>
                                    <th>Descripción</th>
                                    <th>Prioridad</th>
                                    <th>Fecha</th>
                                    <th>Fase</th>
                                    <th>Desarrollador</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($asignados) > 0): ?>
                                    <?php foreach($asignados as $asig): ?>
                                        <tr>
                                            <td><?php echo $asig['id_requerimiento']; ?></td>
                                            <td><?php echo htmlspecialchars($asig['Descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($asig['Prioridad']); ?></td>
                                            <td><?php echo htmlspecialchars($asig['Fecha_creacion']); ?></td>
                                            <td><?php echo htmlspecialchars($asig['NombreFase']); ?></td>
                                            <td><?php echo htmlspecialchars($asig['NombreDesarrollador']); ?></td>
                                            <td>
                                                <form method="post" style="display:inline;" onsubmit="return confirm('¿Desactivar esta asignación y marcar como terminado?');">
                                                    <input type="hidden" name="desactivar_asignacion" value="1">
                                                    <input type="hidden" name="id_asignacion" value="<?php echo $asig['id_asignacion']; ?>">
                                                    <input type="hidden" name="id_requerimiento" value="<?php echo $asig['id_requerimiento']; ?>">
                                                    <input type="hidden" name="id_desarrollador_asig" value="<?php echo $asig['id_desarrollador']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Desactivar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7">No hay requerimientos asignados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>