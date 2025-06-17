<?php
require_once 'controladores/conexion.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Panel de control</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Panel de Control</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active bg-danger" aria-current="page" href="controladores/cerrarSesion.php">Salir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container mt-5">
        <!-- Clientes -->
        <div class="row mb-4">
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Clientes</h5>
                        <a href="agregarCliente.php" class="btn btn-primary mb-3">Agregar Cliente</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Ciudad</th>
                                        <th>Empresa</th>
                                        <th>Teléfono</th>
                                        <th>Fecha Registro</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT Id_cliente, Nombre_cliente, Correo_cliente, Ciudad_cliente, Empresa_cliente, Telefono_cliente, Fecha_registro FROM cliente";
                                    $result = $conn->query($sql);
                                    if ($result && $result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>{$row['Id_cliente']}</td>
                                                <td>{$row['Nombre_cliente']}</td>
                                                <td>{$row['Correo_cliente']}</td>
                                                <td>{$row['Ciudad_cliente']}</td>
                                                <td>{$row['Empresa_cliente']}</td>
                                                <td>{$row['Telefono_cliente']}</td>
                                                <td>{$row['Fecha_registro']}</td>
                                                <td>
                                                    <a href='panelSistemas.php?id={$row['Id_cliente']}' class='btn btn-sm btn-info'>Ver Sistemas</a>
                                                    <a href='editarCliente.php?id={$row['Id_cliente']}' class='btn btn-sm btn-warning'>Editar</a>
                                                    <a href='eliminarCliente.php?id={$row['Id_cliente']}' class='btn btn-sm btn-danger' onclick=\"return confirm('¿Seguro que deseas eliminar este cliente?');\">Eliminar</a>
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
                                    $sql = "SELECT Id_Desarrollador, Nombre, Correo, Especialidad, Experiencia, Fecha_incorporacion FROM desarrollador";
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
                                                    <a href='editarDesarrollador.php?id={$row['Id_Desarrollador']}' class='btn btn-sm btn-warning'>Editar</a>
                                                    <a href='eliminarDesarrollador.php?id={$row['Id_Desarrollador']}' class='btn btn-sm btn-danger' onclick=\"return confirm('¿Seguro que deseas eliminar este desarrollador?');\">Eliminar</a>
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
        <!-- Requerimientos -->
        <div class="row mb-4">
            <div class="col">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Requerimientos</h5>
                        <a href="#" class="btn btn-warning mb-3">Agregar Requerimiento</a>
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
                                    $sql = "SELECT Id_requerimiento, Id_sistema, Descripcion, Prioridad, Fecha_creacion, Estado FROM requerimiento";
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
                                                    <a href='eliminarRequerimiento.php?id={$row['Id_requerimiento']}' class='btn btn-sm btn-danger' onclick=\"return confirm('¿Seguro que deseas eliminar este requerimiento?');\">Eliminar</a>
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