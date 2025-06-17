<?php
require_once 'controladores/conexion.php';

$mensaje = "";

// Obtener id del desarrollador por GET
$id_desarrollador = isset($_GET['id_desarrollador']) ? intval($_GET['id_desarrollador']) : null;

// Registrar reporte/cambio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar_reporte'])) {
    $id_requerimiento = intval($_POST['id_requerimiento']);
    $cambio_realizado = $_POST['cambio_realizado'];
    $fecha_cambio = date('Y-m-d');

    // Obtener id_fase actual del requerimiento
    $id_fase = 1;
    $resFase = $conn->query("SELECT Id_fase FROM requerimiento WHERE Id_requerimiento = $id_requerimiento");
    if ($resFase && $rowFase = $resFase->fetch_assoc()) {
        $id_fase = intval($rowFase['Id_fase']);
    }

    // Verificar si ya existe una asignación activa para este requerimiento
    $resAsignacion = $conn->query("SELECT id_asignacion FROM asignacion_requerimientos WHERE id_requerimiento = $id_requerimiento AND activo = 1");
    if ($resAsignacion && $resAsignacion->num_rows > 0) {
        $mensaje = "<div class='alert alert-danger'>Este requerimiento ya tiene una asignación activa. No se puede volver a asignar hasta que se desactive la anterior.</div>";
    } else {
        // Insertar en reporte
        $sql = "INSERT INTO reporte (Id_requerimiento, Cambio_realizado, Fecha_cambio, Id_desarrollador)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $id_requerimiento, $cambio_realizado, $fecha_cambio, $id_desarrollador);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Cambio registrado y requerimiento asignado correctamente.</div>";
            // Actualizar Fecha_modificacion en requerimiento
            $conn->query("UPDATE requerimiento SET Fecha_modificacion = '$fecha_cambio' WHERE Id_requerimiento = $id_requerimiento");
            // Desactivar cualquier asignación anterior (por seguridad, aunque no debería haber)
            $conn->query("UPDATE asignacion_requerimientos SET activo = 0 WHERE id_requerimiento = $id_requerimiento AND activo = 1");
            // Insertar nueva asignación activa
            $conn->query("INSERT INTO asignacion_requerimientos (id_requerimiento, id_desarrollador, id_fase, activo) VALUES ($id_requerimiento, $id_desarrollador, $id_fase, 1)");
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

    // Crear reporte de terminado
    $sql = "INSERT INTO reporte (Id_requerimiento, Cambio_realizado, Fecha_cambio, Id_desarrollador)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $terminado = "Terminado por el desarrollador";
    $stmt->bind_param("issi", $id_requerimiento, $terminado, $fecha_cambio, $id_desarrollador_asig);
    $stmt->execute();
    $stmt->close();

    $mensaje = "<div class='alert alert-success'>Asignación desactivada y reporte de terminado generado.</div>";
}

// Obtener requerimientos disponibles para este desarrollador
$requerimientos = [];
if ($id_desarrollador) {
    $sql = "
        SELECT r.Id_requerimiento, r.Descripcion, r.Prioridad, r.Fecha_creacion, s.Nombre AS Sistema, c.Nombre_cliente
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

// Mostrar requerimientos asignados
$asignados = [];
$sql = "
    SELECT ar.id_asignacion, ar.id_requerimiento, ar.id_desarrollador, r.Descripcion, r.Prioridad, r.Fecha_creacion, d.Nombre AS NombreDesarrollador
    FROM asignacion_requerimientos ar
    INNER JOIN requerimiento r ON ar.id_requerimiento = r.Id_requerimiento
    INNER JOIN desarrollador d ON ar.id_desarrollador = d.Id_Desarrollador
    WHERE ar.activo = 1
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
</head>
<body>
<div class="container mt-5">
    <h2>Asignar Requerimiento a Desarrollador</h2>
    <?php if ($nombre_desarrollador): ?>
        <div class="alert alert-info">Desarrollador: <strong><?php echo htmlspecialchars($nombre_desarrollador); ?></strong></div>
    <?php endif; ?>
    <?php echo $mensaje; ?>

    <?php if ($id_desarrollador && count($requerimientos) > 0): ?>
        <form method="post" class="mb-4">
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
                <label for="cambio_realizado" class="form-label">Descripción del cambio realizado</label>
                <textarea class="form-control" id="cambio_realizado" name="cambio_realizado" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Cambio</button>
        </form>
    <?php elseif ($id_desarrollador): ?>
        <div class="alert alert-warning">No hay requerimientos disponibles para asignar.</div>
    <?php else: ?>
        <div class="alert alert-danger">No se ha seleccionado un desarrollador.</div>
    <?php endif; ?>

    <h3 class="mt-5">Requerimientos Asignados</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID Requerimiento</th>
                    <th>Descripción</th>
                    <th>Prioridad</th>
                    <th>Fecha creación</th>
                    <th>Desarrollador asignado</th>
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
                    <tr><td colspan="6">No hay requerimientos asignados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="panelControl.php" class="btn btn-secondary">Volver</a>
</div>
</body>
</html>