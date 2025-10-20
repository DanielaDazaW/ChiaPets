
<?php include("../config/bd.php"); ?>

<?php
ob_start();
// Variables y switches igual que antes, no cambia nada
$txtIDPersona = isset($_POST['txtIDPersona']) ? $_POST['txtIDPersona'] : "";
$txtNombres = isset($_POST['txtNombres']) ? $_POST['txtNombres'] : "";
$txtApellidos = isset($_POST['txtApellidos']) ? $_POST['txtApellidos'] : "";
$txtIDTipoDocumento = isset($_POST['txtIDTipoDocumento']) ? $_POST['txtIDTipoDocumento'] : "";
$txtNumeroDocumento = isset($_POST['txtNumeroDocumento']) ? $_POST['txtNumeroDocumento'] : "";
$txtCorreo = isset($_POST['txtCorreo']) ? $_POST['txtCorreo'] : "";
$txtTelefono = isset($_POST['txtTelefono']) ? $_POST['txtTelefono'] : "";
$txtIDGenero = isset($_POST['txtIDGenero']) ? $_POST['txtIDGenero'] : "";
$txtFechaNacimiento = isset($_POST['txtFechaNacimiento']) ? $_POST['txtFechaNacimiento'] : "";
$accionPersona = isset($_POST['accionPersona']) ? $_POST['accionPersona'] : "";

$txtIDOrganizacion = isset($_POST['txtIDOrganizacion']) ? $_POST['txtIDOrganizacion'] : "";
$txtOrganizacion = isset($_POST['txtOrganizacion']) ? $_POST['txtOrganizacion'] : "";
$txtContacto = isset($_POST['txtContacto']) ? $_POST['txtContacto'] : "";
$txtIDTipoOrganizacion = isset($_POST['txtIDTipoOrganizacion']) ? $_POST['txtIDTipoOrganizacion'] : "";
$accionOrganizacion = isset($_POST['accionOrganizacion']) ? $_POST['accionOrganizacion'] : "";

// Listas para selects
$sentenciaTipoDocSQL = $conexion->prepare("SELECT id_tipo_documento, tipo_documento FROM tipo_documento WHERE estado = 1");
$sentenciaTipoDocSQL->execute();
$listaTiposDocumento = $sentenciaTipoDocSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaGeneroSQL = $conexion->prepare("SELECT id_genero, genero FROM genero WHERE estado = 1");
$sentenciaGeneroSQL->execute();
$listaGeneros = $sentenciaGeneroSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaTipoSQL = $conexion->prepare("SELECT id_tipo_organizacion, tipo_organizacion FROM tipo_organizacion WHERE estado = 1");
$sentenciaTipoSQL->execute();
$listaTipos = $sentenciaTipoSQL->fetchAll(PDO::FETCH_ASSOC);

