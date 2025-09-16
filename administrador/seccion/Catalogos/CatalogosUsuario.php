<?php include("../../template/cabecera.php"); ?>

<?php
// Variables Tipo Usuario
$txtIDTipoUsuario = isset($_POST['txtIDTipoUsuario']) ? $_POST['txtIDTipoUsuario'] : "";
$txtTipoUsuario = isset($_POST['txtTipoUsuario']) ? $_POST['txtTipoUsuario'] : "";

// Variables Tipo Documento
$txtIDTipoDocumento = isset($_POST['txtIDTipoDocumento']) ? $_POST['txtIDTipoDocumento'] : "";
$txtTipoDocumento = isset($_POST['txtTipoDocumento']) ? $_POST['txtTipoDocumento'] : "";

// Variables Genero
$txtIDGenero = isset($_POST['txtIDGenero']) ? $_POST['txtIDGenero'] : "";
$txtGenero = isset($_POST['txtGenero']) ? $_POST['txtGenero'] : "";

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

include("../../config/bd.php");

// Acciones
switch ($accion) {
    // Tipo Usuario
    case "AgregarTipoUsuario":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_usuario (tipo_usuario, estado) VALUES (:tipo_usuario, 1)");
        $sentenciaSQL->bindParam(':tipo_usuario', $txtTipoUsuario);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "ModificarTipoUsuario":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_usuario SET tipo_usuario = :tipo_usuario WHERE id_tipo_usuario = :id");
        $sentenciaSQL->bindParam(':tipo_usuario', $txtTipoUsuario);
        $sentenciaSQL->bindParam(':id', $txtIDTipoUsuario);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "CancelarTipoUsuario":
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "SeleccionarTipoUsuario":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_usuario WHERE id_tipo_usuario = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoUsuario);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTipoUsuario = $registro['tipo_usuario'];
        break;

    case "BorrarTipoUsuario":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_usuario SET estado = 0 WHERE id_tipo_usuario = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoUsuario);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    // Tipo Documento
    case "AgregarTipoDocumento":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_documento (tipo_documento, estado) VALUES (:tipo_documento, 1)");
        $sentenciaSQL->bindParam(':tipo_documento', $txtTipoDocumento);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "ModificarTipoDocumento":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_documento SET tipo_documento = :tipo_documento WHERE id_tipo_documento = :id");
        $sentenciaSQL->bindParam(':tipo_documento', $txtTipoDocumento);
        $sentenciaSQL->bindParam(':id', $txtIDTipoDocumento);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "CancelarTipoDocumento":
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "SeleccionarTipoDocumento":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_documento WHERE id_tipo_documento = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoDocumento);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTipoDocumento = $registro['tipo_documento'];
        break;

    case "BorrarTipoDocumento":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_documento SET estado = 0 WHERE id_tipo_documento = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoDocumento);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    // Genero
    case "AgregarGenero":
        $sentenciaSQL = $conexion->prepare("INSERT INTO genero (genero, estado) VALUES (:genero, 1)");
        $sentenciaSQL->bindParam(':genero', $txtGenero);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "ModificarGenero":
        $sentenciaSQL = $conexion->prepare("UPDATE genero SET genero = :genero WHERE id_genero = :id");
        $sentenciaSQL->bindParam(':genero', $txtGenero);
        $sentenciaSQL->bindParam(':id', $txtIDGenero);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "CancelarGenero":
        header("Location: CatalogosUsuario.php");
        exit;
        break;

    case "SeleccionarGenero":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM genero WHERE id_genero = :id");
        $sentenciaSQL->bindParam(':id', $txtIDGenero);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtGenero = $registro['genero'];
        break;

    case "BorrarGenero":
        $sentenciaSQL = $conexion->prepare("UPDATE genero SET estado = 0 WHERE id_genero = :id");
        $sentenciaSQL->bindParam(':id', $txtIDGenero);
        $sentenciaSQL->execute();
        header("Location: CatalogosUsuario.php");
        exit;
        break;
}

