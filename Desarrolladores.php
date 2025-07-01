<?php
require_once 'controladores/conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Eliminar_desarrollador'])) {
    $id_desarrollador = intval($_POST['id_desarrollador']);
    $sql = "UPDATE desarrollador SET Activo = 0 WHERE Id_desarrollador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_desarrollador);
    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Desarrollador eliminado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar desarrollador: " . $conn->error . "</div>";
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
    <title>Desarrolladores</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">   
                <h3>Desarrolladores</h3>
                    <a href="panelControl.php" >Panel de control</a>
                    <a href="ClientesPC.php">Clientes</a>
                    <a href="Desarrolladores.php" id="nav-Desa">Desarrolladores</a>
                    <a href="Requerimientos.php">Requerimientos</a>
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
        <!-- Desarrolladores -->
        <div class="row mb-4">
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Desarrolladores</h5>
                        <a href="agregarDesarrollador.php" class="btn btn-success mb-3">Agregar Desarrollador</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Especialidad</th>
                                        <th>Experiencia</th>
                                        <th>Fecha Incorporación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT Id_Desarrollador, Nombre, Correo, Especialidad, Experiencia, Fecha_incorporacion FROM desarrollador WHERE Activo = 1";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$row['Id_Desarrollador']}</td>
                                                <td>{$row['Nombre']}</td>
                                                <td>{$row['Correo']}</td>
                                                <td>{$row['Especialidad']}</td>
                                                <td>{$row['Experiencia']}</td>
                                                <td>{$row['Fecha_incorporacion']}</td>
                                                <td>
                                                    <a href='panelAsignar.php?id_desarrollador={$row['Id_Desarrollador']}' class='btn btn-sm btn-info'>Asignar</a>
                                                    <a href='editarDesarrollador.php?id={$row['Id_Desarrollador']}' class='btn btn-sm btn-warning'>Editar</a>
                                                    <form method='post' action='' style='display:inline;' onsubmit=\"return confirm('¿Está seguro de eliminar este desarrollador?');\">
                                                        <input type='hidden' name='Eliminar_desarrollador' value='1'>
                                                        <input type='hidden' name='id_desarrollador' value='{$row['Id_Desarrollador']}'>
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