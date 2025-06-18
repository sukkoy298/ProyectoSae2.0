<?php
session_start();
require_once('conexion.php');

if (isset($_POST["Usuario"]) && isset($_POST['Clave'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $Correo = validate($_POST['Usuario']);
    $Clave = validate($_POST['Clave']);

    if (empty($Correo)) {
        header("Location: ../inicioSesion.php?error=El correo es requerido");
        exit();
    } elseif (empty($Clave)) {
        header("Location: ../inicioSesion.php?error=La clave es requerida");
        exit();
    } else {
        // Buscar en la tabla desarrollador por correo
        $sql = "SELECT * FROM desarrollador WHERE Correo = ?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $Correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($Clave, $row['Contraseña'])) {
                $_SESSION['Correo'] = $row['Correo'];
                $_SESSION['Nombre'] = $row['Nombre'];
                $_SESSION['Id_Desarrollador'] = $row['Id_Desarrollador'];
                header("Location: ../panelControl.php");
                exit();
            } else {
                header("Location: ../inicioSesion.php?error=El correo o la clave son incorrectos");
                exit();
            }
        } else {
            header("Location: ../inicioSesion.php?error=El correo o la clave son incorrectos");
            exit();
        }
    }
} else {
    header("Location: ../inicioSesion.php");
    exit();
}