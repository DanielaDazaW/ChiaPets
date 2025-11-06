<?php
include("../../config/bd.php");
ob_start();
// Variables catálogos
$txtIDProducto = $_POST['txtIDProducto'] ?? "";
$txtProducto = $_POST['txtProducto'] ?? "";

$txtIDTipoCampana = $_POST['txtIDTipoCampana'] ?? "";
$txtTipoCampana = $_POST['txtTipoCampana'] ?? "";

$txtIDTipoReporte = $_POST['txtIDTipoReporte'] ?? "";
$txtTipoReporte = $_POST['txtTipoReporte'] ?? "";

$txtIDTipoVacuna = $_POST['txtIDTipoVacuna'] ?? "";
$txtTipoVacuna = $_POST['txtTipoVacuna'] ?? "";

$txtIDTipoOrganizacion = $_POST['txtIDTipoOrganizacion'] ?? "";
$txtTipoOrganizacion = $_POST['txtTipoOrganizacion'] ?? "";

$accion = $_POST['accion'] ?? "";



// Manejo de acciones
switch ($accion) {
    // Producto
    case "AgregarProducto":
        $stmt = $conexion->prepare("INSERT INTO producto (producto, estado) VALUES (:producto, 1)");
        $stmt->bindParam(':producto', $txtProducto);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "ModificarProducto":
        $stmt = $conexion->prepare("UPDATE producto SET producto = :producto WHERE id_producto = :id");
        $stmt->bindParam(':producto', $txtProducto);
        $stmt->bindParam(':id', $txtIDProducto);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "CancelarProducto":
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "SeleccionarProducto":
        $stmt = $conexion->prepare("SELECT * FROM producto WHERE id_producto=:id");
        $stmt->bindParam(':id', $txtIDProducto);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtProducto = $registro['producto'];
        break;
    case "BorrarProducto":
        $stmt = $conexion->prepare("UPDATE producto SET estado = 0 WHERE id_producto=:id");
        $stmt->bindParam(':id', $txtIDProducto);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;

    // Tipo Campana
    case "AgregarTipoCampana":
        $stmt = $conexion->prepare("INSERT INTO tipo_campana (tipo_campana, estado) VALUES (:tipo_campana, 1)");
        $stmt->bindParam(':tipo_campana', $txtTipoCampana);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "ModificarTipoCampana":
        $stmt = $conexion->prepare("UPDATE tipo_campana SET tipo_campana = :tipo_campana WHERE id_tipo_campana = :id");
        $stmt->bindParam(':tipo_campana', $txtTipoCampana);
        $stmt->bindParam(':id', $txtIDTipoCampana);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "CancelarTipoCampana":
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "SeleccionarTipoCampana":
        $stmt = $conexion->prepare("SELECT * FROM tipo_campana WHERE id_tipo_campana = :id");
        $stmt->bindParam(':id', $txtIDTipoCampana);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtTipoCampana = $registro['tipo_campana'];
        break;
    case "BorrarTipoCampana":
        $stmt = $conexion->prepare("UPDATE tipo_campana SET estado = 0 WHERE id_tipo_campana = :id");
        $stmt->bindParam(':id', $txtIDTipoCampana);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;

    // Tipo Reporte
    case "AgregarTipoReporte":
        $stmt = $conexion->prepare("INSERT INTO tipo_reporte (tipo_reporte, estado) VALUES (:tipo_reporte, 1)");
        $stmt->bindParam(':tipo_reporte', $txtTipoReporte);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "ModificarTipoReporte":
        $stmt = $conexion->prepare("UPDATE tipo_reporte SET tipo_reporte = :tipo_reporte WHERE id_tipo_reporte = :id");
        $stmt->bindParam(':tipo_reporte', $txtTipoReporte);
        $stmt->bindParam(':id', $txtIDTipoReporte);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "CancelarTipoReporte":
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "SeleccionarTipoReporte":
        $stmt = $conexion->prepare("SELECT * FROM tipo_reporte WHERE id_tipo_reporte = :id");
        $stmt->bindParam(':id', $txtIDTipoReporte);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtTipoReporte = $registro['tipo_reporte'];
        break;
    case "BorrarTipoReporte":
        $stmt = $conexion->prepare("UPDATE tipo_reporte SET estado = 0 WHERE id_tipo_reporte = :id");
        $stmt->bindParam(':id', $txtIDTipoReporte);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;

    // Tipo Vacuna
    case "AgregarTipoVacuna":
        $stmt = $conexion->prepare("INSERT INTO tipo_vacuna (tipo_vacuna, estado) VALUES (:tipo_vacuna, 1)");
        $stmt->bindParam(':tipo_vacuna', $txtTipoVacuna);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "ModificarTipoVacuna":
        $stmt = $conexion->prepare("UPDATE tipo_vacuna SET tipo_vacuna = :tipo_vacuna WHERE id_tipo_vacuna = :id");
        $stmt->bindParam(':tipo_vacuna', $txtTipoVacuna);
        $stmt->bindParam(':id', $txtIDTipoVacuna);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "CancelarTipoVacuna":
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "SeleccionarTipoVacuna":
        $stmt = $conexion->prepare("SELECT * FROM tipo_vacuna WHERE id_tipo_vacuna = :id");
        $stmt->bindParam(':id', $txtIDTipoVacuna);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtTipoVacuna = $registro['tipo_vacuna'];
        break;
    case "BorrarTipoVacuna":
        $stmt = $conexion->prepare("UPDATE tipo_vacuna SET estado = 0 WHERE id_tipo_vacuna = :id");
        $stmt->bindParam(':id', $txtIDTipoVacuna);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;

    // Tipo Organización
    case "AgregarTipoOrganizacion":
        $stmt = $conexion->prepare("INSERT INTO tipo_organizacion (tipo_organizacion, estado) VALUES (:tipo_organizacion, 1)");
        $stmt->bindParam(':tipo_organizacion', $txtTipoOrganizacion);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "ModificarTipoOrganizacion":
        $stmt = $conexion->prepare("UPDATE tipo_organizacion SET tipo_organizacion = :tipo_organizacion WHERE id_tipo_organizacion = :id");
        $stmt->bindParam(':tipo_organizacion', $txtTipoOrganizacion);
        $stmt->bindParam(':id', $txtIDTipoOrganizacion);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "CancelarTipoOrganizacion":
        header("Location: CatalogosAdministrativos.php");
        exit;
    case "SeleccionarTipoOrganizacion":
        $stmt = $conexion->prepare("SELECT * FROM tipo_organizacion WHERE id_tipo_organizacion = :id");
        $stmt->bindParam(':id', $txtIDTipoOrganizacion);
        $stmt->execute();
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtTipoOrganizacion = $registro['tipo_organizacion'];
        break;
    case "BorrarTipoOrganizacion":
        $stmt = $conexion->prepare("UPDATE tipo_organizacion SET estado = 0 WHERE id_tipo_organizacion = :id");
        $stmt->bindParam(':id', $txtIDTipoOrganizacion);
        $stmt->execute();
        header("Location: CatalogosAdministrativos.php");
        exit;
}