// Consultas para mostrar solo registros activos
$sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_usuario WHERE estado = 1");
$sentenciaSQL->execute();
$listaTipoUsuario = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_documento WHERE estado = 1");
$sentenciaSQL->execute();
$listaTipoDocumento = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM genero WHERE estado = 1");
$sentenciaSQL->execute();
$listaGenero = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- FORMULARIO Y TABLA TIPO USUARIO -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tipo Usuario</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDTipoUsuario" value="<?php echo $txtIDTipoUsuario; ?>">
                    <div class="mb-3">
                        <label for="txtTipoUsuario" class="form-label">Tipo de Usuario:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtTipoUsuario" name="txtTipoUsuario" value="<?php echo $txtTipoUsuario; ?>" placeholder="Tipo de usuario">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarTipoUsuario" <?php echo ($accion == "SeleccionarTipoUsuario") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarTipoUsuario" <?php echo ($accion != "SeleccionarTipoUsuario") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarTipoUsuario" <?php echo ($accion != "SeleccionarTipoUsuario") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- col-md-5 -->

    <div class="col-md-7">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTipoUsuario as $registro) { ?>
                    <tr>
                        <td><?php echo $registro['id_tipo_usuario']; ?></td>
                        <td><?php echo htmlspecialchars($registro['tipo_usuario']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoUsuario" value="<?php echo $registro['id_tipo_usuario']; ?>">
                                <input type="submit" name="accion" value="SeleccionarTipoUsuario" class="btn btn-primary btn-sm">
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoUsuario" value="<?php echo $registro['id_tipo_usuario']; ?>">
                                <input type="submit" name="accion" value="BorrarTipoUsuario" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este tipo de usuario?');">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!-- col-md-7 -->
</div>

<!-- FORMULARIO Y TABLA TIPO DOCUMENTO -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tipo Documento</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDTipoDocumento" value="<?php echo $txtIDTipoDocumento; ?>">
                    <div class="mb-3">
                        <label for="txtTipoDocumento" class="form-label">Tipo de Documento:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtTipoDocumento" name="txtTipoDocumento" value="<?php echo $txtTipoDocumento; ?>" placeholder="Tipo de documento">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarGenero" <?php echo ($accion == "SeleccionarGenero") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarGenero" <?php echo ($accion != "SeleccionarGenero") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarGenero" <?php echo ($accion != "SeleccionarGenero") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- col-md-5 -->

    <div class="col-md-7">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo Documento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTipoDocumento as $registro) { ?>
                    <tr>
                        <td><?php echo $registro['id_tipo_documento']; ?></td>
                        <td><?php echo htmlspecialchars($registro['tipo_documento']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoDocumento" value="<?php echo $registro['id_tipo_documento']; ?>">
                                <input type="submit" name="accion" value="SeleccionarTipoDocumento" class="btn btn-primary btn-sm">
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoDocumento" value="<?php echo $registro['id_tipo_documento']; ?>">
                                <input type="submit" name="accion" value="BorrarTipoDocumento" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este tipo de documento?');">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!-- col-md-7 -->
</div>

<!-- FORMULARIO Y TABLA GENERO -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Género</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDGenero" value="<?php echo $txtIDGenero; ?>">
                    <div class="mb-3">
                        <label for="txtGenero" class="form-label">Género:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtGenero" name="txtGenero" value="<?php echo $txtGenero; ?>" placeholder="Género">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarGenero" <?php echo ($accion == "SeleccionarGenero") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarGenero" <?php echo ($accion != "SeleccionarGenero") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarGenero" <?php echo ($accion != "SeleccionarGenero") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- col-md-5 -->

    <div class="col-md-7">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaGenero as $registro) { ?>
                    <tr>
                        <td><?php echo $registro['id_genero']; ?></td>
                        <td><?php echo htmlspecialchars($registro['genero']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDGenero" value="<?php echo $registro['id_genero']; ?>">
                                <input type="submit" name="accion" value="SeleccionarGenero" class="btn btn-primary btn-sm">
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDGenero" value="<?php echo $registro['id_genero']; ?>">
                                <input type="submit" name="accion" value="BorrarGenero" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este género?');">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div><!-- col-md-7 -->
</div>

<?php include("../../template/pie.php"); ?>
