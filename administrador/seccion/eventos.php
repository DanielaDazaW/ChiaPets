<?php
// Iniciar almacenamiento de salida para evitar errores con header()
ob_start();

include("../config/bd.php");

// Variables formulario campañas
$txtIDCampana = $_POST['txtIDCampana'] ?? "";
$txtTitulo = $_POST['txtTitulo'] ?? "";
$txtDescripcion = $_POST['txtDescripcion'] ?? "";
$txtIDTipoCampana = $_POST['txtIDTipoCampana'] ?? "";
$txtFechaInicio = $_POST['txtFechaInicio'] ?? "";
$txtFechaFin = $_POST['txtFechaFin'] ?? "";
$txtLugar = $_POST['txtLugar'] ?? "";
$txtIDOrganizacion = $_POST['txtIDOrganizacion'] ?? "";
$txtEstadoCampana = $_POST['txtEstadoCampana'] ?? 1;
$accionCampana = $_POST['accionCampana'] ?? "";

// Variables formulario participaciones
$txtIDParticipacion = $_POST['txtIDParticipacion'] ?? "";
$txtIDCampanaParticipacion = $_POST['txtIDCampanaParticipacion'] ?? "";
$txtIDMascotaParticipacion = $_POST['txtIDMascotaParticipacion'] ?? "";
$txtIDZonaParticipacion = $_POST['txtIDZonaParticipacion'] ?? "";
$txtFechaParticipacion = $_POST['txtFechaParticipacion'] ?? "";
$accionParticipacion = $_POST['accionParticipacion'] ?? "";

// Procesar acciones con redirección antes de incluir cabecera
switch($accionCampana) {
    case "AgregarCampana":
        $stmt = $conexion->prepare("INSERT INTO campanias (titulo, descripcion, id_tipo_campana, fecha_inicio, fecha_fin, lugar, id_organizacion, estado) VALUES (:titulo, :descripcion, :id_tipo_campana, :fecha_inicio, :fecha_fin, :lugar, :id_organizacion, 1)");
        $stmt->bindParam(':titulo', $txtTitulo);
        $stmt->bindParam(':descripcion', $txtDescripcion);
        $stmt->bindParam(':id_tipo_campana', $txtIDTipoCampana);
        $stmt->bindParam(':fecha_inicio', $txtFechaInicio);
        $stmt->bindParam(':fecha_fin', $txtFechaFin);
        $stmt->bindParam(':lugar', $txtLugar);
        $stmt->bindParam(':id_organizacion', $txtIDOrganizacion);
        $stmt->execute();
        header("Location: eventos.php");
        ob_end_flush();
        exit;
    case "ModificarCampana":
        $stmt = $conexion->prepare("UPDATE campanias SET titulo=:titulo, descripcion=:descripcion, id_tipo_campana=:id_tipo_campana, fecha_inicio=:fecha_inicio, fecha_fin=:fecha_fin, lugar=:lugar, id_organizacion=:id_organizacion WHERE id_campana=:id");
        $stmt->bindParam(':titulo', $txtTitulo);
        $stmt->bindParam(':descripcion', $txtDescripcion);
        $stmt->bindParam(':id_tipo_campana', $txtIDTipoCampana);
        $stmt->bindParam(':fecha_inicio', $txtFechaInicio);
        $stmt->bindParam(':fecha_fin', $txtFechaFin);
        $stmt->bindParam(':lugar', $txtLugar);
        $stmt->bindParam(':id_organizacion', $txtIDOrganizacion);
        $stmt->bindParam(':id', $txtIDCampana);
        $stmt->execute();
        header("Location: eventos.php");
        ob_end_flush();
        exit;
    case "CancelarCampana":
        header("Location: eventos.php");
        ob_end_flush();
        exit;
    case "BorrarCampana":
        $stmt = $conexion->prepare("UPDATE campanias SET estado=0 WHERE id_campana=:id");
        $stmt->bindParam(':id', $txtIDCampana);
        $stmt->execute();
        header("Location: eventos.php");
        ob_end_flush();
        exit;
}