// Consultar listas
$listaProducto = $conexion->query("SELECT * FROM producto WHERE estado = 1 ORDER BY producto")->fetchAll(PDO::FETCH_ASSOC);
$listaTipoCampana = $conexion->query("SELECT * FROM tipo_campana WHERE estado = 1 ORDER BY tipo_campana")->fetchAll(PDO::FETCH_ASSOC);
$listaTipoReporte = $conexion->query("SELECT * FROM tipo_reporte WHERE estado = 1 ORDER BY tipo_reporte")->fetchAll(PDO::FETCH_ASSOC);
$listaTipoVacuna = $conexion->query("SELECT * FROM tipo_vacuna WHERE estado = 1 ORDER BY tipo_vacuna")->fetchAll(PDO::FETCH_ASSOC);
$listaTipoOrganizacion = $conexion->query("SELECT * FROM tipo_organizacion WHERE estado = 1 ORDER BY tipo_organizacion")->fetchAll(PDO::FETCH_ASSOC);



 include("../../template/cabecera.php"); 
?>
<div class="container">
  <h1>Catálogos - Administrativos </h1>


  <!-- Formulario y listado Producto -->
  <div class="row mb-4">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Producto</div>
        <div class="card-body">
          <form method="POST" autocomplete="off">
            <input type="hidden" name="txtIDProducto" value="<?= htmlspecialchars($txtIDProducto) ?>">
            <div class="mb-3">
              <label for="txtProducto" class="form-label">Producto</label>
              <input type="text" name="txtProducto" id="txtProducto" class="form-control" required value="<?= htmlspecialchars($txtProducto) ?>">
            </div>
            <div class="btn-group">
              <button type="submit" class="btn btn-success" name="accion" value="AgregarProducto" <?= ($accion == "SeleccionarProducto") ? "disabled" : "" ?>>Agregar</button>
              <button type="submit" class="btn btn-warning" name="accion" value="ModificarProducto" <?= ($accion != "SeleccionarProducto") ? "disabled" : "" ?>>Modificar</button>
              <button type="submit" class="btn btn-info" name="accion" value="CancelarProducto" <?= ($accion != "SeleccionarProducto") ? "disabled" : "" ?>>Cancelar</button>
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
            <th>Producto</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listaProducto as $producto): ?>
          <tr>
            <td><?= $producto['id_producto'] ?></td>
            <td><?= htmlspecialchars($producto['producto']) ?></td>
            <td>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDProducto" value="<?= $producto['id_producto'] ?>">
                <button type="submit" name="accion" value="SeleccionarProducto" class="btn btn-primary btn-sm">Editar</button>
              </form>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDProducto" value="<?= $producto['id_producto'] ?>">
                <button type="submit" name="accion" value="BorrarProducto" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que desea desactivar este producto?')">Desactivar</button>
              </form>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>


  <!-- Formulario y listado Tipo Campana -->
  <div class="row mb-4">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Tipo Campana</div>
        <div class="card-body">
          <form method="POST" autocomplete="off">
            <input type="hidden" name="txtIDTipoCampana" value="<?= htmlspecialchars($txtIDTipoCampana) ?>">
            <div class="mb-3">
              <label for="txtTipoCampana" class="form-label">Tipo Campana</label>
              <input type="text" name="txtTipoCampana" id="txtTipoCampana" class="form-control" required value="<?= htmlspecialchars($txtTipoCampana) ?>">
            </div>
            <div class="btn-group">
              <button type="submit" name="accion" value="AgregarTipoCampana" class="btn btn-success" <?= ($accion == "SeleccionarTipoCampana") ? "disabled" : "" ?>>Agregar</button>
              <button type="submit" name="accion" value="ModificarTipoCampana" class="btn btn-warning" <?= ($accion != "SeleccionarTipoCampana") ? "disabled" : "" ?>>Modificar</button>
              <button type="submit" name="accion" value="CancelarTipoCampana" class="btn btn-info" <?= ($accion != "SeleccionarTipoCampana") ? "disabled" : "" ?>>Cancelar</button>
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
            <th>Tipo Campana</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listaTipoCampana as $campana): ?>
          <tr>
            <td><?= $campana['id_tipo_campana'] ?></td>
            <td><?= htmlspecialchars($campana['tipo_campana']) ?></td>
            <td>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoCampana" value="<?= $campana['id_tipo_campana'] ?>">
                <button type="submit" name="accion" value="SeleccionarTipoCampana" class="btn btn-primary btn-sm">Editar</button>
              </form>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoCampana" value="<?= $campana['id_tipo_campana'] ?>">
                <button type="submit" name="accion" value="BorrarTipoCampana" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta campaña?')">Desactivar</button>
              </form>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Formulario y listado Tipo Reporte -->
  <div class="row mb-4">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Tipo Reporte</div>
        <div class="card-body">
          <form method="POST" autocomplete="off">
            <input type="hidden" name="txtIDTipoReporte" value="<?= htmlspecialchars($txtIDTipoReporte) ?>">
            <div class="mb-3">
              <label for="txtTipoReporte" class="form-label">Tipo Reporte</label>
              <input type="text" name="txtTipoReporte" id="txtTipoReporte" class="form-control" required value="<?= htmlspecialchars($txtTipoReporte) ?>">
            </div>
            <div class="btn-group">
              <button type="submit" name="accion" value="AgregarTipoReporte" class="btn btn-success" <?= ($accion == "SeleccionarTipoReporte") ? "disabled" : "" ?>>Agregar</button>
              <button type="submit" name="accion" value="ModificarTipoReporte" class="btn btn-warning" <?= ($accion != "SeleccionarTipoReporte") ? "disabled" : "" ?>>Modificar</button>
              <button type="submit" name="accion" value="CancelarTipoReporte" class="btn btn-info" <?= ($accion != "SeleccionarTipoReporte") ? "disabled" : "" ?>>Cancelar</button>
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
          <?php foreach ($listaTipoReporte as $reporte): ?>
          <tr>
            <td><?= $reporte['id_tipo_reporte'] ?></td>
            <td><?= htmlspecialchars($reporte['tipo_reporte']) ?></td>
            <td>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoReporte" value="<?= $reporte['id_tipo_reporte'] ?>">
                <button type="submit" name="accion" value="SeleccionarTipoReporte" class="btn btn-primary btn-sm">Editar</button>
              </form>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoReporte" value="<?= $reporte['id_tipo_reporte'] ?>">
                <button type="submit" name="accion" value="BorrarTipoReporte" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar este reporte?')">Desactivar</button>
              </form>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Formulario y listado Tipo Vacuna -->
  <div class="row mb-4">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Tipo Vacuna</div>
        <div class="card-body">
          <form method="POST" autocomplete="off">
            <input type="hidden" name="txtIDTipoVacuna" value="<?= htmlspecialchars($txtIDTipoVacuna) ?>">
            <div class="mb-3">
              <label for="txtTipoVacuna" class="form-label">Tipo Vacuna</label>
              <input type="text" name="txtTipoVacuna" id="txtTipoVacuna" class="form-control" required value="<?= htmlspecialchars($txtTipoVacuna) ?>">
            </div>
            <div class="btn-group">
              <button type="submit" name="accion" value="AgregarTipoVacuna" class="btn btn-success" <?= ($accion == "SeleccionarTipoVacuna") ? "disabled" : "" ?>>Agregar</button>
              <button type="submit" name="accion" value="ModificarTipoVacuna" class="btn btn-warning" <?= ($accion != "SeleccionarTipoVacuna") ? "disabled" : "" ?>>Modificar</button>
              <button type="submit" name="accion" value="CancelarTipoVacuna" class="btn btn-info" <?= ($accion != "SeleccionarTipoVacuna") ? "disabled" : "" ?>>Cancelar</button>
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
          <?php foreach ($listaTipoVacuna as $vacuna): ?>
          <tr>
            <td><?= $vacuna['id_tipo_vacuna'] ?></td>
            <td><?= htmlspecialchars($vacuna['tipo_vacuna']) ?></td>
            <td>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoVacuna" value="<?= $vacuna['id_tipo_vacuna'] ?>">
                <button type="submit" name="accion" value="SeleccionarTipoVacuna" class="btn btn-primary btn-sm">Editar</button>
              </form>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoVacuna" value="<?= $vacuna['id_tipo_vacuna'] ?>">
                <button type="submit" name="accion" value="BorrarTipoVacuna" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta vacuna?')">Desactivar</button>
              </form>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Formulario y listado Tipo Organización -->
  <div class="row mb-4">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Tipo Organización</div>
        <div class="card-body">
          <form method="POST" autocomplete="off">
            <input type="hidden" name="txtIDTipoOrganizacion" value="<?= htmlspecialchars($txtIDTipoOrganizacion) ?>">
            <div class="mb-3">
              <label for="txtTipoOrganizacion" class="form-label">Tipo Organización</label>
              <input type="text" name="txtTipoOrganizacion" id="txtTipoOrganizacion" class="form-control" required value="<?= htmlspecialchars($txtTipoOrganizacion) ?>">
            </div>
            <div class="btn-group">
              <button type="submit" name="accion" value="AgregarTipoOrganizacion" class="btn btn-success" <?= ($accion == "SeleccionarTipoOrganizacion") ? "disabled" : "" ?>>Agregar</button>
              <button type="submit" name="accion" value="ModificarTipoOrganizacion" class="btn btn-warning" <?= ($accion != "SeleccionarTipoOrganizacion") ? "disabled" : "" ?>>Modificar</button>
              <button type="submit" name="accion" value="CancelarTipoOrganizacion" class="btn btn-info" <?= ($accion != "SeleccionarTipoOrganizacion") ? "disabled" : "" ?>>Cancelar</button>
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
          <?php foreach ($listaTipoOrganizacion as $org): ?>
          <tr>
            <td><?= $org['id_tipo_organizacion'] ?></td>
            <td><?= htmlspecialchars($org['tipo_organizacion']) ?></td>
            <td>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoOrganizacion" value="<?= $org['id_tipo_organizacion'] ?>">
                <button type="submit" name="accion" value="SeleccionarTipoOrganizacion" class="btn btn-primary btn-sm">Editar</button>
              </form>
              <form method="POST" style="display:inline-block;">
                <input type="hidden" name="txtIDTipoOrganizacion" value="<?= $org['id_tipo_organizacion'] ?>">
                <button type="submit" name="accion" value="BorrarTipoOrganizacion" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta organización?')">Desactivar</button>
              </form>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("../../template/pie.php"); 
ob_end_flush();
?>
