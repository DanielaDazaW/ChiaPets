<?php include("../../template/cabecera.php"); ?>

<?php
$txtID = isset($_POST['txtID']) ? $_POST['txtID'] : "";
$txtTipoCampana = isset($_POST['txtTipoCampana']) ? $_POST['txtTipoCampana'] : "";
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

include("../../config/bd.php");

switch ($accion) {
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tipo_campana (tipo_campana, estado) VALUES (:tipo, 1)");
        $sentenciaSQL->bindParam(':tipo', $txtTipoCampana);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_campana SET tipo_campana = :tipo WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':tipo', $txtTipoCampana);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "Cancelar":
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;

    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_campana WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $TipoCampana = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTipoCampana = $TipoCampana['tipo_campana'];
        break;

    case "Borrar":
        $sentenciaSQL = $conexion->prepare("UPDATE tipo_campana SET estado = 0 WHERE id_tipo_campana = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
        break;
}

// Solo muestra registros con estado=1
$sentenciaSQL = $conexion->prepare("SELECT * FROM tipo_campana WHERE estado = 1");
$sentenciaSQL->execute();
$listaTipoCampana = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tipo Campaña</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label for="txtID" class="form-label">ID:</label>
                        <input type="text" readonly class="form-control" id="txtID" name="txtID" value="<?php echo $txtID; ?>" placeholder="ID">
                    </div>
                    <div class="mb-3">
                        <label for="txtTipoCampana" class="form-label">Tipo de Campaña:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtTipoCampana" name="txtTipoCampana" value="<?php echo $txtTipoCampana; ?>" placeholder="Tipo de campaña">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="Agregar" <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="Modificar" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="Cancelar" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
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
                <?php foreach ($listaTipoCampana as $TipoCampana) { ?>
                    <tr>
                        <td><?php echo $TipoCampana['id_tipo_campana']; ?></td>
                        <td><?php echo htmlspecialchars($TipoCampana['tipo_campana']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtID" value="<?php echo $TipoCampana['id_tipo_campana']; ?>">
                                <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary btn-sm">
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtID" value="<?php echo $TipoCampana['id_tipo_campana']; ?>">
                                <input type="submit" name="accion" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta campaña?');">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../../template/pie.php"); ?>