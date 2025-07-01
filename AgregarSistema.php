<?php
require_once 'controladores/conexion.php';

// Obtener clientes para el select
$clientes = [];
$resClientes = $conn->query("SELECT Id_cliente, Nombre_cliente FROM cliente WHERE activo = 1");
if ($resClientes && $resClientes->num_rows > 0) {
    while($row = $resClientes->fetch_assoc()) {
        $clientes[] = $row;
    }
}

// Obtener tipos de sistemas desde la tabla tipo_sistema
$tipos_sistema = [];
$resTipos = $conn->query("SELECT id, nombre FROM tipo_sistema WHERE activo = 1");
if ($resTipos && $resTipos->num_rows > 0) {
    while($row = $resTipos->fetch_assoc()) {
        $tipos_sistema[] = $row;
    }
}

// Obtener id_cliente enviado por GET
$id_cliente_get = isset($_GET['id']) ? intval($_GET['id']) : null;

// Mensaje de resultado
$mensaje = "";

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registrar_sistema'])) {
    $id_cliente = intval($_POST['id_cliente']);
    $nombre = trim($_POST['nombre']);
    $tipo = intval($_POST['tipo']); // id_tipo
    $version = trim($_POST['version']);
    $fecha_inicio = $_POST['fecha_inicio'];

    $sql = "INSERT INTO sistema (Id_cliente, Nombre, Version_sistema, Fecha_inicio, id_tipo_sistema)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $id_cliente, $nombre, $version, $fecha_inicio, $tipo);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Sistema registrado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al registrar sistema: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Sistema</title>
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
            <select class="form-select" id="tipo" name="tipo" required>
                <option value="">Seleccione tipo</option>
                <?php foreach($tipos_sistema as $tipo): ?>
                    <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="version" class="form-label">Versión</label>
            <input type="text" class="form-control" id="version" name="version" value="1.0" required>
        </div>
        <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Sistema</button>
        <button type="button" class="btn btn-secondary" onclick="history.back();">Volver</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>