<?php
session_start();
include("administrador/config/bd.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_persona'])) {
        header("Location: olvido_contrasena.php");
        exit;
    }
    $id_persona = $_SESSION['id_persona'];
    $contrasena = $_POST['contrasena'] ?? '';
    $contrasena2 = $_POST['contrasena2'] ?? '';

    if ($contrasena !== $contrasena2) {
        $mensaje = "Las contraseñas no coinciden.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $contrasena)) {
        $mensaje = "La contraseña debe contener al menos 8 caracteres, letras mayúsculas, minúsculas y números.";
    } else {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("UPDATE usuario SET contrasena = :contrasena WHERE id_persona = :id_persona");
        $stmt->bindParam(':contrasena', $hash);
        $stmt->bindParam(':id_persona', $id_persona);

        if ($stmt->execute()) {
            unset($_SESSION['id_persona']);
            $exito = "Contraseña actualizada correctamente.";
        } else {
            $mensaje = "Error al actualizar la contraseña.";
        }
    }
} else {
    header("Location: olvido_contrasena.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualizar Contraseña</title>
<style>
    body {
        background: linear-gradient(160deg, #e0ffc4 0%, #b9efa9 100%);
        font-family: Arial, sans-serif;
        min-height: 100vh;
        margin: 0;
        padding: 2rem;
    }
    .container {
        max-width: 400px;
        margin: 3rem auto;
        background: #d4f763;
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        padding: 2rem;
        text-align: center;
    }
    h2 {
        color: #50770a;
        margin-bottom: 1.5rem;
    }
    input[type="password"] {
        width: 100%;
        padding: 0.7rem;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
        border: 1px solid #a9a9a9;
        font-size: 1rem;
    }
    button {
        background: #95e300;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 0.45rem;
        padding: 0.85rem 0;
        width: 100%;
        cursor: pointer;
        font-size: 1.1rem;
        transition: background 0.2s;
    }
    button:hover {
        background: #78bc15;
    }
    .alert {
        padding: 0.7rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        font-weight: bold;
    }
    .alert-error {
        background: #fff3cd;
        color: #856404;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
    }
    a.btn-link {
        display: inline-block;
        margin-top: 1rem;
        color: #1da52b;
        font-weight: 600;
        text-decoration: none;
    }
    a.btn-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Actualizar Contraseña</h2>
    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-error"><?php echo $mensaje; ?></div>
    <?php elseif (!empty($exito)): ?>
        <div class="alert alert-success"><?php echo $exito; ?></div>
        <a href="login.php" class="btn-link">Ir al inicio de sesión</a>
    <?php endif; ?>

    <?php if (empty($exito)): ?>
    <form method="post" autocomplete="off">
        <input type="password" name="contrasena" placeholder="Nueva contraseña" required>
        <input type="password" name="contrasena2" placeholder="Confirmar nueva contraseña" required>
        <button type="submit">Actualizar Contraseña</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
