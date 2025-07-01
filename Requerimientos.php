<?php
require_once 'controladores/conexion.php';

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Eliminar_requerimiento'])) {
    $id_requerimiento = intval($_POST['id_requerimiento']);
    $sql = "UPDATE requerimiento SET Estado = 'Eliminado' WHERE Id_requerimiento = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_requerimiento);
    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Requerimiento eliminado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar requerimiento: " . $conn->error . "</div>";
    }
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Requerimientos</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">   
                <h3>Requerimientos</h3>
                   <a href="panelControl.php" >Panel de control</a>
                    <a href="ClientesPC.php">Clientes</a>
                    <a href="Desarrolladores.php">Desarrolladores</a>
                    <a href="Requerimientos.php" id="nav-Reque">Requerimientos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="controladores/cerrarSesion.php">Salir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container mt-5">
        <?php if (!empty($mensaje)) echo $mensaje; ?>
        
        <!-- Requerimientos -->
        <div class="row mb-4">
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Requerimientos</h5>
                        <a href="agregarRequerimiento.php" class="btn btn-warning mb-3">Agregar Requerimiento</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>ID Sistema</th>
                                        <th>Descripción</th>
                                        <th>Prioridad</th>
                                        <th>Fecha Creación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT Id_requerimiento, Id_sistema, Descripcion, Prioridad, Fecha_creacion, Estado FROM requerimiento WHERE Estado != 'Eliminado'";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$row['Id_requerimiento']}</td>
                                                <td>{$row['Id_sistema']}</td>
                                                <td>{$row['Descripcion']}</td>
                                                <td>{$row['Prioridad']}</td>
                                                <td>{$row['Fecha_creacion']}</td>
                                                <td>{$row['Estado']}</td>
                                                <td>
                                                    <a href='editarRequerimiento.php?id={$row['Id_requerimiento']}' class='btn btn-sm btn-warning'>Editar</a>
                                                    <form method='post' action='' style='display:inline;' onsubmit=\"return confirm('¿Está seguro de eliminar este requerimiento?');\">
                                                        <input type='hidden' name='Eliminar_requerimiento' value='1'>
                                                        <input type='hidden' name='id_requerimiento' value='{$row['Id_requerimiento']}'>
                                                        <button type='submit' class='btn btn-sm btn-danger'>Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>Sin registros</td></tr>";
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