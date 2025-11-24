<?php

include("administrador/config/bd.php");
include("template/cabecera.php");

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = intval($_SESSION['usuario_id']);
$mensaje = "";

// Cargar catálogos
$tiposDocumento = $conexion->query("SELECT id_tipo_documento, tipo_documento FROM tipo_documento WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$generos = $conexion->query("SELECT id_genero, genero FROM genero WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);

// Obtener persona vinculada al usuario autenticado
$stmt = $conexion->prepare("
    SELECT p.id_persona, p.nombres, p.apellidos, p.id_tipo_documento, p.numero_documento,
           p.correo, p.telefono, p.id_genero, p.fecha_nacimiento
    FROM usuario u
    JOIN personas p ON p.id_persona = u.id_persona
    WHERE u.id_usuario = :id_usuario AND p.estado = 1
    LIMIT 1
");
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$persona = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$persona) {
    $mensaje = "No fue posible cargar la información de la cuenta.";
}

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] === "POST" && $persona) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $id_tipo_documento = intval($_POST['id_tipo_documento']);
    $numero_documento = trim($_POST['numero_documento']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $id_genero = intval($_POST['id_genero']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $contrasena = $_POST['contrasena'] ?? "";
    $contrasena2 = $_POST['contrasena2'] ?? "";

        // Validaciones básicas y de formato
    if (empty($nombres) || empty($apellidos) || empty($numero_documento) || empty($correo) || empty($telefono)) {
        $mensaje = "Por favor, completa los campos obligatorios.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombres)) {
        $mensaje = "El nombre solo puede contener letras y espacios.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellidos)) {
        $mensaje = "El apellido solo puede contener letras y espacios.";
    } elseif (!preg_match('/^[0-9]{6,20}$/', $numero_documento)) {
        $mensaje = "El número de documento debe tener solo dígitos (6 a 20).";
    } elseif (!preg_match('/^[0-9]{7,15}$/', $telefono)) {
        $mensaje = "El teléfono debe tener solo dígitos (7 a 15).";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Correo inválido.";
    } elseif (!empty($fecha_nacimiento) && $fecha_nacimiento >= date('Y-m-d')) {
        $mensaje = "La fecha de nacimiento debe ser anterior a hoy.";
    } elseif ($contrasena !== "" || $contrasena2 !== "") {
        if ($contrasena !== $contrasena2) {
            $mensaje = "Las contraseñas no coinciden.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $contrasena)) {
            $mensaje = "La contraseña debe contener mayúsculas, minúsculas, números y mínimo 8 caracteres.";
        }
    }

    // Validaciones de unicidad (excluyendo la propia persona)
    if ($mensaje === "") {
        $stmtVerif = $conexion->prepare("
            SELECT COUNT(*) FROM personas 
            WHERE estado=1 AND id_persona <> :id_persona AND numero_documento = :numero_documento
        ");
        $stmtVerif->bindValue(':id_persona', $persona['id_persona'], PDO::PARAM_INT);
        $stmtVerif->bindValue(':numero_documento', $numero_documento);
        $stmtVerif->execute();
        if ($stmtVerif->fetchColumn() > 0) {
            $mensaje = "Este número de documento ya está registrado en otra cuenta.";
        }
    }
    if ($mensaje === "") {
        $stmtVerif2 = $conexion->prepare("
            SELECT COUNT(*) FROM personas 
            WHERE estado=1 AND id_persona <> :id_persona AND correo = :correo
        ");
        $stmtVerif2->bindValue(':id_persona', $persona['id_persona'], PDO::PARAM_INT);
        $stmtVerif2->bindValue(':correo', $correo);
        $stmtVerif2->execute();
        if ($stmtVerif2->fetchColumn() > 0) {
            $mensaje = "Este correo ya está registrado en otra cuenta.";
        }
    }

    // Aplicar cambios
    if ($mensaje === "") {
        $stmtUpd = $conexion->prepare("
            UPDATE personas
            SET nombres = :nombres,
                apellidos = :apellidos,
                id_tipo_documento = :id_tipo_documento,
                numero_documento = :numero_documento,
                correo = :correo,
                telefono = :telefono,
                id_genero = :id_genero,
                fecha_nacimiento = :fecha_nacimiento
            WHERE id_persona = :id_persona
        ");
        $stmtUpd->execute([
            ':nombres' => $nombres,
            ':apellidos' => $apellidos,
            ':id_tipo_documento' => $id_tipo_documento,
            ':numero_documento' => $numero_documento,
            ':correo' => $correo,
            ':telefono' => $telefono,
            ':id_genero' => $id_genero,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':id_persona' => $persona['id_persona'],
        ]);

        // Cambiar contraseña si se proporcionó
        if ($contrasena !== "" && $contrasena2 !== "") {
            $hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmtPwd = $conexion->prepare("UPDATE usuario SET contrasena = :contrasena WHERE id_usuario = :id_usuario");
            $stmtPwd->execute([':contrasena' => $hash, ':id_usuario' => $id_usuario]);
        }

        // Recargar datos actualizados para mostrar en el formulario
        header("Location: actualizar_mi_informacion.php?ok=1");
        exit();
    }

    // Mantener los valores ingresados si hubo error
    $persona = array_merge($persona, [
        'nombres' => $nombres,
        'apellidos' => $apellidos,
        'id_tipo_documento' => $id_tipo_documento,
        'numero_documento' => $numero_documento,
        'correo' => $correo,
        'telefono' => $telefono,
        'id_genero' => $id_genero,
        'fecha_nacimiento' => $fecha_nacimiento,
    ]);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
    <title>Actualizar mi información</title>
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4 text-center">Actualizar mi información</h2>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success">Información actualizada correctamente.</div>
    <?php endif; ?>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-warning"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <?php if ($persona): ?>
    <form method="post" autocomplete="off">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="nombres">Nombres</label>
                <input class="form-control" type="text" id="nombres" name="nombres" required value="<?php echo htmlspecialchars($persona['nombres']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="apellidos">Apellidos</label>
                <input class="form-control" type="text" id="apellidos" name="apellidos" required value="<?php echo htmlspecialchars($persona['apellidos']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="id_tipo_documento">Tipo de Documento</label>
                <select class="form-select" id="id_tipo_documento" name="id_tipo_documento" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($tiposDocumento as $td): ?>
                        <option value="<?php echo (int)$td['id_tipo_documento']; ?>" <?php echo ($persona['id_tipo_documento'] == $td['id_tipo_documento'] ? 'selected' : ''); ?>>
                            <?php echo htmlspecialchars($td['tipo_documento']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="numero_documento">Número de Documento</label>
                <input class="form-control" type="text" id="numero_documento" name="numero_documento" required value="<?php echo htmlspecialchars($persona['numero_documento']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="correo">Correo Electrónico</label>
                <input class="form-control" type="email" id="correo" name="correo" required value="<?php echo htmlspecialchars($persona['correo']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="telefono">Teléfono</label>
                <input class="form-control" type="text" id="telefono" name="telefono" required value="<?php echo htmlspecialchars($persona['telefono']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="id_genero">Género</label>
                <select class="form-select" id="id_genero" name="id_genero" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($generos as $g): ?>
                        <option value="<?php echo (int)$g['id_genero']; ?>" <?php echo ($persona['id_genero'] == $g['id_genero'] ? 'selected' : ''); ?>>
                            <?php echo htmlspecialchars($g['genero']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input class="form-control" type="date" id="fecha_nacimiento" name="fecha_nacimiento" max="<?php echo date('Y-m-d'); ?>" required value="<?php echo htmlspecialchars($persona['fecha_nacimiento']); ?>">
            </div>

            <div class="col-12"><hr></div>

            <div class="col-md-6">
                <label class="form-label" for="contrasena">Nueva Contraseña (opcional)</label>
                <input class="form-control" type="password" id="contrasena" name="contrasena" placeholder="Dejar en blanco para no cambiar">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="contrasena2">Confirmar Contraseña</label>
                <input class="form-control" type="password" id="contrasena2" name="contrasena2" placeholder="Requerido si cambias contraseña">
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary w-100" type="submit">Guardar cambios</button>
        </div>
    </form>
    <?php endif; ?>
  
</div>
</body>
</html>
<?php include("template/pie.php") ?>
