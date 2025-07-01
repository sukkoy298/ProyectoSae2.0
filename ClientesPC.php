<?php
require_once 'controladores/conexion.php';

$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Eliminar_cliente'])) {
    $id_cliente = intval($_POST['id_cliente']);
    $sql = "UPDATE cliente SET Activo = 0 WHERE Id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    if ($stmt->execute()) {
        $mensaje = "<div class='alert alert-success'>Cliente eliminado correctamente.</div>";
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar cliente: " . $conn->error . "</div>";
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
    <title>Clientes</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">   
                <h3>Clientes</h3>
                    <a href="panelControl.php" >Panel de control</a>
                    <a href="ClientesPC.php" id="nav-Clientes">Clientes</a>
                    <a href="Desarrolladores.php">Desarrolladores</a>
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
                                    $sql = "SELECT Id_cliente, Nombre_cliente, Correo_cliente, Ciudad_cliente, Empresa_cliente, Telefono_cliente, Fecha_registro FROM cliente WHERE Activo = 1";
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
                                                    <form method='post' action='' style='display:inline;' onsubmit=\"return confirm('¿Está seguro de eliminar este cliente?');\">
                                                        <input type='hidden' name='Eliminar_cliente' value='1'>
                                                        <input type='hidden' name='id_cliente' value='{$row['Id_cliente']}'>
                                                        <button type='submit' class='btn btn-sm btn-danger'>Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>Sin registros</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>