switch($accionParticipacion) {
    case "AgregarParticipacion":
        $fecha = date('Y-m-d');
        $stmt = $conexion->prepare("INSERT INTO participacion_campania (id_campana, id_mascota, id_zona, fecha_participacion, estado) VALUES (:id_campana, :id_mascota, :id_zona, :fecha_participacion, 1)");
        $stmt->bindParam(':id_campana', $txtIDCampanaParticipacion);
        $stmt->bindParam(':id_mascota', $txtIDMascotaParticipacion);
        $stmt->bindParam(':id_zona', $txtIDZonaParticipacion);
        $stmt->bindParam(':fecha_participacion', $fecha);
        $stmt->execute();
        header("Location: eventos.php");
        ob_end_flush();
        exit;
    case "ModificarParticipacion":
        $stmt = $conexion->prepare("UPDATE participacion_campania SET id_campana=:id_campana, id_mascota=:id_mascota, id_zona=:id_zona, fecha_participacion=:fecha_participacion WHERE id_participacion=:id");
        $stmt->bindParam(':id_campana', $txtIDCampanaParticipacion);
        $stmt->bindParam(':id_mascota', $txtIDMascotaParticipacion);
        $stmt->bindParam(':id_zona', $txtIDZonaParticipacion);
        $stmt->bindParam(':fecha_participacion', $txtFechaParticipacion);
        $stmt->bindParam(':id', $txtIDParticipacion);
        $stmt->execute();
        header("Location: eventos.php");
        ob_end_flush();
        exit;
    case "CancelarParticipacion":
        header("Location: eventos.php");
        ob_end_flush();
        exit;
    case "BorrarParticipacion":
        $stmt = $conexion->prepare("UPDATE participacion_campania SET estado=0 WHERE id_participacion=:id");
        $stmt->bindParam(':id', $txtIDParticipacion);
        $stmt->execute();
        header("Location: eventos.php");
        ob_end_flush();
        exit;
}

// Para seleccionar registros sin redireccionar
switch($accionCampana) {
    case "SeleccionarCampana":
        $stmt = $conexion->prepare("SELECT * FROM campanias WHERE id_campana=:id");
        $stmt->bindParam(':id', $txtIDCampana);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtTitulo = $row['titulo'];
        $txtDescripcion = $row['descripcion'];
        $txtIDTipoCampana = $row['id_tipo_campana'];
        $txtFechaInicio = $row['fecha_inicio'];
        $txtFechaFin = $row['fecha_fin'];
        $txtLugar = $row['lugar'];
        $txtIDOrganizacion = $row['id_organizacion'];
        break;
}

switch($accionParticipacion) {
    case "SeleccionarParticipacion":
        $stmt = $conexion->prepare("SELECT * FROM participacion_campania WHERE id_participacion=:id");
        $stmt->bindParam(':id', $txtIDParticipacion);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtIDCampanaParticipacion = $row['id_campana'];
        $txtIDMascotaParticipacion = $row['id_mascota'];
        $txtIDZonaParticipacion = $row['id_zona'];
        $txtFechaParticipacion = $row['fecha_participacion'];
        break;
}

