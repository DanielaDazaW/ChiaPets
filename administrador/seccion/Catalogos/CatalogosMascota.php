<?php 
include("../../config/bd.php");
ob_start();

// Variables de catalogo mascotas
$txtIDColor = isset($_POST['txtIDColor']) ? $_POST['txtIDColor'] : "";
$txtColor = isset($_POST['txtColor']) ? $_POST['txtColor'] : "";

$txtIDEspecie = isset($_POST['txtIDEspecie']) ? $_POST['txtIDEspecie'] : "";
$txtEspecie = isset($_POST['txtEspecie']) ? $_POST['txtEspecie'] : "";

$txtIDRaza = isset($_POST['txtIDRaza']) ? $_POST['txtIDRaza'] : "";
$txtRaza = isset($_POST['txtRaza']) ? $_POST['txtRaza'] : "";

$txtIDSexo = isset($_POST['txtIDSexo']) ? $_POST['txtIDSexo'] : "";
$txtSexo = isset($_POST['txtSexo']) ? $_POST['txtSexo'] : "";

$txtIDTamano = isset($_POST['txtIDTamano']) ? $_POST['txtIDTamano'] : "";
$txtTamano = isset($_POST['txtTamano']) ? $_POST['txtTamano'] : "";

$accion = isset($_POST['accion']) ? $_POST['accion'] : "";



// Acciones CRUD
switch ($accion) {
    // Color
    case "AgregarColor":
        $sentenciaSQL = $conexion->prepare("INSERT INTO color (color, estado) VALUES (:color, 1)");
        $sentenciaSQL->bindParam(':color', $txtColor);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "ModificarColor":
        $sentenciaSQL = $conexion->prepare("UPDATE color SET color = :color WHERE id_color = :id");
        $sentenciaSQL->bindParam(':color', $txtColor);
        $sentenciaSQL->bindParam(':id', $txtIDColor);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "CancelarColor":
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "SeleccionarColor":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM color WHERE id_color = :id");
        $sentenciaSQL->bindParam(':id', $txtIDColor);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtColor = $registro['color'];
        break;
    case "BorrarColor":
        $sentenciaSQL = $conexion->prepare("UPDATE color SET estado = 0 WHERE id_color = :id");
        $sentenciaSQL->bindParam(':id', $txtIDColor);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;

    // Especie
    case "AgregarEspecie":
        $sentenciaSQL = $conexion->prepare("INSERT INTO especie (tipo_especie, estado) VALUES (:tipo_especie, 1)");
        $sentenciaSQL->bindParam(':tipo_especie', $txtEspecie);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "ModificarEspecie":
        $sentenciaSQL = $conexion->prepare("UPDATE especie SET tipo_especie = :tipo_especie WHERE id_especie = :id");
        $sentenciaSQL->bindParam(':tipo_especie', $txtEspecie);
        $sentenciaSQL->bindParam(':id', $txtIDEspecie);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "CancelarEspecie":
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "SeleccionarEspecie":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM especie WHERE id_especie = :id");
        $sentenciaSQL->bindParam(':id', $txtIDEspecie);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtEspecie = $registro['tipo_especie'];
        break;
    case "BorrarEspecie":
        $sentenciaSQL = $conexion->prepare("UPDATE especie SET estado = 0 WHERE id_especie = :id");
        $sentenciaSQL->bindParam(':id', $txtIDEspecie);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;

    // Raza
    case "AgregarRaza":
        $sentenciaSQL = $conexion->prepare("INSERT INTO raza (tipo_raza, estado) VALUES (:tipo_raza, 1)");
        $sentenciaSQL->bindParam(':tipo_raza', $txtRaza);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "ModificarRaza":
        $sentenciaSQL = $conexion->prepare("UPDATE raza SET tipo_raza = :tipo_raza WHERE id_raza = :id");
        $sentenciaSQL->bindParam(':tipo_raza', $txtRaza);
        $sentenciaSQL->bindParam(':id', $txtIDRaza);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "CancelarRaza":
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "SeleccionarRaza":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM raza WHERE id_raza = :id");
        $sentenciaSQL->bindParam(':id', $txtIDRaza);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtRaza = $registro['tipo_raza'];
        break;
    case "BorrarRaza":
        $sentenciaSQL = $conexion->prepare("UPDATE raza SET estado = 0 WHERE id_raza = :id");
        $sentenciaSQL->bindParam(':id', $txtIDRaza);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;

    // Sexo
    case "AgregarSexo":
        $sentenciaSQL = $conexion->prepare("INSERT INTO sexo (tipo_sexo, estado) VALUES (:tipo_sexo, 1)");
        $sentenciaSQL->bindParam(':tipo_sexo', $txtSexo);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "ModificarSexo":
        $sentenciaSQL = $conexion->prepare("UPDATE sexo SET tipo_sexo = :tipo_sexo WHERE id_sexo = :id");
        $sentenciaSQL->bindParam(':tipo_sexo', $txtSexo);
        $sentenciaSQL->bindParam(':id', $txtIDSexo);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "CancelarSexo":
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "SeleccionarSexo":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM sexo WHERE id_sexo = :id");
        $sentenciaSQL->bindParam(':id', $txtIDSexo);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtSexo = $registro['tipo_sexo'];
        break;
    case "BorrarSexo":
        $sentenciaSQL = $conexion->prepare("UPDATE sexo SET estado = 0 WHERE id_sexo = :id");
        $sentenciaSQL->bindParam(':id', $txtIDSexo);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;

    // Tamaño
    case "AgregarTamano":
        $sentenciaSQL = $conexion->prepare("INSERT INTO tamano (tamano, estado) VALUES (:tamano, 1)");
        $sentenciaSQL->bindParam(':tamano', $txtTamano);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "ModificarTamano":
        $sentenciaSQL = $conexion->prepare("UPDATE tamano SET tamano = :tamano WHERE id_tamano = :id");
        $sentenciaSQL->bindParam(':tamano', $txtTamano);
        $sentenciaSQL->bindParam(':id', $txtIDTamano);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "CancelarTamano":
        header("Location: CatalogosMascota.php");
        exit;
        break;
    case "SeleccionarTamano":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM tamano WHERE id_tamano = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTamano);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtTamano = $registro['tamano'];
        break;
    case "BorrarTamano":
        $sentenciaSQL = $conexion->prepare("UPDATE tamano SET estado = 0 WHERE id_tamano = :id");
        $sentenciaSQL->bindParam(':id', $txtIDTamano);
        $sentenciaSQL->execute();
        header("Location: CatalogosMascota.php");
        exit;
        break;
}

