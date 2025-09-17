<?php include("../template/cabecera.php"); ?>
<?php include("../config/bd.php"); ?>

<?php
$txtIDOrganizacion = isset($_POST['txtIDOrganizacion']) ? $_POST['txtIDOrganizacion'] : "";
$txtOrganizacion = isset($_POST['txtOrganizacion']) ? $_POST['txtOrganizacion'] : "";
$txtContacto = isset($_POST['txtContacto']) ? $_POST['txtContacto'] : "";
$txtIDTipoOrganizacion = isset($_POST['txtIDTipoOrganizacion']) ? $_POST['txtIDTipoOrganizacion'] : "";
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

// Para obtener los tipos de organización activos
$sentenciaTipoSQL = $conexion->prepare("SELECT id_tipo_organizacion, tipo_organizacion FROM tipo_organizacion WHERE estado = 1");
$sentenciaTipoSQL->execute();
$listaTipos = $sentenciaTipoSQL->fetchAll(PDO::FETCH_ASSOC);

switch ($accion) {
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

// Obtener lista de organizaciones activas con nombre del tipo (JOIN)
$sentenciaSQL = $conexion->prepare("SELECT o.id_organizacion, o.organizacion, o.contacto, t.tipo_organizacion FROM organizacion o INNER JOIN tipo_organizacion t ON o.id_tipo_organizacion = t.id_tipo_organizacion WHERE o.estado = 1");
$sentenciaSQL->execute();
$listaOrganizaciones = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Organización</div>
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
                        <button type="submit" name="accion" value="AgregarOrganizacion" <?php echo ($accion == "SeleccionarOrganizacion") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarOrganizacion" <?php echo ($accion != "SeleccionarOrganizacion") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarOrganizacion" <?php echo ($accion != "SeleccionarOrganizacion") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
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
                                <input type="submit" name="accion" value="SeleccionarOrganizacion" class="btn btn-primary btn-sm">
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDOrganizacion" value="<?php echo $organizacion['id_organizacion']; ?>">
                                <input type="submit" name="accion" value="BorrarOrganizacion" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta organización?');">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../template/pie.php"); ?>
