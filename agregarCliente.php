<?php
require_once 'controladores/conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $ciudad = $_POST['ciudad'];
    $empresa = $_POST['empresa'];
    $telefono = $_POST['telefono'];
    $fecha_registro = date('Y-m-d H:i:s');
    $activo = 1; 

    $sql = "INSERT INTO cliente (Nombre_cliente, Correo_cliente, Contraseña_cliente, Ciudad_cliente, Empresa_cliente, Telefono_cliente, Fecha_registro, Activo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $nombre, $correo, $contraseña, $ciudad, $empresa, $telefono, $fecha_registro, $activo);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Cliente agregado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al agregar cliente: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Agregar Cliente</h2>
    <?php echo $mensaje; ?>
    <form method="post" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña" required>
        </div>
        <div class="mb-3">
            <label for="ciudad" class="form-label">Ciudad</label>
            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
        </div>
        <div class="mb-3">
            <label for="empresa" class="form-label">Empresa</label>
            <input type="text" class="form-control" id="empresa" name="empresa" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono">
        </div>
        <button type="submit" class="btn btn-primary">Agregar</button>
        <a href="panelControl.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>