// Consultas para mostrar solo registros activos
$sentenciaSQL = $conexion->prepare("SELECT * FROM color WHERE estado = 1");
$sentenciaSQL->execute();
$listaColor = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM especie WHERE estado = 1");
$sentenciaSQL->execute();
$listaEspecie = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM raza WHERE estado = 1");
$sentenciaSQL->execute();
$listaRaza = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM sexo WHERE estado = 1");
$sentenciaSQL->execute();
$listaSexo = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$sentenciaSQL = $conexion->prepare("SELECT * FROM tamano WHERE estado = 1");
$sentenciaSQL->execute();
$listaTamano = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
include("../../template/cabecera.php"); 
?>
<!-- Color -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Color</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDColor" value="<?php echo $txtIDColor; ?>">
                    <div class="mb-3">
                        <label for="txtColor" class="form-label">Color:</label>
                        <input type="text" class="form-control" id="txtColor" name="txtColor" required autocomplete="off" placeholder="Color" value="<?php echo $txtColor; ?>">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarColor" <?php echo ($accion == "SeleccionarColor") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarColor" <?php echo ($accion != "SeleccionarColor") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarColor" <?php echo ($accion != "SeleccionarColor") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Color</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaColor as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_color']; ?></td>
                        <td><?php echo htmlspecialchars($item['color']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDColor" value="<?php echo $item['id_color']; ?>">
                                <input type="submit" name="accion" value="SeleccionarColor" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDColor" value="<?php echo $item['id_color']; ?>">
                                <input type="submit" name="accion" value="BorrarColor" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este color?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Especie -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Especie</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDEspecie" value="<?php echo $txtIDEspecie; ?>">
                    <div class="mb-3">
                        <label for="txtEspecie" class="form-label">Tipo de Especie:</label>
                        <input type="text" class="form-control" id="txtEspecie" name="txtEspecie" required autocomplete="off" placeholder="Tipo de especie" value="<?php echo $txtEspecie; ?>">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarEspecie" <?php echo ($accion == "SeleccionarEspecie") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarEspecie" <?php echo ($accion != "SeleccionarEspecie") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarEspecie" <?php echo ($accion != "SeleccionarEspecie") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo de Especie</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaEspecie as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_especie']; ?></td>
                        <td><?php echo htmlspecialchars($item['tipo_especie']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDEspecie" value="<?php echo $item['id_especie']; ?>">
                                <input type="submit" name="accion" value="SeleccionarEspecie" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDEspecie" value="<?php echo $item['id_especie']; ?>">
                                <input type="submit" name="accion" value="BorrarEspecie" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta especie?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Raza -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Raza</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDRaza" value="<?php echo $txtIDRaza; ?>">
                    <div class="mb-3">
                        <label for="txtRaza" class="form-label">Tipo de Raza:</label>
                        <input type="text" class="form-control" id="txtRaza" name="txtRaza" required autocomplete="off" placeholder="Tipo de raza" value="<?php echo $txtRaza; ?>">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarRaza" <?php echo ($accion == "SeleccionarRaza") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarRaza" <?php echo ($accion != "SeleccionarRaza") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarRaza" <?php echo ($accion != "SeleccionarRaza") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo de Raza</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaRaza as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_raza']; ?></td>
                        <td><?php echo htmlspecialchars($item['tipo_raza']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDRaza" value="<?php echo $item['id_raza']; ?>">
                                <input type="submit" name="accion" value="SeleccionarRaza" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDRaza" value="<?php echo $item['id_raza']; ?>">
                                <input type="submit" name="accion" value="BorrarRaza" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta raza?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Sexo -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Sexo</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDSexo" value="<?php echo $txtIDSexo; ?>">
                    <div class="mb-3">
                        <label for="txtSexo" class="form-label">Tipo de Sexo:</label>
                        <input type="text" class="form-control" id="txtSexo" name="txtSexo" required autocomplete="off" placeholder="Tipo de sexo" value="<?php echo $txtSexo; ?>">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarSexo" <?php echo ($accion == "SeleccionarSexo") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarSexo" <?php echo ($accion != "SeleccionarSexo") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarSexo" <?php echo ($accion != "SeleccionarSexo") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo de Sexo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaSexo as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_sexo']; ?></td>
                        <td><?php echo htmlspecialchars($item['tipo_sexo']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDSexo" value="<?php echo $item['id_sexo']; ?>">
                                <input type="submit" name="accion" value="SeleccionarSexo" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDSexo" value="<?php echo $item['id_sexo']; ?>">
                                <input type="submit" name="accion" value="BorrarSexo" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este sexo?');" />
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tamaño -->
<div class="row mb-5">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Datos de Tamaño</div>
            <div class="card-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" name="txtIDTamano" value="<?php echo $txtIDTamano; ?>">
                    <div class="mb-3">
                        <label for="txtTamano" class="form-label">Tamaño:</label>
                        <input type="text" class="form-control" id="txtTamano" name="txtTamano" required autocomplete="off" placeholder="Tamaño" value="<?php echo $txtTamano; ?>">
                    </div>
                    <div class="btn-group" role="group">
                        <button type="submit" name="accion" value="AgregarTamano" <?php echo ($accion == "SeleccionarTamano") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" value="ModificarTamano" <?php echo ($accion != "SeleccionarTamano") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" value="CancelarTamano" <?php echo ($accion != "SeleccionarTamano") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tamaño</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaTamano as $item) { ?>
                    <tr>
                        <td><?php echo $item['id_tamano']; ?></td>
                        <td><?php echo htmlspecialchars($item['tamano']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTamano" value="<?php echo $item['id_tamano']; ?>">
                                <input type="submit" name="accion" value="SeleccionarTamano" class="btn btn-primary btn-sm" />
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtIDTamano" value="<?php echo $item['id_tamano']; ?>">
                                <input type="submit" name="accion" value="BorrarTamano" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este tamaño?');" />
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
