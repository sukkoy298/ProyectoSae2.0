<?php
require_once 'controladores/conexion.php';

// Registrar sistema
$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar_sistema'])) {
    $id_cliente = intval($_POST['id_cliente']);
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $version = $_POST['version'];
    $estado = $_POST['estado'];
    $fecha_inicio = $_POST['fecha_inicio'];

    $sql = "INSERT INTO sistema (Id_cliente, Nombre, Tipo_sistema, Version_sistema, Estado_sistema, Fecha_inicio)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $id_cliente, $nombre, $tipo, $version, $estado, $fecha_inicio);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Sistema registrado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al registrar sistema: " . $conn->error . "</div>";
    }
    $stmt->close();
}
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

// Obtener sistemas existentes según el id_cliente por GET (si existe)
$sistemas = [];
if ($id_cliente_get) {
    $stmt = $conn->prepare("SELECT s.*, c.Nombre_cliente FROM sistema s JOIN cliente c ON s.Id_cliente = c.Id_cliente WHERE s.Id_cliente = ?");
    $stmt->bind_param("i", $id_cliente_get);
    $stmt->execute();
    $resSistemas = $stmt->get_result();
} else {
    $resSistemas = $conn->query("SELECT s.*, c.Nombre_cliente FROM sistema s JOIN cliente c ON s.Id_cliente = c.Id_cliente");
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
</head>
<body>
<div class="container mt-5">
    <h2>Registrar Sistema</h2>
    <?php echo $mensaje; ?>
    <form method="post" class="mb-5">
        <input type="hidden" name="registrar_sistema" value="1">
        <div class="mb-3">
            <label for="id_cliente" class="form-label">Cliente</label>
            <?php if ($id_cliente_get): ?>
                <?php
                // Buscar el nombre del cliente seleccionado
                $nombre_cliente = '';
                foreach($clientes as $cliente) {
                    if ($cliente['Id_cliente'] == $id_cliente_get) {
                        $nombre_cliente = $cliente['Nombre_cliente'];
                        break;
                    }
                }
                ?>
                <input type="hidden" name="id_cliente" value="<?php echo $id_cliente_get; ?>">
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($nombre_cliente); ?>" disabled>
            <?php else: ?>
                <select class="form-select" id="id_cliente" name="id_cliente" required>
                    <option value="">Seleccione un cliente</option>
                    <?php foreach($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['Id_cliente']; ?>"><?php echo htmlspecialchars($cliente['Nombre_cliente']); ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del sistema</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo de sistema</label>
            <input type="text" class="form-control" id="tipo" name="tipo" required>
        </div>
        <div class="mb-3">
            <label for="version" class="form-label">Versión</label>
            <input type="text" class="form-control" id="version" name="version" value="1.0" required>
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado" required>
                <option value="Pendiente">Pendiente</option>
                <option value="En desarrollo">En desarrollo</option>
                <option value="Finalizado">Finalizado</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Sistema</button>
        <a href="panelControl.php" class="btn btn-secondary">Volver</a>
    </form>

    <h2>Sistemas Existentes</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Versión</th>
                    <th>Estado</th>
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
                            <td><?php echo htmlspecialchars($sistema['Tipo_sistema']); ?></td>
                            <td><?php echo htmlspecialchars($sistema['Version_sistema']); ?></td>
                            <td><?php echo htmlspecialchars($sistema['Estado_sistema']); ?></td>
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
                    <tr><td colspan="8">No hay sistemas registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>