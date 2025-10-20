<?php
ob_start();
include("../config/bd.php");

// Variables formulario
$txtIdUsuario = $_POST['txtIdUsuario'] ?? '';
$txtNombres = $_POST['nombres'] ?? '';
$txtApellidos = $_POST['apellidos'] ?? '';
$txtIdTipoDocumento = $_POST['id_tipo_documento'] ?? '';
$txtNumeroDocumento = $_POST['numero_documento'] ?? '';
$txtCorreo = $_POST['correo'] ?? '';
$txtTelefono = $_POST['telefono'] ?? '';
$txtIdGenero = $_POST['id_genero'] ?? '';
$txtFechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
$txtContrasena = $_POST['contrasena'] ?? '';
$txtContrasena2 = $_POST['contrasena2'] ?? '';
$txtIdTipoUsuario = $_POST['id_tipo_usuario'] ?? '';
$accion = $_POST['accion'] ?? '';

// Manejo acciones CRUD
switch ($accion) {
    case 'Agregar':
        // Validar contraseñas y campos mínimos aquí si quieres
        if ($txtContrasena !== $txtContrasena2) {
            $mensaje = "Las contraseñas no coinciden.";
        } else {
            // Revisa si documento ya existe
            $stmtCheck = $conexion->prepare("SELECT COUNT(*) FROM personas WHERE numero_documento = :numero_documento AND estado=1");
            $stmtCheck->bindParam(':numero_documento', $txtNumeroDocumento);
            $stmtCheck->execute();
            if ($stmtCheck->fetchColumn() > 0) {
                $mensaje = "Número de documento ya registrado.";
            } else {
                // Inserta persona
                $fechaRegistro = date('Y-m-d');
                $stmtPersona = $conexion->prepare("INSERT INTO personas (nombres, apellidos, id_tipo_documento, numero_documento, correo, telefono, id_genero, fecha_nacimiento, fecha_registro, estado) VALUES (:nombres, :apellidos, :id_tipo_documento, :numero_documento, :correo, :telefono, :id_genero, :fecha_nacimiento, :fecha_registro, 1)");
                $stmtPersona->bindParam(':nombres', $txtNombres);
                $stmtPersona->bindParam(':apellidos', $txtApellidos);
                $stmtPersona->bindParam(':id_tipo_documento', $txtIdTipoDocumento);
                $stmtPersona->bindParam(':numero_documento', $txtNumeroDocumento);
                $stmtPersona->bindParam(':correo', $txtCorreo);
                $stmtPersona->bindParam(':telefono', $txtTelefono);
                $stmtPersona->bindParam(':id_genero', $txtIdGenero);
                $stmtPersona->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
                $stmtPersona->bindParam(':fecha_registro', $fechaRegistro);
                if ($stmtPersona->execute()) {
                    $idPersona = $conexion->lastInsertId();
                    $hashContrasena = password_hash($txtContrasena, PASSWORD_DEFAULT);
                    // Inserta usuario
                    $stmtUsuario = $conexion->prepare("INSERT INTO usuario (id_tipo_usuario, contrasena, id_persona) VALUES (:id_tipo_usuario, :contrasena, :id_persona)");
                    $stmtUsuario->bindParam(':id_tipo_usuario', $txtIdTipoUsuario);
                    $stmtUsuario->bindParam(':contrasena', $hashContrasena);
                    $stmtUsuario->bindParam(':id_persona', $idPersona);
                    if ($stmtUsuario->execute()) {
                        header("Location: roles.php");
                        exit;
                    } else {
                        $mensaje = "Error al crear usuario.";
                    }
                } else {
                    $mensaje = "Error al crear persona.";
                }
            }
        }
        break;
    case 'Seleccionar':
        // Carga datos para editar
        $stmt = $conexion->prepare("SELECT u.id_usuario, u.id_tipo_usuario, u.contrasena, p.* FROM usuario u JOIN personas p ON u.id_persona = p.id_persona WHERE u.id_usuario = :id");
        $stmt->bindParam(':id', $txtIdUsuario);
        $stmt->execute();
        $usuarioSeleccionado = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usuarioSeleccionado) {
            $txtNombres = $usuarioSeleccionado['nombres'];
            $txtApellidos = $usuarioSeleccionado['apellidos'];
            $txtIdTipoDocumento = $usuarioSeleccionado['id_tipo_documento'];
            $txtNumeroDocumento = $usuarioSeleccionado['numero_documento'];
            $txtCorreo = $usuarioSeleccionado['correo'];
            $txtTelefono = $usuarioSeleccionado['telefono'];
            $txtIdGenero = $usuarioSeleccionado['id_genero'];
            $txtFechaNacimiento = $usuarioSeleccionado['fecha_nacimiento'];
            $txtIdTipoUsuario = $usuarioSeleccionado['id_tipo_usuario'];
        }
        break;
    case 'Modificar':
        // Editar persona y usuario
        $stmtModPersona = $conexion->prepare("UPDATE personas SET nombres = :nombres, apellidos = :apellidos, id_tipo_documento = :id_tipo_documento, numero_documento = :numero_documento, correo = :correo, telefono = :telefono, id_genero = :id_genero, fecha_nacimiento = :fecha_nacimiento WHERE id_persona = (SELECT id_persona FROM usuario WHERE id_usuario = :id_usuario)");
        $stmtModPersona->bindParam(':nombres', $txtNombres);
        $stmtModPersona->bindParam(':apellidos', $txtApellidos);
        $stmtModPersona->bindParam(':id_tipo_documento', $txtIdTipoDocumento);
        $stmtModPersona->bindParam(':numero_documento', $txtNumeroDocumento);
        $stmtModPersona->bindParam(':correo', $txtCorreo);
        $stmtModPersona->bindParam(':telefono', $txtTelefono);
        $stmtModPersona->bindParam(':id_genero', $txtIdGenero);
        $stmtModPersona->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
        $stmtModPersona->bindParam(':id_usuario', $txtIdUsuario);
        $stmtModPersona->execute();

        $params = [
            ':id_tipo_usuario' => $txtIdTipoUsuario,
            ':id_usuario' => $txtIdUsuario,
        ];

        $sqlModUsuario = "UPDATE usuario SET id_tipo_usuario = :id_tipo_usuario";
        if (!empty($txtContrasena)) {
            $hashContrasena = password_hash($txtContrasena, PASSWORD_DEFAULT);
            $sqlModUsuario .= ", contrasena = :contrasena";
            $params[':contrasena'] = $hashContrasena;
        }
        $sqlModUsuario .= " WHERE id_usuario = :id_usuario";

        $stmtModUsuario = $conexion->prepare($sqlModUsuario);

        foreach ($params as $param => $val) {
            $stmtModUsuario->bindValue($param, $val);
        }
        $stmtModUsuario->execute();

        header("Location: roles.php");
        exit;
    case 'Borrar':
        // Eliminar usuario y persona asociado
        $stmtBorrarUsuario = $conexion->prepare("SELECT id_persona FROM usuario WHERE id_usuario = :id");
        $stmtBorrarUsuario->bindParam(':id', $txtIdUsuario);
        $stmtBorrarUsuario->execute();
        $res = $stmtBorrarUsuario->fetch(PDO::FETCH_ASSOC);
        if ($res) {
            $idPersonaBorrar = $res['id_persona'];
            $stmtDelUsuario = $conexion->prepare("DELETE FROM usuario WHERE id_usuario = :id");
            $stmtDelUsuario->bindParam(':id', $txtIdUsuario);
            $stmtDelUsuario->execute();

            $stmtDelPersona = $conexion->prepare("DELETE FROM personas WHERE id_persona = :id");
            $stmtDelPersona->bindParam(':id', $idPersonaBorrar);
            $stmtDelPersona->execute();
        }
        header("Location: roles.php");
        exit;
    case 'Cancelar':
        header("Location: roles.php");
        exit;
}