// Acciones personas
switch ($accionPersona) {
    case "AgregarPersona":
        $fechaRegistro = date('Y-m-d');
        $sentenciaSQL = $conexion->prepare("INSERT INTO personas (nombres, apellidos, id_tipo_documento, numero_documento, correo, telefono, id_genero, fecha_nacimiento, fecha_registro, estado) VALUES (:nombres, :apellidos, :id_tipo_documento, :numero_documento, :correo, :telefono, :id_genero, :fecha_nacimiento, :fecha_registro, 1)");
        $sentenciaSQL->bindParam(':nombres', $txtNombres);
        $sentenciaSQL->bindParam(':apellidos', $txtApellidos);
        $sentenciaSQL->bindParam(':id_tipo_documento', $txtIDTipoDocumento);
        $sentenciaSQL->bindParam(':numero_documento', $txtNumeroDocumento);
        $sentenciaSQL->bindParam(':correo', $txtCorreo);
        $sentenciaSQL->bindParam(':telefono', $txtTelefono);
        $sentenciaSQL->bindParam(':id_genero', $txtIDGenero);
        $sentenciaSQL->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
        $sentenciaSQL->bindParam(':fecha_registro', $fechaRegistro);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;
    case "ModificarPersona":
        $sentenciaSQL = $conexion->prepare("UPDATE personas SET nombres = :nombres, apellidos = :apellidos, id_tipo_documento = :id_tipo_documento, numero_documento = :numero_documento, correo = :correo, telefono = :telefono, id_genero = :id_genero, fecha_nacimiento = :fecha_nacimiento WHERE id_persona = :id");
        $sentenciaSQL->bindParam(':nombres', $txtNombres);
        $sentenciaSQL->bindParam(':apellidos', $txtApellidos);
        $sentenciaSQL->bindParam(':id_tipo_documento', $txtIDTipoDocumento);
        $sentenciaSQL->bindParam(':numero_documento', $txtNumeroDocumento);
        $sentenciaSQL->bindParam(':correo', $txtCorreo);
        $sentenciaSQL->bindParam(':telefono', $txtTelefono);
        $sentenciaSQL->bindParam(':id_genero', $txtIDGenero);
        $sentenciaSQL->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
        $sentenciaSQL->bindParam(':id', $txtIDPersona);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;
    case "CancelarPersona":
        header("Location: registros.php");
        exit;
        break;
    case "SeleccionarPersona":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM personas WHERE id_persona = :id");
        $sentenciaSQL->bindParam(':id', $txtIDPersona);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtNombres = $registro['nombres'];
        $txtApellidos = $registro['apellidos'];
        $txtIDTipoDocumento = $registro['id_tipo_documento'];
        $txtNumeroDocumento = $registro['numero_documento'];
        $txtCorreo = $registro['correo'];
        $txtTelefono = $registro['telefono'];
        $txtIDGenero = $registro['id_genero'];
        $txtFechaNacimiento = $registro['fecha_nacimiento'];
        break;
    case "BorrarPersona":
        $sentenciaSQL = $conexion->prepare("UPDATE personas SET estado = 0 WHERE id_persona = :id");
        $sentenciaSQL->bindParam(':id', $txtIDPersona);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;
}
// Acciones organizaciones
switch ($accionOrganizacion) {
    case "AgregarOrganizacion":
        $sentenciaSQL = $conexion->prepare("INSERT INTO organizacion (organizacion, contacto, id_tipo_organizacion, estado) VALUES (:organizacion, :contacto, :id_tipo_organizacion, 1)");
        $sentenciaSQL->bindParam(':organizacion', $txtOrganizacion);
        $sentenciaSQL->bindParam(':contacto', $txtContacto);
        $sentenciaSQL->bindParam(':id_tipo_organizacion', $txtIDTipoOrganizacion);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;
    case "ModificarOrganizacion":
        $sentenciaSQL = $conexion->prepare("UPDATE organizacion SET organizacion = :organizacion, contacto = :contacto, id_tipo_organizacion = :id_tipo_organizacion WHERE id_organizacion = :id");
        $sentenciaSQL->bindParam(':organizacion', $txtOrganizacion);
        $sentenciaSQL->bindParam(':contacto', $txtContacto);
        $sentenciaSQL->bindParam(':id_tipo_organizacion', $txtIDTipoOrganizacion);
        $sentenciaSQL->bindParam(':id', $txtIDOrganizacion);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;
    case "CancelarOrganizacion":
        header("Location: registros.php");
        exit;
        break;
    case "SeleccionarOrganizacion":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM organizacion WHERE id_organizacion = :id");
        $sentenciaSQL->bindParam(':id', $txtIDOrganizacion);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtOrganizacion = $registro['organizacion'];
        $txtContacto = $registro['contacto'];
        $txtIDTipoOrganizacion = $registro['id_tipo_organizacion'];
        break;
    case "BorrarOrganizacion":
        $sentenciaSQL = $conexion->prepare("UPDATE organizacion SET estado = 0 WHERE id_organizacion = :id");
        $sentenciaSQL->bindParam(':id', $txtIDOrganizacion);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;
}

