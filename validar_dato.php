<?php
session_start();
include("administrador/config/bd.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $numero_documento = trim($_POST['numero_documento']);
    $fecha_nacimiento = trim($_POST['fecha_nacimiento']);
    $telefono = trim($_POST['telefono']);

    $query = "
        SELECT u.id_persona, p.nombres, p.apellidos, p.correo 
        FROM personas p
        INNER JOIN usuario u ON p.id_persona = u.id_persona
        WHERE p.correo = :correo
          AND p.numero_documento = :numero_documento
          AND p.fecha_nacimiento = :fecha_nacimiento
          AND p.telefono = :telefono
          AND p.estado = 1
        LIMIT 1
    ";

    $stmt = $conexion->prepare($query);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':numero_documento', $numero_documento);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':telefono', $telefono);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Guardar id_persona en sesión para actualizar contraseña
        $_SESSION['id_persona'] = $user['id_persona'];
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Actualizar Contraseña</title>
            <style>
                body { background: #d4f763; font-family: Arial, sans-serif; padding: 2rem; }
                .container { background: white; max-width: 400px; margin: 0 auto; padding: 1.5rem; border-radius: 8px; text-align: center; }
                input { margin-bottom: 1rem; padding: 0.5rem; width: 100%; font-size: 1rem; }
                button { background-color: #78bc15; color: white; font-weight: bold; padding: 0.7rem; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
                button:hover { background-color: #5e921a; }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>Actualizar Contraseña para <?php echo htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']); ?></h2>
                <form method="post" action="actualizar_contrasena.php" autocomplete="off">
                    <input type="password" name="contrasena" placeholder="Nueva contraseña" required>
                    <input type="password" name="contrasena2" placeholder="Confirmar nueva contraseña" required>
                    <button type="submit">Actualizar Contraseña</button>
                </form>
            </div>
        </body>
        </html>

        <?php
    } else {
        $_SESSION['mensaje_olvido'] = "Datos incorrectos o usuario no encontrado.";
        header("Location: olvido_contrasena.php");
        exit;
    }
} else {
    header("Location: olvido_contrasena.php");
    exit;
}
?>
