<?php
require_once 'controladores/conexion.php';

// Eliminar sistema
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Eliminar_sistema'])) {
    $id_sistema = intval($_POST['id_sistema']);
    $sql = "DELETE FROM sistema WHERE Id_sistema = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_sistema);
    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Sistema eliminado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar sistema: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Si viene id_cliente por GET, selecciona ese cliente y deshabilita el select
$id_cliente_get = isset($_GET['id']) ? intval($_GET['id']) : null;

// Obtener clientes para el select
$clientes = [];
$resClientes = $conn->query("SELECT Id_cliente, Nombre_cliente FROM cliente");
if ($resClientes && $resClientes->num_rows > 0) {
    while($row = $resClientes->fetch_assoc()) {
        $clientes[] = $row;
    }
}

// Obtener tipos de sistema para la tabla aparte
$tipos_sistema = [];
$resTipos = $conn->query("SELECT id, nombre, descripcion, activo FROM tipo_sistema");
if ($resTipos && $resTipos->num_rows > 0) {
    while($row = $resTipos->fetch_assoc()) {
        $tipos_sistema[] = $row;
    }
}

// Obtener sistemas existentes según el id_cliente por GET (si existe)
$sistemas = [];
if ($id_cliente_get) {
    $stmt = $conn->prepare("SELECT s.*, c.Nombre_cliente, ts.nombre AS NombreTipoSistema 
        FROM sistema s 
        JOIN cliente c ON s.Id_cliente = c.Id_cliente 
        LEFT JOIN tipo_sistema ts ON s.id_tipo_sistema = ts.id
        WHERE s.Id_cliente = ?");
    $stmt->bind_param("i", $id_cliente_get);
    $stmt->execute();
    $resSistemas = $stmt->get_result();
} else {
    $resSistemas = $conn->query("SELECT s.*, c.Nombre_cliente, ts.nombre AS NombreTipoSistema 
        FROM sistema s 
        JOIN cliente c ON s.Id_cliente = c.Id_cliente 
        LEFT JOIN tipo_sistema ts ON s.id_tipo = ts.id");
}
if ($resSistemas && $resSistemas->num_rows > 0) {
    while($row = $resSistemas->fetch_assoc()) {
        $sistemas[] = $row;
    }
}
if (isset($stmt)) $stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Sistemas</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Sistemas Existentes</h2>
    <?php if (!empty($mensaje)) echo $mensaje; ?>
    <a href="agregarSistema.php?id=<?php echo $id_cliente_get; ?>" class="btn btn-primary mb-3">Registrar Nuevo Sistema</a>
    <div class="row">
        <!-- Tabla de sistemas -->
        <div class="col-md-8 mb-4">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Versión</th>
                            <th>Fecha inicio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($sistemas) > 0): ?>
                            <?php foreach($sistemas as $sistema): ?>
                                <tr>
                                    <td><?php echo $sistema['Id_sistema']; ?></td>
                                    <td><?php echo htmlspecialchars($sistema['Nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($sistema['Nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($sistema['NombreTipoSistema']); ?></td>
                                    <td><?php echo htmlspecialchars($sistema['Version_sistema']); ?></td>
                                    <td><?php echo htmlspecialchars($sistema['Fecha_inicio']); ?></td>
                                    <td>
                                        <a href="agregarRequerimiento.php?id_sistema=<?php echo $sistema['Id_sistema']; ?>" class="btn btn-sm btn-warning">Crear Requerimiento</a>
                                        <form method="post" action="" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este sistema?');">
                                            <input type="hidden" name="Eliminar_sistema" value="1">
                                            <input type="hidden" name="id_sistema" value="<?php echo $sistema['Id_sistema']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar sistema</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7">No hay sistemas registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>