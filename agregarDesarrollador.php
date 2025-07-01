<?php
require_once 'controladores/conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $especialidad = $_POST['especialidad'];
    $experiencia = intval($_POST['experiencia']);
    $fecha_incorporacion = $_POST['fecha_incorporacion'];
    $activo = 1; // Siempre activo al crear

    $sql = "INSERT INTO desarrollador (Nombre, Correo, Contraseña, Especialidad, Experiencia, Fecha_incorporacion, activo)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $nombre, $correo, $contraseña, $especialidad, $experiencia, $fecha_incorporacion, $activo);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Desarrollador agregado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al agregar desarrollador: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Desarrollador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#F8F8FF">
<div class="container mt-5">
    <h2>Agregar Desarrollador</h2>
    <?php echo $mensaje; ?>
    <form method="post" action="">
        <div class="row">
            <div class="col-md-6">
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
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="especialidad" class="form-label">Especialidad</label>
                    <input type="text" class="form-control" id="especialidad" name="especialidad" required>
                </div>
                <div class="mb-3">
                    <label for="experiencia" class="form-label">Años de experiencia</label>
                    <input type="number" class="form-control" id="experiencia" name="experiencia" min="1" value="1" required>
                </div>
                <div class="mb-3">
                    <label for="fecha_incorporacion" class="form-label">Fecha de incorporación</label>
                    <input type="date" class="form-control" id="fecha_incorporacion" name="fecha_incorporacion" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">Agregar</button>
            <a href="desarrolladores.php" class="btn btn-secondary">Volver</a>
        </div>
    </form>
</div>
</body>
</html>