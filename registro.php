<?php
session_start();
include("administrador/config/bd.php");

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $id_tipo_documento = intval($_POST['id_tipo_documento']);
    $numero_documento = trim($_POST['numero_documento']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $id_genero = intval($_POST['id_genero']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $contrasena = $_POST['contrasena'];
    $contrasena2 = $_POST['contrasena2'];

    // Cargar listas por si hay que volver a mostrar el formulario
    $tiposDocumento = $conexion->query("SELECT id_tipo_documento, tipo_documento FROM tipo_documento WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
    $generos = $conexion->query("SELECT id_genero, genero FROM genero WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);

    // Validaciones
    if (empty($nombres) || empty($apellidos) || empty($numero_documento) || empty($correo) || empty($telefono) || empty($contrasena) || empty($contrasena2)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo inválido.";
    } elseif (!preg_match('/^\d+$/', $telefono)) {
        $mensaje = "El teléfono debe contener solo números.";
    } elseif ($contrasena !== $contrasena2) {
        $mensaje = "Las contraseñas no coinciden.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $contrasena)) {
        $mensaje = "La contraseña debe contener letras mayúsculas, minúsculas, números y tener mínimo 8 caracteres.";
    } elseif (!empty($fecha_nacimiento) && $fecha_nacimiento >= date('Y-m-d')) {
        $mensaje = "La fecha de nacimiento debe ser anterior a hoy.";
    } else {
        // Verifica si el número de documento ya existe en personas (activo o no)
        $stmtChk = $conexion->prepare("SELECT id_persona FROM personas WHERE numero_documento = :numero_documento LIMIT 1");
        $stmtChk->bindParam(':numero_documento', $numero_documento);
        $stmtChk->execute();
        $persona = $stmtChk->fetch(PDO::FETCH_ASSOC);

        if ($persona) {
            $id_persona = $persona['id_persona'];
            // ¿Tiene usuario?
            $stmtChkUser = $conexion->prepare("SELECT COUNT(*) FROM usuario WHERE id_persona = :id_persona");
            $stmtChkUser->bindParam(':id_persona', $id_persona);
            $stmtChkUser->execute();
            if ($stmtChkUser->fetchColumn() > 0) {
                // Ya es usuario
                $mensaje = "Este número de documento ya está registrado como usuario.";
            } else {
                // Está preregistrado por administración
                $_SESSION['id_persona_preregistro'] = $id_persona;
                echo '<!DOCTYPE html>
                <html lang="es">
                <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" href="./css/bootstrap.min.css">
                <title>Completar registro</title>
                </head>
                <body>
                <div class="container mt-4">
                    <div class="alert alert-info">
                        <b>Parece que ya estás preregistrado.</b><br>
                        ¿Deseas completar tu registro?
                        <form method="post" action="completar_registro.php" style="margin-top:10px;">
                            <input type="hidden" name="id_persona" value="' . $id_persona . '">
                            <button type="submit" class="btn btn-success">Completar Registro</button>
                        </form>
                    </div>
                </div>
                </body>
                </html>';
                exit;
            }
        } else {
            // No existe, registrar normalmente
            $fecha_registro = date('Y-m-d');
            $stmtInsertPersona = $conexion->prepare("INSERT INTO personas (nombres, apellidos, id_tipo_documento, numero_documento, correo, telefono, id_genero, fecha_nacimiento, fecha_registro, estado) VALUES (:nombres, :apellidos, :id_tipo_documento, :numero_documento, :correo, :telefono, :id_genero, :fecha_nacimiento, :fecha_registro, 1)");
            $stmtInsertPersona->bindParam(':nombres', $nombres);
            $stmtInsertPersona->bindParam(':apellidos', $apellidos);
            $stmtInsertPersona->bindParam(':id_tipo_documento', $id_tipo_documento);
            $stmtInsertPersona->bindParam(':numero_documento', $numero_documento);
            $stmtInsertPersona->bindParam(':correo', $correo);
            $stmtInsertPersona->bindParam(':telefono', $telefono);
            $stmtInsertPersona->bindParam(':id_genero', $id_genero);
            $stmtInsertPersona->bindParam(':fecha_nacimiento', $fecha_nacimiento);
            $stmtInsertPersona->bindParam(':fecha_registro', $fecha_registro);

            if ($stmtInsertPersona->execute()) {
                $id_persona = $conexion->lastInsertId();
                $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
                $id_tipo_usuario = 2;

                $stmtInsertUsuario = $conexion->prepare("INSERT INTO usuario (id_tipo_usuario, contrasena, id_persona) VALUES (:id_tipo_usuario, :contrasena, :id_persona)");
                $stmtInsertUsuario->bindParam(':id_tipo_usuario', $id_tipo_usuario);
                $stmtInsertUsuario->bindParam(':contrasena', $hashContrasena);
                $stmtInsertUsuario->bindParam(':id_persona', $id_persona);

                if ($stmtInsertUsuario->execute()) {
                    header("Location: login.php?registro=exito");
                    exit();
                } else {
                    $mensaje = "Error al crear usuario.";
                }
            } else {
                $mensaje = "Error al registrar persona.";
            }
        }
    }
} else {
    // cargar listas siempre para repintar el formulario
    $tiposDocumento = $conexion->query("SELECT id_tipo_documento, tipo_documento FROM tipo_documento WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
    $generos = $conexion->query("SELECT id_genero, genero FROM genero WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <title>Registro</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="main-content p-4 shadow rounded" style="background: #d4f763;">
                <h2 class="mb-4 text-center">Registro de Usuario</h2>
                <?php if ($mensaje): ?>
                <div class="alert alert-warning"><?php echo htmlspecialchars($mensaje); ?></div>
                <?php endif; ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" id="nombres" name="nombres" required value="<?php echo isset($nombres) ? htmlspecialchars($nombres) : ''; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" id="apellidos" name="apellidos" required value="<?php echo isset($apellidos) ? htmlspecialchars($apellidos) : ''; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="id_tipo_documento" class="form-label">Tipo de Documento</label>
                        <select id="id_tipo_documento" name="id_tipo_documento" required class="form-select">
                            <option value="">Seleccione</option>
                            <?php foreach ($tiposDocumento as $td): ?>
                                <option value="<?php echo htmlspecialchars($td['id_tipo_documento']); ?>" <?php echo (isset($id_tipo_documento) && $id_tipo_documento == $td['id_tipo_documento']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($td['tipo_documento']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="numero_documento" class="form-label">Número de Documento</label>
                        <input type="text" id="numero_documento" name="numero_documento" required value="<?php echo isset($numero_documento) ? htmlspecialchars($numero_documento) : ''; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" required value="<?php echo isset($correo) ? htmlspecialchars($correo) : ''; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" required value="<?php echo isset($telefono) ? htmlspecialchars($telefono) : ''; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="id_genero" class="form-label">Género</label>
                        <select id="id_genero" name="id_genero" required class="form-select">
                            <option value="">Seleccione</option>
                            <?php foreach ($generos as $g): ?>
                                <option value="<?php echo htmlspecialchars($g['id_genero']); ?>" <?php echo (isset($id_genero) && $id_genero == $g['id_genero']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($g['genero']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" max="<?php echo date('Y-m-d'); ?>" required value="<?php echo isset($fecha_nacimiento) ? htmlspecialchars($fecha_nacimiento) : ''; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" required placeholder="Min. 8 caracteres, mayúsculas, minúsculas y números" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="contrasena2" class="form-label">Confirmar Contraseña</label>
                        <input type="password" id="contrasena2" name="contrasena2" required class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Registrar</button>
                </form>
                <p class="text-center mt-3">
                    ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