// Listas para selects
$tiposDocumento = $conexion->query("SELECT id_tipo_documento, tipo_documento FROM tipo_documento WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$generos = $conexion->query("SELECT id_genero, genero FROM genero WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaTiposUsuario = $conexion->query("SELECT id_tipo_usuario, tipo_usuario FROM tipo_usuario WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);

// Listar usuarios con detalle
$listaUsuarios = $conexion->query("
    SELECT u.id_usuario, u.id_tipo_usuario, t.tipo_usuario, p.*
    FROM usuario u
    JOIN tipo_usuario t ON u.id_tipo_usuario = t.id_tipo_usuario
    JOIN personas p ON u.id_persona = p.id_persona
    ORDER BY u.id_usuario DESC
")->fetchAll(PDO::FETCH_ASSOC);

include("../template/cabecera.php");
?>

<div class="container">
    <h2 class="mb-4 text-center">Gestión y Registro de Usuarios</h2>

    <?php if (isset($mensaje) && $mensaje) : ?>
        <div class="alert alert-warning"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIdUsuario" value="<?= htmlspecialchars($txtIdUsuario) ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombres" class="form-label">Nombres</label>
                <input type="text" class="form-control" id="nombres" name="nombres" required value="<?= htmlspecialchars($txtNombres) ?>">
            </div>
            <div class="col-md-6">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" required value="<?= htmlspecialchars($txtApellidos) ?>">
            </div>
            <div class="col-md-4">
                <label for="id_tipo_documento" class="form-label">Tipo de Documento</label>
                <select class="form-select" id="id_tipo_documento" name="id_tipo_documento" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($tiposDocumento as $td) : ?>
                        <option value="<?= htmlspecialchars($td['id_tipo_documento']) ?>" <?= $txtIdTipoDocumento == $td['id_tipo_documento'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($td['tipo_documento']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="numero_documento" class="form-label">Número de Documento</label>
                <input type="text" class="form-control" id="numero_documento" name="numero_documento" required value="<?= htmlspecialchars($txtNumeroDocumento) ?>">
            </div>
            <div class="col-md-4">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required value="<?= htmlspecialchars($txtCorreo) ?>">
            </div>
            <div class="col-md-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required value="<?= htmlspecialchars($txtTelefono) ?>">
            </div>
            <div class="col-md-4">
                <label for="id_genero" class="form-label">Género</label>
                <select class="form-select" id="id_genero" name="id_genero" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($generos as $g) : ?>
                        <option value="<?= htmlspecialchars($g['id_genero']) ?>" <?= $txtIdGenero == $g['id_genero'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['genero']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" max="<?= date('Y-m-d') ?>" required value="<?= htmlspecialchars($txtFechaNacimiento) ?>">
            </div>
            <div class="col-md-4">
                <label for="id_tipo_usuario" class="form-label">Rol de Usuario</label>
                <select class="form-select" id="id_tipo_usuario" name="id_tipo_usuario" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($listaTiposUsuario as $tipoU) : ?>
                        <option value="<?= $tipoU['id_tipo_usuario'] ?>" <?= $txtIdTipoUsuario == $tipoU['id_tipo_usuario'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipoU['tipo_usuario']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="contrasena" class="form-label">Contraseña <?= $accion == "Seleccionar" ? "(dejar en blanco para no cambiar)" : "" ?></label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" <?= $accion == "Seleccionar" ? "" : "required" ?>>
            </div>
            <div class="col-md-4">
                <label for="contrasena2" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="contrasena2" name="contrasena2" <?= $accion == "Seleccionar" ? "" : "required" ?>>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" name="accion" value="Agregar" class="btn btn-success" <?= $accion == "Seleccionar" ? "disabled" : "" ?>>Agregar</button>
            <button type="submit" name="accion" value="Modificar" class="btn btn-warning" <?= $accion == "Seleccionar" ? "" : "disabled" ?>>Modificar</button>
            <button type="submit" name="accion" value="Borrar" class="btn btn-danger" <?= $accion == "Seleccionar" ? "" : "disabled" ?> onclick="return confirm('¿Está seguro que desea eliminar este registro?')">Borrar</button>
            <button type="submit" name="accion" value="Cancelar" class="btn btn-info" <?= $accion == "Seleccionar" ? "" : "disabled" ?>>Cancelar</button>
        </div>
    </form>

    <h3 class="mt-5">Listado de Usuarios</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre Completo</th>
                <th>Tipo Documento</th>
                <th>Número Documento</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Género</th>
                <th>Fecha Nacimiento</th>
                <th>Rol Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaUsuarios as $user) : ?>
                <tr>
                    <td><?= htmlspecialchars($user['id_usuario']) ?></td>
                    <td><?= htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']) ?></td>
                    <td><?= htmlspecialchars($user['id_tipo_documento']) ?></td>
                    <td><?= htmlspecialchars($user['numero_documento']) ?></td>
                    <td><?= htmlspecialchars($user['correo']) ?></td>
                    <td><?= htmlspecialchars($user['telefono']) ?></td>
                    <td><?= htmlspecialchars($user['id_genero']) ?></td>
                    <td><?= htmlspecialchars($user['fecha_nacimiento']) ?></td>
                    <td><?= htmlspecialchars($user['tipo_usuario']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="txtIdUsuario" value="<?= $user['id_usuario'] ?>">
                            <button type="submit" name="accion" value="Seleccionar" class="btn btn-primary btn-sm">Editar</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="txtIdUsuario" value="<?= $user['id_usuario'] ?>">
                            <button type="submit" name="accion" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro que desea eliminar este registro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include("../template/pie.php"); 
ob_end_flush();
?>
