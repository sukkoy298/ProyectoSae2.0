<?php
session_start();
require_once 'conexion.php';

$usuario = $_POST['Usuario'] ?? '';
$clave = $_POST['Clave'] ?? '';

// ADMINISTRADOR
if ($usuario === 'admin' && $clave === 'admin') {
    $_SESSION['admin'] = true;
    header('Location: ../panelControl.php');
    exit;
}

// CLIENTE
$sql_cliente = "SELECT * FROM cliente WHERE Correo_cliente = ? AND activo = 1";
$stmt_cliente = $conn->prepare($sql_cliente);
$stmt_cliente->bind_param("s", $usuario);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();

if ($row = $result_cliente->fetch_assoc()) {
    if (password_verify($clave, $row['Contraseña_cliente'])) {
        $_SESSION['cliente_id'] = $row['Id_cliente'];
        $_SESSION['cliente_nombre'] = $row['Nombre_cliente'];
        header("Location: ../VistaClientes.php");
        exit;
    }
}
$stmt_cliente->close();

// DESARROLLADOR
$sql_dev = "SELECT * FROM desarrollador WHERE Correo = ? AND activo = 1";
$stmt_dev = $conn->prepare($sql_dev);
$stmt_dev->bind_param("s", $usuario);
$stmt_dev->execute();
$result_dev = $stmt_dev->get_result();

if ($row = $result_dev->fetch_assoc()) {
    if (password_verify($clave, $row['Contraseña'])) {
        $_SESSION['desarrollador_id'] = $row['Id_Desarrollador'];
        $_SESSION['desarrollador_nombre'] = $row['Nombre'];
        header("Location: ../VistaDesarrolladores.php");
        exit;
    }
}
$stmt_dev->close();

// Si no coincide ningún usuario
$mensaje = "Usuario o clave incorrectos.";
header("Location: ../inicioSesion.php?error=" . urlencode($mensaje));
exit;
?>