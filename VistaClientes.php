<?php
session_start();
require_once 'controladores/conexion.php';

/* Verifica que el cliente esté autenticado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit;
}
*/
$mensaje = "";

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

// Procesar el formulario para agregar sistema/proyecto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_sistema'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $id_cliente = $_SESSION['cliente_id'];

    if ($nombre !== "") {
        $sql = "INSERT INTO sistema (Nombre, Descripcion, Id_cliente) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id_cliente);
        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Proyecto registrado correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al registrar proyecto: " . $conn->error . "</div>";
        }
        $stmt->close();
    } else {
        $mensaje = "<div class='alert alert-warning'>El nombre del proyecto es obligatorio.</div>";
    }
}

// Consultar sistemas del cliente
$id_cliente = $_SESSION['cliente_id'];
$sql_sistemas = "SELECT s.Id_sistema, s.Nombre, ts.nombre AS Tipo_sistema, s.Version_sistema, s.Fecha_inicio
                 FROM sistema s
                 INNER JOIN tipo_sistema ts ON s.id_tipo_sistema = ts.id
                 WHERE s.Id_cliente = ?";
$stmt_sistemas = $conn->prepare($sql_sistemas);
$stmt_sistemas->bind_param("i", $id_cliente);
$stmt_sistemas->execute();
$result_sistemas = $stmt_sistemas->get_result();

// Consultar requerimientos de los sistemas del cliente
$sql_reqs = "SELECT r.Id_requerimiento, r.Descripcion, r.Prioridad, r.Fecha_creacion, r.Estado, s.Nombre AS Sistema
             FROM requerimiento r
             INNER JOIN sistema s ON r.Id_sistema = s.Id_sistema
             WHERE s.Id_cliente = ?";
$stmt_reqs = $conn->prepare($sql_reqs);
$stmt_reqs->bind_param("i", $id_cliente);
$stmt_reqs->execute();
$result_reqs = $stmt_reqs->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-4">
    <h2>Bienvenido <?php echo htmlspecialchars($_SESSION['cliente_nombre'] ?? ''); ?></h2>
    <?php echo $mensaje; ?>

    <div class="row mt-4">
        <!-- Panel de Sistemas -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Mis Sistemas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Versión</th>
                                    <th>Fecha Inicio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_sistemas->num_rows > 0) {
                                    while ($row = $result_sistemas->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['Id_sistema']}</td>
                                            <td>{$row['Nombre']}</td>
                                            <td>{$row['Tipo_sistema']}</td>
                                            <td>{$row['Version_sistema']}</td>
                                            <td>{$row['Fecha_inicio']}</td>
                                            <td>
                                                <a href='agregarRequerimiento.php?id_sistema={$row['Id_sistema']}' class='btn btn-sm btn-warning'>Crear Requerimiento</a>
                                                <form method='post' action='' style='display:inline;' onsubmit=\"return confirm('¿Está seguro de eliminar este sistema?');\">
                                                    <input type='hidden' name='Eliminar_sistema' value='1'>
                                                    <input type='hidden' name='id_sistema' value='{$row['Id_sistema']}'>
                                                    <button type='submit' class='btn btn-sm btn-danger'>Eliminar sistema</button>
                                                </form>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>Sin sistemas registrados</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="agregarSistema.php?id=<?php echo $id_cliente; ?>" class="btn btn-primary m-2">Registrar Nuevo Sistema</a>
            </div>
        </div>
        <!-- Panel de Requerimientos -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Mis Requerimientos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Sistema</th>
                                    <th>Descripción</th>
                                    <th>Prioridad</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_reqs->num_rows > 0) {
                                    while ($row = $result_reqs->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['Id_requerimiento']}</td>
                                            <td>{$row['Sistema']}</td>
                                            <td>{$row['Descripcion']}</td>
                                            <td>{$row['Prioridad']}</td>
                                            <td>{$row['Fecha_creacion']}</td>
                                            <td>{$row['Estado']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>Sin requerimientos registrados</td></tr>";
                                }
                                ?>
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