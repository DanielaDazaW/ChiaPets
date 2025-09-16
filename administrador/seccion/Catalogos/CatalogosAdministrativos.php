<?php include("../../template/cabecera.php"); ?>

<?php
// Variables para cada catálogo
$txtIDTipoCampana = isset($_POST['txtIDTipoCampana']) ? $_POST['txtIDTipoCampana'] : "";
$txtTipoCampana = isset($_POST['txtTipoCampana']) ? $_POST['txtTipoCampana'] : "";

$txtIDTipoReporte = isset($_POST['txtIDTipoReporte']) ? $_POST['txtIDTipoReporte'] : "";
$txtTipoReporte = isset($_POST['txtTipoReporte']) ? $_POST['txtTipoReporte'] : "";

$txtIDTipoVacuna = isset($_POST['txtIDTipoVacuna']) ? $_POST['txtIDTipoVacuna'] : "";
$txtTipoVacuna = isset($_POST['txtTipoVacuna']) ? $_POST['txtTipoVacuna'] : "";

$txtIDTipoOrganizacion = isset($_POST['txtIDTipoOrganizacion']) ? $_POST['txtIDTipoOrganizacion'] : "";
$txtTipoOrganizacion = isset($_POST['txtTipoOrganizacion']) ? $_POST['txtTipoOrganizacion'] : "";

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

include("../../config/bd.php");

switch ($accion) {

    // Tipo Campana
    case "AgregarTipoCampana":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_campana (tipo_campana, estado) VALUES (:tipo_campana, 1)");
        $sentenciaSQL->bindParam(':tipo_campana', $txtTipoCampana);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "ModificarTipoCampana":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_campana SET tipo_campana = :tipo_campana WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':tipo_campana', $txtTipoCampana);
        $sentenciaSQL->bindParam(':id', $txtIDTipoCampana);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "CancelarTipoCampana":
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "SeleccionarTipoCampana":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_campana WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoCampana);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTipoCampana = $registro['tipo_campana'];
        break;

    case "BorrarTipoCampana":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_campana SET estado = 0 WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoCampana);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    // Tipo Reporte
    case "AgregarTipoReporte":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_reporte (tipo_reporte, estado) VALUES (:tipo_reporte, 1)");
        $sentenciaSQL->bindParam(':tipo_reporte', $txtTipoReporte);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "ModificarTipoReporte":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_reporte SET tipo_reporte = :tipo_reporte WHERE id_tipo_reporte = :id");
        $sentenciaSQL->bindParam(':tipo_reporte', $txtTipoReporte);
        $sentenciaSQL->bindParam(':id', $txtIDTipoReporte);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "CancelarTipoReporte":
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "SeleccionarTipoReporte":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_reporte WHERE id_tipo_reporte = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoReporte);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTipoReporte = $registro['tipo_reporte'];
        break;

    case "BorrarTipoReporte":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_reporte SET estado = 0 WHERE id_tipo_reporte = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoReporte);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    // Tipo Vacuna
    case "AgregarTipoVacuna":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_vacuna (tipo_vacuna, estado) VALUES (:tipo_vacuna, 1)");
        $sentenciaSQL->bindParam(':tipo_vacuna', $txtTipoVacuna);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "ModificarTipoVacuna":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_vacuna SET tipo_vacuna = :tipo_vacuna WHERE id_tipo_vacuna = :id");
        $sentenciaSQL->bindParam(':tipo_vacuna', $txtTipoVacuna);
        $sentenciaSQL->bindParam(':id', $txtIDTipoVacuna);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "CancelarTipoVacuna":
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "SeleccionarTipoVacuna":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_vacuna WHERE id_tipo_vacuna = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoVacuna);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTipoVacuna = $registro['tipo_vacuna'];
        break;

    case "BorrarTipoVacuna":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_vacuna SET estado = 0 WHERE id_tipo_vacuna = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoVacuna);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    // Tipo Organizacion
    case "AgregarTipoOrganizacion":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_organizacion (tipo_organizacion, estado) VALUES (:tipo_organizacion, 1)");
        $sentenciaSQL->bindParam(':tipo_organizacion', $txtTipoOrganizacion);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "ModificarTipoOrganizacion":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_organizacion SET tipo_organizacion = :tipo_organizacion WHERE id_tipo_organizacion = :id");
        $sentenciaSQL->bindParam(':tipo_organizacion', $txtTipoOrganizacion);
        $sentenciaSQL->bindParam(':id', $txtIDTipoOrganizacion);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "CancelarTipoOrganizacion":
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "SeleccionarTipoOrganizacion":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_organizacion WHERE id_tipo_organizacion = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoOrganizacion);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTipoOrganizacion = $registro['tipo_organizacion'];
        break;

    case "BorrarTipoOrganizacion":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_organizacion SET estado = 0 WHERE id_tipo_organizacion = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTipoOrganizacion);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;
}

