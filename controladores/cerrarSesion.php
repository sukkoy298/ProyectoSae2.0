<?php
session_start();
session_unset();
session_destroy();
header("Location: ../inicioSesion.php?message=Sesión cerrada exitosamente");
exit();
?>