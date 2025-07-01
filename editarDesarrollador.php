<?php
require_once 'controladores/conexion.php';

$mensaje = "";
$id_desarrollador = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener datos actuales del desarrollador
$desarrollador = null;
if ($id_desarrollador > 0) {
    $sql = "SELECT * FROM desarrollador WHERE Id_Desarrollador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_desarrollador);
    $stmt->execute();
    $result = $stmt->get_result();
    $desarrollador = $result->fetch_assoc();
    $stmt->close();
}

if (!$desarrollador) {
    echo "<div class='alert alert-danger'>Desarrollador no encontrado.</div>";
    exit;
}


// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $especialidad = $_POST['especialidad'];
    $experiencia = intval($_POST['experiencia']);
    $fecha_incorporacion = $_POST['fecha_incorporacion'];
    $nueva_contraseña = $_POST['contraseña'];

    if (!empty($nueva_contraseña)) {
        $contraseña_hash = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
        $sql = "UPDATE desarrollador SET Nombre=?, Correo=?, Contraseña=?, Especialidad=?, Experiencia=?, Fecha_incorporacion=? WHERE Id_Desarrollador=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssisi", $nombre, $correo, $contraseña_hash, $especialidad, $experiencia, $fecha_incorporacion, $id_desarrollador);
    } else {
        $sql = "UPDATE desarrollador SET Nombre=?, Correo=?, Especialidad=?, Experiencia=?, Fecha_incorporacion=? WHERE Id_Desarrollador=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisi", $nombre, $correo, $especialidad, $experiencia, $fecha_incorporacion, $id_desarrollador);
    }

    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Desarrollador actualizado correctamente.</div>";
        // Recargar datos actualizados
        $sql = "SELECT * FROM desarrollador WHERE Id_Desarrollador = ?";
        $stmt2 = $conn->prepare($sql);
        $stmt2->bind_param("i", $id_desarrollador);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $desarrollador = $result->fetch_assoc();
        $stmt2->close();
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar desarrollador: " . $conn->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Desarrollador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style= "background-color:#F8F8FF">
<div class="container mt-5">
    <h2>Editar Desarrollador</h2>
    <?php echo $mensaje; ?>
    <form method="post" action="">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($desarrollador['Nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($desarrollador['Correo']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="contraseña" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" class="form-control" id="contraseña" name="contraseña">
        </div>
        <div class="mb-3">
            <label for="especialidad" class="form-label">Especialidad</label>
            <input type="text" class="form-control" id="especialidad" name="especialidad" value="<?php echo htmlspecialchars($desarrollador['Especialidad']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="experiencia" class="form-label">Años de experiencia</label>
            <input type="number" class="form-control" id="experiencia" name="experiencia" min="1" value="<?php echo htmlspecialchars($desarrollador['Experiencia']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha_incorporacion" class="form-label">Fecha de incorporación</label>
            <input type="date" class="form-control" id="fecha_incorporacion" name="fecha_incorporacion" value="<?php echo htmlspecialchars($desarrollador['Fecha_incorporacion']); ?>" required>
        </div>
       
        <button type="submit" class="btn btn-primary">Actualizar</button>
       <button type="button" class="btn btn-secondary" onclick="history.back();">Volver</button>
    </form>
</div>
</body>
</html>