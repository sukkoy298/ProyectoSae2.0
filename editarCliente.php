<?php
require_once 'controladores/conexion.php';

$mensaje = "";
$id_cliente = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener datos actuales del cliente
$cliente = null;
if ($id_cliente > 0) {
    $sql = "SELECT * FROM cliente WHERE Id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $stmt->close();
}

if (!$cliente) {
    echo "<div class='alert alert-danger'>Cliente no encontrado.</div>";
    exit;
}

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $ciudad = $_POST['ciudad'];
    $empresa = $_POST['empresa'];
    $telefono = $_POST['telefono'];
    $nueva_contraseña = $_POST['contraseña'];

    if (!empty($nueva_contraseña)) {
        $contraseña_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        $sql = "UPDATE cliente SET Nombre_cliente=?, Correo_cliente=?, Contraseña_cliente=?, Ciudad_cliente=?, Empresa_cliente=?, Telefono_cliente=? WHERE Id_cliente=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nombre, $correo, $contraseña_hash, $ciudad, $empresa, $telefono, $id_cliente);
    } else {
        $sql = "UPDATE cliente SET Nombre_cliente=?, Correo_cliente=?, Ciudad_cliente=?, Empresa_cliente=?, Telefono_cliente=? WHERE Id_cliente=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $correo, $ciudad, $empresa, $telefono, $id_cliente);
    }

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Cliente actualizado correctamente.</div>";
        // Recargar datos actualizados
        $sql = "SELECT * FROM cliente WHERE Id_cliente = ?";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("i", $id_cliente);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $cliente = $result->fetch_assoc();
        $stmt2->close();
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar cliente: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Cliente</h2>
    <?php echo $mensaje; ?>
    <form method="post" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($cliente['Nombre_cliente']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($cliente['Correo_cliente']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña">
        </div>
        <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?php echo htmlspecialchars($cliente['Ciudad_cliente']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="empresa" class="form-label">Empresa</label>
            <input type="text" class="form-control" id="empresa" name="empresa" value="<?php echo htmlspecialchars($cliente['Empresa_cliente']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cliente['Telefono_cliente']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="panelControl.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>