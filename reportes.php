<?php
require_once 'controladores/conexion.php';

$reporte = isset($_GET['reporte']) ? $_GET['reporte'] : '';
$resultados = [];
$titulo = '';
$thead = '';
$tbody = '';

if ($reporte == 'desarrolladores_requerimientos') {
    $titulo = 'Desarrolladores y sus Requerimientos Activos';
    $sql = "SELECT d.Nombre, COUNT(ar.id_requerimiento) AS total_requerimientos
            FROM desarrollador d
            LEFT JOIN asignacion_requerimientos ar ON d.Id_Desarrollador = ar.id_desarrollador AND ar.activo = 1
            GROUP BY d.Id_Desarrollador";
    $res = $conn->query($sql);
    $thead = '<tr><th>Desarrollador</th><th>Cantidad de Requerimientos</th></tr>';
    while($row = $res->fetch_assoc()) {
        $tbody .= "<tr><td>{$row['Nombre']}</td><td>{$row['total_requerimientos']}</td></tr>";
    }
} elseif ($reporte == 'sistemas_mas_requerimientos') {
    $titulo = 'Sistemas con Más Requerimientos';
    $sql = "SELECT s.Nombre, COUNT(r.Id_requerimiento) AS total_reqs
            FROM sistema s
            LEFT JOIN requerimiento r ON s.Id_sistema = r.Id_sistema
            GROUP BY s.Id_sistema
            ORDER BY total_reqs DESC";
    $res = $conn->query($sql);
    $thead = '<tr><th>Sistema</th><th>Cantidad de Requerimientos</th></tr>';
    while($row = $res->fetch_assoc()) {
        $tbody .= "<tr><td>{$row['Nombre']}</td><td>{$row['total_reqs']}</td></tr>";
    }
} elseif ($reporte == 'tipos_mas_sistemas') {
    $titulo = 'Tipos de Sistema con Más Sistemas';
    $sql = "SELECT ts.nombre, COUNT(s.Id_sistema) AS total_sistemas
            FROM tipo_sistema ts
            LEFT JOIN sistema s ON ts.id = s.id_tipo_sistema
            GROUP BY ts.id
            ORDER BY total_sistemas DESC";
    $res = $conn->query($sql);
    $thead = '<tr><th>Tipo de Sistema</th><th>Cantidad de Sistemas</th></tr>';
    while($row = $res->fetch_assoc()) {
        $tbody .= "<tr><td>{$row['nombre']}</td><td>{$row['total_sistemas']}</td></tr>";
    }
} elseif ($reporte == 'clientes_mas_sistemas') {
    $titulo = 'Clientes con Más Sistemas';
    $sql = "SELECT c.Nombre_cliente, COUNT(s.Id_sistema) AS total_sistemas
            FROM cliente c
            LEFT JOIN sistema s ON c.Id_cliente = s.Id_cliente
            GROUP BY c.Id_cliente
            ORDER BY total_sistemas DESC";
    $res = $conn->query($sql);
    $thead = '<tr><th>Cliente</th><th>Cantidad de Sistemas</th></tr>';
    while($row = $res->fetch_assoc()) {
        $tbody .= "<tr><td>{$row['Nombre_cliente']}</td><td>{$row['total_sistemas']}</td></tr>";
    }
} elseif ($reporte == 'requerimientos_fase') {
    $titulo = 'Cantidad de Requerimientos por Fase';
    $sql = "SELECT f.Nombre AS Fase, COUNT(r.Id_requerimiento) AS total_reqs
            FROM fase f
            LEFT JOIN requerimiento r ON f.Id_fase = r.Id_fase
            GROUP BY f.Id_fase";
    $res = $conn->query($sql);
    $thead = '<tr><th>Fase</th><th>Cantidad de Requerimientos</th></tr>';
    while($row = $res->fetch_assoc()) {
        $tbody .= "<tr><td>{$row['Fase']}</td><td>{$row['total_reqs']}</td></tr>";
    }
} elseif ($reporte == 'tiempo_asignacion') {
    $titulo = 'Tiempo Promedio de Asignación de Requerimientos (días)';
    $sql = "SELECT r.Id_requerimiento, r.Fecha_creacion, MIN(ar.fecha_asignacion) AS fecha_asignacion,
                DATEDIFF(MIN(ar.fecha_asignacion), r.Fecha_creacion) AS dias
            FROM requerimiento r
            INNER JOIN asignacion_requerimientos ar ON r.Id_requerimiento = ar.id_requerimiento
            GROUP BY r.Id_requerimiento";
    $res = $conn->query($sql);
    $thead = '<tr><th>ID Requerimiento</th><th>Fecha Creación</th><th>Fecha Asignación</th><th>Días</th></tr>';
    $total = 0; $count = 0;
    while($row = $res->fetch_assoc()) {
        $tbody .= "<tr><td>{$row['Id_requerimiento']}</td><td>{$row['Fecha_creacion']}</td><td>{$row['fecha_asignacion']}</td><td>{$row['dias']}</td></tr>";
        if (is_numeric($row['dias'])) {
            $total += $row['dias'];
            $count++;
        }
    }
    if ($count > 0) {
        $promedio = round($total / $count, 2);
        $tbody .= "<tr class='table-info'><td colspan='3'><b>Promedio</b></td><td><b>{$promedio}</b></td></tr>";
    }
} elseif ($reporte == 'clientes_cantidad_reqs') {
    $titulo = 'Clientes y Cantidad de Requerimientos';
    $sql = "SELECT c.Nombre_cliente, COUNT(r.Id_requerimiento) AS total_reqs
            FROM cliente c
            LEFT JOIN sistema s ON c.Id_cliente = s.Id_cliente
            LEFT JOIN requerimiento r ON s.Id_sistema = r.Id_sistema
            GROUP BY c.Id_cliente";
    $res = $conn->query($sql);
    $thead = '<tr><th>Cliente</th><th>Cantidad de Requerimientos</th></tr>';
    while($row = $res->fetch_assoc()) {
        $tbody .= "<tr><td>{$row['Nombre_cliente']}</td><td>{$row['total_reqs']}</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<header>
        <nav class="navbar navbar-expand-lg ">
            <div class="container-fluid">   
                <h3>Panel de contol</h3>
                <a href="panelControl.php">Panel de control</a>
                    <a href="ClientesPC.php">Clientes</a>
                    <a href="Desarrolladores.php">Desarrolladores</a>
                    <a href="Requerimientos.php">Requerimientos</a>
                    
                    <a href="reportes.php" id="nav-Repor" style="color: white; background-color:#006400; border-radius: 50px; padding: 6px 12px; text-decoration: none;">Reportes</a>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="controladores/cerrarSesion.php">Salir</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
<body>
<div class="container mt-5">
    <h2>Reportes</h2>
    <form method="get" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-6">
                <label for="reporte" class="form-label">Selecciona un tipo de reporte:</label>
                <select class="form-select" id="reporte" name="reporte" required>
                    <option value="">Seleccione...</option>
                    <option value="desarrolladores_requerimientos" <?php if($reporte=='desarrolladores_requerimientos') echo 'selected'; ?>>Desarrolladores y cantidad de requerimientos activos</option>
                    <option value="sistemas_mas_requerimientos" <?php if($reporte=='sistemas_mas_requerimientos') echo 'selected'; ?>>Sistemas con más requerimientos</option>
                    <option value="tipos_mas_sistemas" <?php if($reporte=='tipos_mas_sistemas') echo 'selected'; ?>>Tipos de sistemas con más sistemas</option>
                    <option value="clientes_mas_sistemas" <?php if($reporte=='clientes_mas_sistemas') echo 'selected'; ?>>Clientes con más sistemas</option>
                    <option value="requerimientos_fase" <?php if($reporte=='requerimientos_fase') echo 'selected'; ?>>Requerimientos por fase</option>
                    <option value="tiempo_asignacion" <?php if($reporte=='tiempo_asignacion') echo 'selected'; ?>>Tiempo promedio de asignación de requerimientos</option>
                    <option value="clientes_cantidad_reqs" <?php if($reporte=='clientes_cantidad_reqs') echo 'selected'; ?>>Clientes y cantidad de requerimientos</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Ver reporte</button>
            </div>
        </div>
    </form>

    <?php if($reporte && $thead): ?>
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><?php echo $titulo; ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead><?php echo $thead; ?></thead>
                        <tbody><?php echo $tbody; ?></tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php elseif($reporte): ?>
        <div class="alert alert-warning mt-4">No hay datos para mostrar.</div>
    <?php endif; ?>
</div>