<?php

session_start();

// Verifica que el desarrollador esté autenticado
if (!isset($_SESSION['desarrollador_id'])) {
    header("Location: inicioSesion.php");
    exit;
}

// Redirige al panel de asignación con el id del desarrollador
$id_desarrollador = $_SESSION['desarrollador_id'];
header("Location: panelAsignar.php?id_desarrollador=" . $id_desarrollador);
exit;
?>