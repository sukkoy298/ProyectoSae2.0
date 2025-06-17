<?php
require_once 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestión de Proyectos</title>
    <style>

    </style>
</head>
<body>
    <div class="tabla-container">
        <h2>Clientes</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Correo</th>
                <th>Ciudad</th>
                <th>Empresa</th>
                <th>Teléfono</th>
                <th>Fecha Registro</th>
            </tr>
            <?php
            $sql = "SELECT Id_cliente, Correo_cliente, Ciudad_cliente, Empresa_cliente, Telefono_cliente, Fecha_registro FROM cliente";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Id_cliente']}</td>
                        <td>{$row['Correo_cliente']}</td>
                        <td>{$row['Ciudad_cliente']}</td>
                        <td>{$row['Empresa_cliente']}</td>
                        <td>{$row['Telefono_cliente']}</td>
                        <td>{$row['Fecha_registro']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <div class="tabla-container">
        <h2>Desarrolladores</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Especialidad</th>
                <th>Experiencia</th>
                <th>Fecha Incorporación</th>
            </tr>
            <?php
            $sql = "SELECT Id_Desarrollador, Nombre, Correo, Especialidad, Experiencia, Fecha_incorporacion FROM desarrollador";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Id_Desarrollador']}</td>
                        <td>{$row['Nombre']}</td>
                        <td>{$row['Correo']}</td>
                        <td>{$row['Especialidad']}</td>
                        <td>{$row['Experiencia']}</td>
                        <td>{$row['Fecha_incorporacion']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <div class="tabla-container">
        <h2>Requerimientos</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>ID Sistema</th>
                <th>Descripción</th>
                <th>Prioridad</th>
                <th>Fecha Creación</th>
                <th>Estado</th>
            </tr>
            <?php
            $sql = "SELECT Id_requerimiento, Id_sistema, Descripcion, Prioridad, Fecha_creacion, Estado FROM requerimiento";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Id_requerimiento']}</td>
                        <td>{$row['Id_sistema']}</td>
                        <td>{$row['Descripcion']}</td>
                        <td>{$row['Prioridad']}</td>
                        <td>{$row['Fecha_creacion']}</td>
                        <td>{$row['Estado']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>