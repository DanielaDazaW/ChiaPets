<?php
ob_start();
include("../../config/bd.php");

$txtIDZona = isset($_POST['txtIDZona']) ? $_POST['txtIDZona'] : "";
$txtZona = isset($_POST['txtZona']) ? $_POST['txtZona'] : "";
$accion = isset($_POST['accion']) ? $_POST['accion'] : "";

include("../../config/bd.php");

switch ($accion) {
    case "AgregarZona":
        $sentenciaSQL = $conexion->prepare("INSERT INTO zona (zona, estado) VALUES (:zona, 1)");
        $sentenciaSQL->bindParam(':zona', $txtZona);
        $sentenciaSQL->execute();
        header("Location: CatalogosUbicacion.php");
        exit;
        break;

    case "ModificarZona":
        $sentenciaSQL = $conexion->prepare("UPDATE zona SET zona = :zona WHERE id_zona = :id");
        $sentenciaSQL->bindParam(':zona', $txtZona);
        $sentenciaSQL->bindParam(':id', $txtIDZona);
        $sentenciaSQL->execute();
        header("Location: CatalogosUbicacion.php");
        exit;
        break;

    case "CancelarZona":
        header("Location: CatalogosUbicacion.php");
        exit;
        break;

    case "SeleccionarZona":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM zona WHERE id_zona = :id");
        $sentenciaSQL->bindParam(':id', $txtIDZona);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtZona = $registro['zona'];
        break;

    case "BorrarZona":
        $sentenciaSQL = $conexion->prepare("UPDATE zona SET estado = 0 WHERE id_zona = :id");
        $sentenciaSQL->bindParam(':id', $txtIDZona);
        $sentenciaSQL->execute();
        header("Location: CatalogosUbicacion.php");
        exit;
        break;
}

$sentenciaSQL = $conexion->prepare("SELECT * FROM zona WHERE estado = 1");
$sentenciaSQL->execute();
$listaZona = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

include("../../template/cabecera.php"); 
?>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Zona</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDZona" value="<?php echo $txtIDZona; ?>">
                    <div class="mb-3">
                        <label for="txtZona" class="form-label">Zona:</label>
                        <input type="text" required autocomplete="off" class="form-control" id="txtZona" name="txtZona" value="<?php echo $txtZona; ?>" placeholder="Nombre de la zona">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarZona" <?php echo ($accion == "SeleccionarZona") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarZona" <?php echo ($accion != "SeleccionarZona") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarZona" <?php echo ($accion != "SeleccionarZona") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
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
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaZona as $zona) { ?>
                    <tr>
                        <td><?php echo $zona['id_zona']; ?></td>
                        <td><?php echo htmlspecialchars($zona['zona']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDZona" value="<?php echo $zona['id_zona']; ?>">
                                <input type="submit" name="accion" value="SeleccionarZona" class="btn btn-primary btn-sm">
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDZona" value="<?php echo $zona['id_zona']; ?>">
                                <input type="submit" name="accion" value="BorrarZona" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta zona?');">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../../template/pie.php"); 
ob_end_flush();
?>