// Cargar listas selects y datos para mostrar
$listaTipoCampana = $conexion->query("SELECT id_tipo_campana, tipo_campana FROM tipo_campana WHERE estado=1 ORDER BY tipo_campana")->fetchAll(PDO::FETCH_ASSOC);
$listaOrganizaciones = $conexion->query("SELECT id_organizacion, organizacion FROM organizacion WHERE estado=1 ORDER BY organizacion")->fetchAll(PDO::FETCH_ASSOC);
$listaCampanas = $conexion->query("SELECT id_campana, titulo FROM campanias WHERE estado=1 ORDER BY titulo")->fetchAll(PDO::FETCH_ASSOC);
$listaZonas = $conexion->query("SELECT id_zona, zona FROM zona WHERE estado=1 ORDER BY zona")->fetchAll(PDO::FETCH_ASSOC);
$listaMascotas = $conexion->query("
    SELECT m.id_mascota, CONCAT(m.nombre, ' (', p.nombres, ' ', p.apellidos, ')') AS nombre_con_dueño
    FROM mascotas m
    LEFT JOIN personas p ON m.id_persona = p.id_persona
    WHERE m.estado=1
    ORDER BY m.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$campanasStmt = $conexion->prepare("SELECT c.*, t.tipo_campana, o.organizacion 
                                   FROM campanias c 
                                   LEFT JOIN tipo_campana t ON c.id_tipo_campana = t.id_tipo_campana 
                                   LEFT JOIN organizacion o ON c.id_organizacion = o.id_organizacion 
                                   WHERE c.estado=1
                                   ORDER BY c.titulo");
$campanasStmt->execute();
$campanas = $campanasStmt->fetchAll(PDO::FETCH_ASSOC);

$participacionesStmt = $conexion->prepare("SELECT p.*, ca.titulo, m.nombre, z.zona 
                                          FROM participacion_campania p 
                                          LEFT JOIN campanias ca ON p.id_campana = ca.id_campana 
                                          LEFT JOIN mascotas m ON p.id_mascota = m.id_mascota 
                                          LEFT JOIN zona z ON p.id_zona = z.id_zona 
                                          WHERE p.estado=1 
                                          ORDER BY p.fecha_participacion DESC");
$participacionesStmt->execute();
$participaciones = $participacionesStmt->fetchAll(PDO::FETCH_ASSOC);

include("../template/cabecera.php");
?>

<div class="container">
    <h1>Gestión de Campañas</h1>
    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIDCampana" value="<?= htmlspecialchars($txtIDCampana) ?>">
        <div class="mb-3">
            <label for="txtTitulo" class="form-label">Título</label>
            <input type="text" name="txtTitulo" id="txtTitulo" value="<?= htmlspecialchars($txtTitulo) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="txtDescripcion" class="form-label">Descripción</label>
            <textarea name="txtDescripcion" id="txtDescripcion" class="form-control" rows="3" required><?= htmlspecialchars($txtDescripcion) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="txtIDTipoCampana" class="form-label">Tipo de Campaña</label>
            <select name="txtIDTipoCampana" id="txtIDTipoCampana" class="form-select" required>
                <option value="">Seleccione tipo</option>
                <?php foreach ($listaTipoCampana as $tipo): ?>
                    <option value="<?= htmlspecialchars($tipo['id_tipo_campana']) ?>" <?= ($txtIDTipoCampana == $tipo['id_tipo_campana']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tipo['tipo_campana']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtFechaInicio" class="form-label">Fecha de Inicio</label>
            <input type="date" name="txtFechaInicio" id="txtFechaInicio" value="<?= htmlspecialchars($txtFechaInicio) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="txtFechaFin" class="form-label">Fecha de Fin</label>
            <input type="date" name="txtFechaFin" id="txtFechaFin" value="<?= htmlspecialchars($txtFechaFin) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="txtLugar" class="form-label">Lugar</label>
            <input type="text" name="txtLugar" id="txtLugar" value="<?= htmlspecialchars($txtLugar) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="txtIDOrganizacion" class="form-label">Organización</label>
            <select name="txtIDOrganizacion" id="txtIDOrganizacion" class="form-select" required>
                <option value="">Seleccione organización</option>
                <?php foreach ($listaOrganizaciones as $org): ?>
                    <option value="<?= htmlspecialchars($org['id_organizacion']) ?>" <?= ($txtIDOrganizacion == $org['id_organizacion']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($org['organizacion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="accionCampana" value="AgregarCampana" <?= ($accionCampana == 'SeleccionarCampana') ? 'disabled' : '' ?> class="btn btn-success">Agregar</button>
        <button type="submit" name="accionCampana" value="ModificarCampana" <?= ($accionCampana == 'SeleccionarCampana') ? '' : 'disabled' ?> class="btn btn-warning">Modificar</button>
        <button type="submit" name="accionCampana" value="CancelarCampana" <?= ($accionCampana == 'SeleccionarCampana') ? '' : 'disabled' ?> class="btn btn-info">Cancelar</button>
    </form>

    <hr>

    <h2>Listado de Campañas</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Tipo</th>
                <th>Fechas</th>
                <th>Lugar</th>
                <th>Organización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($campanas as $campana): ?>
            <tr>
                <td><?= $campana['id_campana'] ?></td>
                <td><?= htmlspecialchars($campana['titulo']) ?></td>
                <td><?= htmlspecialchars($campana['descripcion']) ?></td>
                <td><?= htmlspecialchars($campana['tipo_campana']) ?></td>
                <td><?= htmlspecialchars($campana['fecha_inicio']) ?> a <?= htmlspecialchars($campana['fecha_fin']) ?></td>
                <td><?= htmlspecialchars($campana['lugar']) ?></td>
                <td><?= htmlspecialchars($campana['organizacion']) ?></td>
                <td>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="txtIDCampana" value="<?= $campana['id_campana'] ?>">
                        <button type="submit" name="accionCampana" value="SeleccionarCampana" class="btn btn-primary btn-sm">Editar</button>
                    </form>
                    <form method="post" style="display:inline">
                        <input type="hidden" name="txtIDCampana" value="<?= $campana['id_campana'] ?>">
                        <button type="submit" name="accionCampana" value="BorrarCampana" onclick="return confirm('¿Seguro?')" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <h1>Gestión Participaciones</h1>
    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIDParticipacion" value="<?= htmlspecialchars($txtIDParticipacion) ?>">
        <div class="mb-3">
            <label for="txtIDCampanaParticipacion">Campaña</label>
            <select name="txtIDCampanaParticipacion" id="txtIDCampanaParticipacion" class="form-select" required>
                <option value="">Seleccione campaña</option>
                <?php foreach($campanas as $camp): ?>
                    <option value="<?= $camp['id_campana'] ?>" <?= ($txtIDCampanaParticipacion == $camp['id_campana']) ? 'selected' : '' ?>><?= htmlspecialchars($camp['titulo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDMascotaParticipacion">Mascota</label>
            <select name="txtIDMascotaParticipacion" id="txtIDMascotaParticipacion" class="form-select" required>
                <option value="">Seleccione mascota</option>
                <?php foreach($listaMascotas as $m): ?>
                    <option value="<?= $m['id_mascota'] ?>" <?= ($txtIDMascotaParticipacion == $m['id_mascota']) ? 'selected' : '' ?>><?= htmlspecialchars($m['nombre_con_dueño']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDZonaParticipacion">Zona</label>
            <select name="txtIDZonaParticipacion" id="txtIDZonaParticipacion" class="form-select" required>
                <option value="">Seleccione zona</option>
                <?php foreach($listaZonas as $zona): ?>
                    <option value="<?= $zona['id_zona'] ?>" <?= ($txtIDZonaParticipacion == $zona['id_zona']) ? 'selected' : '' ?>><?= htmlspecialchars($zona['zona']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtFechaParticipacion">Fecha de Participación</label>
            <input type="date" name="txtFechaParticipacion" id="txtFechaParticipacion" value="<?= htmlspecialchars($txtFechaParticipacion ?? date('Y-m-d')) ?>" class="form-control" required>
        </div>
        <div>
            <button type="submit" name="accionParticipacion" value="AgregarParticipacion" <?= ($accionParticipacion == 'SeleccionarParticipacion') ? '' : '' ?> class="btn btn-success">Agregar</button>
            <button type="submit" name="accionParticipacion" value="ModificarParticipacion" <?= ($accionParticipacion == 'SeleccionarParticipacion') ? '' : 'disabled' ?> class="btn btn-warning">Modificar</button>
            <button type="submit" name="accionParticipacion" value="CancelarParticipacion" <?= ($accionParticipacion == 'SeleccionarParticipacion') ? '' : 'disabled' ?> class="btn btn-info">Cancelar</button>
        </div>
    </form>

    <hr>
      <h2>Listado de Participaciones</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Campaña</th>
                <th>Mascota</th>
                <th>Zona</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($participaciones as $part): ?>
            <tr>
                <td><?= $part['id_participacion'] ?></td>
                <td><?= htmlspecialchars($part['titulo']) ?></td>
                <td><?= htmlspecialchars($part['nombre']) ?></td>
                <td><?= htmlspecialchars($part['zona']) ?></td>
                <td><?= htmlspecialchars($part['fecha_participacion']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="txtIDParticipacion" value="<?= $part['id_participacion'] ?>">
                        <button type="submit" name="accionParticipacion" value="SeleccionarParticipacion" class="btn btn-sm btn-primary">Editar</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="txtIDParticipacion" value="<?= $part['id_participacion'] ?>">
                        <button type="submit" name="accionParticipacion" value="BorrarParticipacion" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que desea eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioPersona = document.getElementById('radioPersona');
    const radioOrganizacion = document.getElementById('radioOrganizacion');
    const contenedorPersona = document.getElementById('contenedorPersona');
    const contenedorOrganizacion = document.getElementById('contenedorOrganizacion');

    function toggleOwnerFields() {
        if (radioPersona.checked) {
            contenedorPersona.style.display = 'block';
            contenedorOrganizacion.style.display = 'none';
        } else {
            contenedorPersona.style.display = 'none';
            contenedorOrganizacion.style.display = 'block';
        }
    }

    radioPersona.addEventListener('click', toggleOwnerFields);
    radioOrganizacion.addEventListener('click', toggleOwnerFields);
    toggleOwnerFields();
});
</script>

<?php
include("../template/pie.php");

// Enviar el buffer y desactivar almacenamiento de salida
ob_end_flush();
?>