// Listas de datos para tablas
$sentenciaSQL = $conexion->prepare("SELECT p.id_persona, p.nombres, p.apellidos, td.tipo_documento, p.numero_documento, p.correo, p.telefono, g.genero, p.fecha_nacimiento, p.fecha_registro FROM personas p INNER JOIN tipo_documento td ON p.id_tipo_documento = td.id_tipo_documento INNER JOIN genero g ON p.id_genero = g.id_genero WHERE p.estado = 1");
$sentenciaSQL->execute();
$listaPersonas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT o.id_organizacion, o.organizacion, o.contacto, t.tipo_organizacion FROM organizacion o INNER JOIN tipo_organizacion t ON o.id_tipo_organizacion = t.id_tipo_organizacion WHERE o.estado = 1");
$sentenciaSQL->execute();
$listaOrganizaciones = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

include("../template/cabecera.php"); 
?>

<div class="container">
    <h2>Formulario Personas</h2>
    <div class="card mb-5">
        <div class="card-body">
            <form method="POST" autocomplete="off">
                <input type="hidden" name="txtIDPersona" value="<?php echo $txtIDPersona; ?>">

                <div class="mb-3">
                    <label for="txtNombres" class="form-label">Nombres:</label>
                    <input type="text" required class="form-control" id="txtNombres" name="txtNombres" value="<?php echo $txtNombres ?? ''; ?>" placeholder="Nombres">
                </div>

                <div class="mb-3">
                    <label for="txtApellidos" class="form-label">Apellidos:</label>
                    <input type="text" required class="form-control" id="txtApellidos" name="txtApellidos" value="<?php echo $txtApellidos ?? ''; ?>" placeholder="Apellidos">
                </div>

                <div class="mb-3">
                    <label for="txtIDTipoDocumento" class="form-label">Tipo de Documento:</label>
                    <select class="form-select" id="txtIDTipoDocumento" name="txtIDTipoDocumento" required>
                        <option value="">Seleccione tipo</option>
                        <?php foreach ($listaTiposDocumento as $tipo) { ?>
                            <option value="<?php echo $tipo['id_tipo_documento']; ?>" <?php if (($txtIDTipoDocumento ?? '') == $tipo['id_tipo_documento']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($tipo['tipo_documento']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="txtNumeroDocumento" class="form-label">Número de Documento:</label>
                    <input type="number" required class="form-control" id="txtNumeroDocumento" name="txtNumeroDocumento" value="<?php echo $txtNumeroDocumento ?? ''; ?>" placeholder="Número de Documento">
                </div>

                <div class="mb-3">
                    <label for="txtCorreo" class="form-label">Correo:</label>
                    <input type="email" required class="form-control" id="txtCorreo" name="txtCorreo" value="<?php echo $txtCorreo ?? ''; ?>" placeholder="Correo">
                </div>

                <div class="mb-3">
                    <label for="txtTelefono" class="form-label">Teléfono:</label>
                    <input type="number" required class="form-control" id="txtTelefono" name="txtTelefono" value="<?php echo $txtTelefono ?? ''; ?>" placeholder="Teléfono">
                </div>

                <div class="mb-3">
                    <label for="txtIDGenero" class="form-label">Género:</label>
                    <select class="form-select" id="txtIDGenero" name="txtIDGenero" required>
                        <option value="">Seleccione género</option>
                        <?php foreach ($listaGeneros as $genero) { ?>
                            <option value="<?php echo $genero['id_genero']; ?>" <?php if (($txtIDGenero ?? '') == $genero['id_genero']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($genero['genero']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="txtFechaNacimiento" class="form-label">Fecha de Nacimiento:</label>
                    <input type="date" required class="form-control" id="txtFechaNacimiento" name="txtFechaNacimiento" value="<?php echo $txtFechaNacimiento ?? ''; ?>">
                </div>

                <div class="btn-group" role="group" aria-label="Botones registro persona">
                    <button type="submit" name="accionPersona" value="AgregarPersona" <?php echo ($accionPersona == "SeleccionarPersona") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                    <button type="submit" name="accionPersona" value="ModificarPersona" <?php echo ($accionPersona != "SeleccionarPersona") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accionPersona" value="CancelarPersona" <?php echo ($accionPersona != "SeleccionarPersona") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <h2>Listado Personas</h2>
    <div class="card mb-5">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Tipo Documento</th>
                        <th>Documento</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Género</th>
                        <th>Fecha Nacimiento</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaPersonas as $persona) { ?>
                        <tr>
                            <td><?php echo $persona['id_persona']; ?></td>
                            <td><?php echo htmlspecialchars($persona['nombres']); ?></td>
                            <td><?php echo htmlspecialchars($persona['apellidos']); ?></td>
                            <td><?php echo htmlspecialchars($persona['tipo_documento']); ?></td>
                            <td><?php echo htmlspecialchars($persona['numero_documento']); ?></td>
                            <td><?php echo htmlspecialchars($persona['correo']); ?></td>
                            <td><?php echo htmlspecialchars($persona['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($persona['genero']); ?></td>
                            <td><?php echo htmlspecialchars($persona['fecha_nacimiento']); ?></td>
                            <td><?php echo htmlspecialchars($persona['fecha_registro']); ?></td>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="txtIDPersona" value="<?php echo $persona['id_persona']; ?>">
                                    <input type="submit" name="accionPersona" value="SeleccionarPersona" class="btn btn-primary btn-sm">
                                </form>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="txtIDPersona" value="<?php echo $persona['id_persona']; ?>">
                                    <input type="submit" name="accionPersona" value="BorrarPersona" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta persona?');">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <h2>Formulario Organizaciones</h2>
    <div class="card mb-5">
        <div class="card-body">
            <form method="POST" autocomplete="off">
                <input type="hidden" name="txtIDOrganizacion" value="<?php echo $txtIDOrganizacion; ?>">
                <div class="mb-3">
                    <label for="txtOrganizacion" class="form-label">Organización:</label>
                    <input type="text" required autocomplete="off" class="form-control" id="txtOrganizacion" name="txtOrganizacion" value="<?php echo $txtOrganizacion; ?>" placeholder="Nombre de la organización">
                </div>
                <div class="mb-3">
                    <label for="txtContacto" class="form-label">Contacto:</label>
                    <input type="text" required autocomplete="off" class="form-control" id="txtContacto" name="txtContacto" value="<?php echo $txtContacto; ?>" placeholder="Contacto">
                </div>
                <div class="mb-3">
                    <label for="txtIDTipoOrganizacion" class="form-label">Tipo de Organización:</label>
                    <select class="form-select" id="txtIDTipoOrganizacion" name="txtIDTipoOrganizacion" required>
                        <option value="">Seleccione tipo</option>
                        <?php foreach ($listaTipos as $tipo) { ?>
                            <option value="<?php echo $tipo['id_tipo_organizacion']; ?>" <?php if ($txtIDTipoOrganizacion == $tipo['id_tipo_organizacion']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($tipo['tipo_organizacion']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="btn-group" role="group">
                    <button type="submit" name="accionOrganizacion" value="AgregarOrganizacion" <?php echo ($accionOrganizacion == "SeleccionarOrganizacion") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                    <button type="submit" name="accionOrganizacion" value="ModificarOrganizacion" <?php echo ($accionOrganizacion != "SeleccionarOrganizacion") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accionOrganizacion" value="CancelarOrganizacion" <?php echo ($accionOrganizacion != "SeleccionarOrganizacion") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <h2>Listado Organizaciones</h2>
    <div class="card mb-5">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Organización</th>
                        <th>Contacto</th>
                        <th>Tipo de Organización</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaOrganizaciones as $organizacion) { ?>
                        <tr>
                            <td><?php echo $organizacion['id_organizacion']; ?></td>
                            <td><?php echo htmlspecialchars($organizacion['organizacion']); ?></td>
                            <td><?php echo htmlspecialchars($organizacion['contacto']); ?></td>
                            <td><?php echo htmlspecialchars($organizacion['tipo_organizacion']); ?></td>
                            <td>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="txtIDOrganizacion" value="<?php echo $organizacion['id_organizacion']; ?>">
                                    <input type="submit" name="accionOrganizacion" value="SeleccionarOrganizacion" class="btn btn-primary btn-sm">
                                </form>
                                <form method="post" style="display:inline-block;">
                                    <input type="hidden" name="txtIDOrganizacion" value="<?php echo $organizacion['id_organizacion']; ?>">
                                    <input type="submit" name="accionOrganizacion" value="BorrarOrganizacion" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta organización?');">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../template/pie.php"); 
ob_end_flush();
?>
