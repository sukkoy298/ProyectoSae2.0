<?php
require_once 'controladores/conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);
    $especialidad = $_POST['especialidad'];
    $experiencia = intval($_POST['experiencia']);
    $fecha_incorporacion = date('Y-m-d');
    $id_fase = !empty($_POST['id_fase']) ? intval($_POST['id_fase']) : null;

    $sql = "INSERT INTO desarrollador (Nombre, Correo, Contraseña, Especialidad, Experiencia, Fecha_incorporacion, Id_fase)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $nombre, $correo, $contraseña, $especialidad, $experiencia, $fecha_incorporacion, $id_fase);

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Desarrollador agregado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al agregar desarrollador: " . $conn->error . "</div>";
    }
    $stmt->close();
}

// Obtener fases para el select
$fases = [];
$faseSql = "SELECT Id_fase, Nombre FROM fase";
$faseResult = $conn->query($faseSql);
if ($faseResult && $faseResult->num_rows > 0) {
    while($row = $faseResult->fetch_assoc()) {
        $fases[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Desarrollador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Agregar Desarrollador</h2>
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
            <label for="especialidad" class="form-label">Especialidad</label>
            <input type="text" class="form-control" id="especialidad" name="especialidad" required>
        </div>
        <div class="mb-3">
            <label for="experiencia" class="form-label">Años de experiencia</label>
            <input type="number" class="form-control" id="experiencia" name="experiencia" min="1" value="1" required>
        </div>
        <button type="submit" class="btn btn-success">Agregar</button>
        <a href="panelControl.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>