// Consultar registros activos para mostrar
$sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_campana WHERE estado = 1");
$sentenciaSQL->execute();
$listaTipoCampana = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_reporte WHERE estado = 1");
$sentenciaSQL->execute();
$listaTipoReporte = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_vacuna WHERE estado = 1");
$sentenciaSQL->execute();
$listaTipoVacuna = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_organizacion WHERE estado = 1");
$sentenciaSQL->execute();
$listaTipoOrganizacion = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Tipo Campaña -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tipo Campaña</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDTipoCampana" value="<?php echo $txtIDTipoCampana; ?>">
                    <div class="mb-3">
                        <label for="txtTipoCampana" class="form-label">Tipo de Campaña:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtTipoCampana" name="txtTipoCampana" value="<?php echo $txtTipoCampana; ?>" placeholder="Tipo de campaña">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarTipoCampana" <?php echo ($accion == "SeleccionarTipoCampana") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarTipoCampana" <?php echo ($accion != "SeleccionarTipoCampana") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarTipoCampana" <?php echo ($accion != "SeleccionarTipoCampana") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
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
                    <th>Tipo Campaña</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTipoCampana as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_tipo_campana']; ?></td>
                        <td><?php echo htmlspecialchars($item['tipo_campana']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoCampana" value="<?php echo $item['id_tipo_campana']; ?>">
                                <input type="submit" name="accion" value="SeleccionarTipoCampana" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoCampana" value="<?php echo $item['id_tipo_campana']; ?>">
                                <input type="submit" name="accion" value="BorrarTipoCampana" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta campaña?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tipo Reporte -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tipo Reporte</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDTipoReporte" value="<?php echo $txtIDTipoReporte; ?>">
                    <div class="mb-3">
                        <label for="txtTipoReporte" class="form-label">Tipo de Reporte:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtTipoReporte" name="txtTipoReporte" value="<?php echo $txtTipoReporte; ?>" placeholder="Tipo de reporte">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarTipoReporte" <?php echo ($accion == "SeleccionarTipoReporte") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarTipoReporte" <?php echo ($accion != "SeleccionarTipoReporte") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarTipoReporte" <?php echo ($accion != "SeleccionarTipoReporte") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
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
                    <th>Tipo Reporte</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTipoReporte as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_tipo_reporte']; ?></td>
                        <td><?php echo htmlspecialchars($item['tipo_reporte']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoReporte" value="<?php echo $item['id_tipo_reporte']; ?>">
                                <input type="submit" name="accion" value="SeleccionarTipoReporte" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoReporte" value="<?php echo $item['id_tipo_reporte']; ?>">
                                <input type="submit" name="accion" value="BorrarTipoReporte" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este tipo de reporte?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tipo Vacuna -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tipo Vacuna</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDTipoVacuna" value="<?php echo $txtIDTipoVacuna; ?>">
                    <div class="mb-3">
                        <label for="txtTipoVacuna" class="form-label">Tipo de Vacuna:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtTipoVacuna" name="txtTipoVacuna" value="<?php echo $txtTipoVacuna; ?>" placeholder="Tipo de vacuna">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarTipoVacuna" <?php echo ($accion == "SeleccionarTipoVacuna") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarTipoVacuna" <?php echo ($accion != "SeleccionarTipoVacuna") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarTipoVacuna" <?php echo ($accion != "SeleccionarTipoVacuna") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
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
                    <th>Tipo Vacuna</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTipoVacuna as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_tipo_vacuna']; ?></td>
                        <td><?php echo htmlspecialchars($item['tipo_vacuna']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoVacuna" value="<?php echo $item['id_tipo_vacuna']; ?>">
                                <input type="submit" name="accion" value="SeleccionarTipoVacuna" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoVacuna" value="<?php echo $item['id_tipo_vacuna']; ?>">
                                <input type="submit" name="accion" value="BorrarTipoVacuna" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta vacuna?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tipo Organización -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tipo Organización</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDTipoOrganizacion" value="<?php echo $txtIDTipoOrganizacion; ?>">
                    <div class="mb-3">
                        <label for="txtTipoOrganizacion" class="form-label">Tipo de Organización:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtTipoOrganizacion" name="txtTipoOrganizacion" value="<?php echo $txtTipoOrganizacion; ?>" placeholder="Tipo de organización">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarTipoOrganizacion" <?php echo ($accion == "SeleccionarTipoOrganizacion") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarTipoOrganizacion" <?php echo ($accion != "SeleccionarTipoOrganizacion") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarTipoOrganizacion" <?php echo ($accion != "SeleccionarTipoOrganizacion") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
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
                    <th>Tipo Organización</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTipoOrganizacion as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_tipo_organizacion']; ?></td>
                        <td><?php echo htmlspecialchars($item['tipo_organizacion']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoOrganizacion" value="<?php echo $item['id_tipo_organizacion']; ?>">
                                <input type="submit" name="accion" value="SeleccionarTipoOrganizacion" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTipoOrganizacion" value="<?php echo $item['id_tipo_organizacion']; ?>">
                                <input type="submit" name="accion" value="BorrarTipoOrganizacion" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta organización?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../../template/pie.php"); ?>
