<?php
session_start();
include("administrador/config/bd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_persona = $_POST['id_persona'];
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $id_genero = intval($_POST['id_genero']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $contrasena = $_POST['contrasena'];
    $contrasena2 = $_POST['contrasena2'];

    if ($contrasena !== $contrasena2) {
        echo '<div class="alert alert-warning">Las contraseñas no coinciden.</div>';
        exit;
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $contrasena)) {
        echo '<div class="alert alert-warning">La contraseña debe tener al menos 8 caracteres, mayúscula, minúscula y números.</div>';
        exit;
    }

    $stmtUPD = $conexion->prepare("UPDATE personas SET correo=:correo, telefono=:telefono, id_genero=:id_genero, fecha_nacimiento=:fecha_nacimiento, estado=1 WHERE id_persona=:id_persona");
    $stmtUPD->bindParam(':correo', $correo);
    $stmtUPD->bindParam(':telefono', $telefono);
    $stmtUPD->bindParam(':id_genero', $id_genero);
    $stmtUPD->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmtUPD->bindParam(':id_persona', $id_persona);

    if ($stmtUPD->execute()) {
        $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $id_tipo_usuario = 2;
        $stmtUser = $conexion->prepare("INSERT INTO usuario (id_tipo_usuario, contrasena, id_persona) VALUES (:id_tipo_usuario, :contrasena, :id_persona)");
        $stmtUser->bindParam(':id_tipo_usuario', $id_tipo_usuario);
        $stmtUser->bindParam(':contrasena', $hashContrasena);
        $stmtUser->bindParam(':id_persona', $id_persona);

        if ($stmtUser->execute()) {
            echo '<div class="alert alert-success">Registro completado. <a href="login.php">Iniciar sesión</a></div>';
        } else {
            echo '<div class="alert alert-danger">Error al crear usuario.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Error al actualizar tus datos.</div>';
    }
} else {
    header("Location: registro.php");
    exit;
}
?>
