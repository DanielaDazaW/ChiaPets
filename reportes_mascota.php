<?php
include("template/cabecera.php");
include("administrador/config/bd.php");

// SEGURIDAD: Solo usuario logueado
$idPersonaSesion = $_SESSION['usuario_id_persona'] ?? null;
if (!$idPersonaSesion) {
    header("Location: ../login.php");
    exit();
}

// Listas para selects en el reporte
$listaTiposReporte = $conexion->query("SELECT id_tipo_reporte, tipo_reporte FROM tipo_reporte WHERE 1")->fetchAll(PDO::FETCH_ASSOC);
$listaMascotasUsuario = $conexion->query("
    SELECT id_mascota, nombre 
    FROM mascotas 
    WHERE estado=1 AND id_persona = $idPersonaSesion
    ORDER BY nombre
")->fetchAll(PDO::FETCH_ASSOC);

// ----- CRUD REPORTE -----
$idReporte = $_POST['id_reporte'] ?? '';
$idMascota = $_POST['id_mascota'] ?? '';
$idTipoReporte = $_POST['id_tipo_reporte'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$ubicacion = $_POST['ubicacion'] ?? '';
$accionReporte = $_POST['accionReporte'] ?? "";

switch ($accionReporte) {
    case 'Agregar':
        // Solo puede crear reporte de mascota propia
        $stmtMascota = $conexion->prepare("SELECT id_mascota FROM mascotas WHERE id_mascota=? AND id_persona=? AND estado=1");
        $stmtMascota->execute([$idMascota, $idPersonaSesion]);
        if ($stmtMascota->fetch()) {
            $fechaReporte = date('Y-m-d');
            $idEstadoReporte = 1;
            $estado = 1;
            $stmt = $conexion->prepare("INSERT INTO reportes (id_mascota, id_tipo_reporte, descripcion, ubicacion, id_estado_reporte, fecha_reporte, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$idMascota, $idTipoReporte, $descripcion, $ubicacion, $idEstadoReporte, $fechaReporte, $estado]);
        }
        header("Location: reporte_mascota.php?add=ok");
        exit;
    case 'Modificar':
        // Solo puede modificar reporte si es de mascota propia
        $stmt = $conexion->prepare("
            UPDATE reportes SET id_tipo_reporte=:tipo, descripcion=:desc, ubicacion=:ubi
            WHERE id_reporte=:id AND id_mascota IN (
                SELECT id_mascota FROM mascotas WHERE id_persona=:persona AND estado=1
            )
        ");
        $stmt->bindParam(':tipo', $idTipoReporte);
        $stmt->bindParam(':desc', $descripcion);
        $stmt->bindParam(':ubi', $ubicacion);
        $stmt->bindParam(':id', $idReporte);
        $stmt->bindParam(':persona', $idPersonaSesion);
        $stmt->execute();
        header("Location: reporte_mascota.php?mod=ok");
        exit;
    case 'Seleccionar':
        // Solo puede seleccionar reporte propio para editar
        $stmt = $conexion->prepare("
            SELECT * FROM reportes WHERE id_reporte=:id AND id_mascota IN (
                SELECT id_mascota FROM mascotas WHERE id_persona=:persona AND estado=1
            )
        ");
        $stmt->bindParam(':id', $idReporte);
        $stmt->bindParam(':persona', $idPersonaSesion);
        $stmt->execute();
        $reporteSel = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($reporteSel) {
            $idMascota = $reporteSel['id_mascota'];
            $idTipoReporte = $reporteSel['id_tipo_reporte'];
            $descripcion = $reporteSel['descripcion'];
            $ubicacion = $reporteSel['ubicacion'];
        }
        break;
    case 'Borrar':
        // Solo puede borrar reporte propio
        $stmt = $conexion->prepare("
            UPDATE reportes SET estado=0 WHERE id_reporte=:id AND id_mascota IN (
                SELECT id_mascota FROM mascotas WHERE id_persona=:persona
            )
        ");
        $stmt->bindParam(':id', $idReporte);
        $stmt->bindParam(':persona', $idPersonaSesion);
        $stmt->execute();
        header("Location: reporte_mascota.php?del=ok");
        exit;
}

// Listar solo reportes de mascotas propias
$stmt = $conexion->prepare("
    SELECT r.*, m.nombre AS mascota_nombre, tr.tipo_reporte AS tipo
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
    <!-- TEXTO EXPLICATIVO -->
    <div class="alert alert-info mt-4 mb-3">
        <strong>¿Para qué sirven los reportes?</strong><br>
        Esta página te permite registrar y consultar reportes relacionados con tus mascotas. 
        Aquí podrás crear registros sobre situaciones como extravíos, accidentes, adopciones, cambios de ubicación o cualquier otro evento relevante. 
        Cada reporte queda asociado a tu mascota y te ayudará a llevar un historial organizado y compartir la información cuando lo necesites.
    </div>
    <h2>Generar/Editar Reporte de Mascota</h2>
    <form method="post">
        <input type="hidden" name="id_reporte" value="<?= htmlspecialchars($idReporte) ?>">
        <div class="mb-3">
            <label for="id_mascota" class="form-label">Mascota</label>
            <select name="id_mascota" id="id_mascota" class="form-select" required <?= $accionReporte == 'Seleccionar' ? 'disabled' : '' ?>>
                <option value="">Seleccione</option>
                <?php foreach ($listaMascotasUsuario as $mascota): ?>
                    <option value="<?= $mascota['id_mascota'] ?>" <?= $mascota['id_mascota'] == $idMascota ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mascota['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción del reporte</label>
            <textarea name="descripcion" id="descripcion" class="form-control" maxlength="300" required><?= htmlspecialchars($descripcion) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" maxlength="255" value="<?= htmlspecialchars($ubicacion) ?>" required>
        </div>
        <div class="mb-3">
            <label for="id_tipo_reporte" class="form-label">Tipo de reporte</label>
            <select name="id_tipo_reporte" id="id_tipo_reporte" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaTiposReporte as $tr): ?>
                    <option value="<?= $tr['id_tipo_reporte'] ?>" <?= $tr['id_tipo_reporte'] == $idTipoReporte ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tr['tipo_reporte']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="accionReporte" value="<?= $accionReporte == 'Seleccionar' ? 'Modificar' : 'Agregar' ?>" class="btn btn-primary">
            <?= $accionReporte == 'Seleccionar' ? 'Modificar' : 'Agregar' ?> Reporte
        </button>
        <?php if ($accionReporte == 'Seleccionar'): ?>
            <button type="submit" name="accionReporte" value="Cancelar" class="btn btn-info">Cancelar</button>
        <?php endif; ?>
    </form>
    <hr>
    <h3>Reportes de tus mascotas</h3>
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
                        <input type="hidden" name="id_reporte" value="<?= $r['id_reporte'] ?>">
                        <button type="submit" name="accionReporte" value="Seleccionar" class="btn btn-info btn-sm">Editar</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_reporte" value="<?= $r['id_reporte'] ?>">
                        <button type="submit" name="accionReporte" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php include("template/pie.php"); ?>
