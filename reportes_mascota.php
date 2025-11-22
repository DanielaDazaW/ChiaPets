<?php
include("template/cabecera.php");
include("administrador/config/bd.php");

// -- SEGURIDAD: Solo usuario logueado
$idPersonaSesion = $_SESSION['usuario_id_persona'] ?? null;
if (!$idPersonaSesion) {
    header("Location: ../login.php");
    exit();
}

// ----- VARIABLES REPORTES -----
$txtIDReporte = $_POST['txtIDReporte'] ?? "";
$txtIdMascota = $_POST['txtIdMascota'] ?? "";
$txtIdTipoReporte = $_POST['txtIdTipoReporte'] ?? "";
$txtDescripcion = $_POST['txtDescripcion'] ?? "";
$txtUbicacion = $_POST['txtUbicacion'] ?? "";
$accionReporte = $_POST['accionReporte'] ?? "";

// -- Listas para selects (solo de usuario)
$listaMascotasUsuario = $conexion->query("
    SELECT id_mascota, nombre 
    FROM mascotas 
    WHERE estado=1 AND id_persona = $idPersonaSesion
    ORDER BY nombre
")->fetchAll(PDO::FETCH_ASSOC);

$listaTiposReporte = $conexion->query("SELECT id_tipo_reporte, tipo_reporte FROM tipo_reporte WHERE 1")->fetchAll(PDO::FETCH_ASSOC);

// --- CRUD REPORTES ---
switch ($accionReporte) {
    case 'Agregar':
        // Solo puede crear reporte de mascota propia
        $stmtMascota = $conexion->prepare("SELECT id_mascota FROM mascotas WHERE id_mascota=? AND id_persona=? AND estado=1");
        $stmtMascota->execute([$txtIdMascota, $idPersonaSesion]);
        if ($stmtMascota->fetch()) {
            $fechaReporte = date('Y-m-d');
            $idEstadoReporte = 3; // SIEMPRE 3 (automático)
            $estado = 1;          // SIEMPRE 1 (automático)
            $stmt = $conexion->prepare("INSERT INTO reportes (id_mascota, id_tipo_reporte, descripcion, ubicacion, id_estado_reporte, fecha_reporte, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$txtIdMascota, $txtIdTipoReporte, $txtDescripcion, $txtUbicacion, $idEstadoReporte, $fechaReporte, $estado]);
        }
        header("Location: reportes_mascota.php");
        exit;
    case 'Modificar':
        // Solo puede modificar reporte si es de mascota propia
        $stmt = $conexion->prepare("
            UPDATE reportes SET id_tipo_reporte=:tipo, descripcion=:desc, ubicacion=:ubi
            WHERE id_reporte=:id AND id_mascota IN (
                SELECT id_mascota FROM mascotas WHERE id_persona=:persona AND estado=1
            )
        ");
        $stmt->bindParam(':tipo', $txtIdTipoReporte);
        $stmt->bindParam(':desc', $txtDescripcion);
        $stmt->bindParam(':ubi', $txtUbicacion);
        $stmt->bindParam(':id', $txtIDReporte);
        $stmt->bindParam(':persona', $idPersonaSesion);
        $stmt->execute();
        header("Location: reportes_mascota.php");
        exit;
    case 'Seleccionar':
        // Solo puede seleccionar reporte propio para editar
        $stmt = $conexion->prepare("
            SELECT * FROM reportes WHERE id_reporte=:id AND id_mascota IN (
                SELECT id_mascota FROM mascotas WHERE id_persona=:persona AND estado=1
            )
        ");
        $stmt->bindParam(':id', $txtIDReporte);
        $stmt->bindParam(':persona', $idPersonaSesion);
        $stmt->execute();
        $reporteSel = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($reporteSel) {
            $txtIdMascota = $reporteSel['id_mascota'];
            $txtIdTipoReporte = $reporteSel['id_tipo_reporte'];
            $txtDescripcion = $reporteSel['descripcion'];
            $txtUbicacion = $reporteSel['ubicacion'];
        }
        break;
    case 'Borrar':
        // Solo puede borrar reporte propio
        $stmt = $conexion->prepare("
            UPDATE reportes SET estado=0 WHERE id_reporte=:id AND id_mascota IN (
                SELECT id_mascota FROM mascotas WHERE id_persona=:persona
            )
        ");
        $stmt->bindParam(':id', $txtIDReporte);
        $stmt->bindParam(':persona', $idPersonaSesion);
        $stmt->execute();
        header("Location: reportes_mascota.php");
        exit;
    case 'Cancelar':
        header("Location: reportes_mascota.php");
        exit;
}

// --- LISTADO DE REPORTES DEL USUARIO ---
$stmt = $conexion->prepare("
    SELECT r.*, m.nombre AS mascota_nombre, tr.tipo_reporte AS tipo, r.descripcion, r.ubicacion, r.fecha_reporte
    FROM reportes r
    LEFT JOIN mascotas m ON r.id_mascota = m.id_mascota
    LEFT JOIN tipo_reporte tr ON r.id_tipo_reporte = tr.id_tipo_reporte
    WHERE r.estado=1 AND m.id_persona=?
    ORDER BY r.fecha_reporte DESC
");
$stmt->execute([$idPersonaSesion]);
$listaReportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Reportes de mis Mascotas</h1>
    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIDReporte" value="<?= htmlspecialchars($txtIDReporte) ?>">
        <div class="mb-3">
            <label for="txtIdMascota" class="form-label">Mascota</label>
            <select name="txtIdMascota" id="txtIdMascota" class="form-select" required <?= $accionReporte == 'Seleccionar' ? 'disabled' : '' ?>>
                <option value="">Seleccione</option>
                <?php foreach ($listaMascotasUsuario as $mascota): ?>
                    <option value="<?= $mascota['id_mascota'] ?>" <?= $mascota['id_mascota'] == $txtIdMascota ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mascota['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtDescripcion" class="form-label">Descripción del reporte</label>
            <textarea name="txtDescripcion" id="txtDescripcion" class="form-control" maxlength="300" required><?= htmlspecialchars($txtDescripcion) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="txtUbicacion" class="form-label">Ubicación</label>
            <input type="text" name="txtUbicacion" id="txtUbicacion" class="form-control" maxlength="255" value="<?= htmlspecialchars($txtUbicacion) ?>" required>
        </div>
        <div class="mb-3">
            <label for="txtIdTipoReporte" class="form-label">Tipo de reporte</label>
            <select name="txtIdTipoReporte" id="txtIdTipoReporte" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaTiposReporte as $tr): ?>
                    <option value="<?= $tr['id_tipo_reporte'] ?>" <?= $tr['id_tipo_reporte'] == $txtIdTipoReporte ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tr['tipo_reporte']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <button type="submit" name="accionReporte" value="<?= $accionReporte == 'Seleccionar' ? 'Modificar' : 'Agregar' ?>" class="btn btn-<?= $accionReporte == 'Seleccionar' ? 'warning' : 'success' ?>">
                <?= $accionReporte == 'Seleccionar' ? 'Modificar' : 'Agregar' ?> Reporte
            </button>
            <?php if ($accionReporte == 'Seleccionar'): ?>
            <button type="submit" name="accionReporte" value="Cancelar" class="btn btn-info">Cancelar</button>
            <?php endif; ?>
        </div>
    </form>
    <hr>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mascota</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Ubicación</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaReportes as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id_reporte']) ?></td>
                <td><?= htmlspecialchars($r['mascota_nombre']) ?></td>
                <td><?= htmlspecialchars($r['tipo']) ?></td>
                <td><?= htmlspecialchars($r['descripcion']) ?></td>
                <td><?= htmlspecialchars($r['ubicacion']) ?></td>
                <td><?= htmlspecialchars($r['fecha_reporte']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="txtIDReporte" value="<?= $r['id_reporte'] ?>">
                        <button type="submit" name="accionReporte" value="Seleccionar" class="btn btn-info btn-sm">Editar</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="txtIDReporte" value="<?= $r['id_reporte'] ?>">
                        <button type="submit" name="accionReporte" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                    </form>
                    <form action="descargar_reporte.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $r['id_reporte'] ?>">
                        <button type="submit" class="btn btn-success btn-sm">Descargar PDF de reporte</button>
                    </form>


                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include("template/pie.php"); ?>
