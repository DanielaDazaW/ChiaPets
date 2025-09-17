<?php
include("../template/cabecera.php");
include("../config/bd.php");

# Variables vacunas
$txtIdVacuna = $_POST['txtIdVacuna'] ?? '';
$txtIdTipoVacuna = $_POST['txtIdTipoVacuna'] ?? '';
$txtIdMascotaVacuna = $_POST['txtIdMascotaVacuna'] ?? '';
$txtFechaAplicacionVacuna = $_POST['txtFechaAplicacionVacuna'] ?? '';
$accionVacuna = $_POST['accionVacuna'] ?? '';

# Variables desparasitaciones
$txtIdDesparasitacion = $_POST['txtIdDesparasitacion'] ?? '';
$txtIdMascotaDesparasitacion = $_POST['txtIdMascotaDesparasitacion'] ?? '';
$txtIdProducto = $_POST['txtIdProducto'] ?? '';
$txtFechaAplicacionDesparasitacion = $_POST['txtFechaAplicacionDesparasitacion'] ?? '';
$txtObservaciones = $_POST['observaciones'] ?? '';
$accionDesparasitacion = $_POST['accionDesparasitacion'] ?? '';

# Listas para selects
# Mascotas con dueño
$listaMascotas = $conexion->query("
    SELECT m.id_mascota,
           CONCAT(m.nombre, ' (', p.nombres, ' ', p.apellidos, ')') AS nombre_dueno
    FROM mascotas m
    LEFT JOIN personas p ON m.id_persona = p.id_persona
    WHERE m.estado = 1
    ORDER BY m.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$listaTiposVacuna = $conexion->query("SELECT id_tipo_vacuna, tipo_vacuna FROM tipo_vacuna WHERE estado=1 ORDER BY tipo_vacuna")->fetchAll(PDO::FETCH_ASSOC);

$listaProducto = $conexion->query("SELECT id_producto, producto FROM producto WHERE estado=1 ORDER BY producto")->fetchAll(PDO::FETCH_ASSOC);

# Manejo vacunas
switch ($accionVacuna) {
    case 'Agregar':
        $fechaActual = date('Y-m-d');
        $stmt = $conexion->prepare("INSERT INTO vacunas (id_tipo_vacuna, id_mascota, fecha_aplicacion, estado) VALUES (:tipo_vacuna, :mascota, :fecha_aplicacion, 1)");
        $stmt->bindParam(':tipo_vacuna', $txtIdTipoVacuna);
        $stmt->bindParam(':mascota', $txtIdMascotaVacuna);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionVacuna);
        $stmt->execute();
        header("Location: salud.php");
        exit;
    case 'Modificar':
        $stmt = $conexion->prepare("UPDATE vacunas SET id_tipo_vacuna=:tipo_vacuna, id_mascota=:mascota, fecha_aplicacion=:fecha_aplicacion WHERE id_vacuna=:id");
        $stmt->bindParam(':tipo_vacuna', $txtIdTipoVacuna);
        $stmt->bindParam(':mascota', $txtIdMascotaVacuna);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionVacuna);
        $stmt->bindParam(':id', $txtIdVacuna);
        $stmt->execute();
        header("Location: salud.php");
        exit;
    case 'Cancelar':
        header("Location: salud.php");
        exit;
    case 'Seleccionar':
        $stmt = $conexion->prepare("SELECT * FROM vacunas WHERE id_vacuna=:id");
        $stmt->bindParam(':id', $txtIdVacuna);
        $stmt->execute();
        $vacuna = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtIdTipoVacuna = $vacuna['id_tipo_vacuna'];
        $txtIdMascotaVacuna = $vacuna['id_mascota'];
        $txtFechaAplicacionVacuna = $vacuna['fecha_aplicacion'];
        break;
    case 'Borrar':
        $stmt = $conexion->prepare("UPDATE vacunas SET estado=0 WHERE id_vacuna=:id");
        $stmt->bindParam(':id', $txtIdVacuna);
        $stmt->execute();
        header("Location: salud.php");
        exit;
}

# Manejo desparasitaciones
switch ($accionDesparasitacion) {
    case 'Agregar':
        $stmt = $conexion->prepare("INSERT INTO desparasitaciones (id_mascota, id_producto, fecha_aplicacion, observaciones, estado) VALUES (:mascota, :producto, :fecha_aplicacion, :observaciones, 1)");
        $stmt->bindParam(':mascota', $txtIdMascotaDesparasitacion);
        $stmt->bindParam(':producto', $txtIdProducto);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionDesparasitacion);
        $stmt->bindParam(':observaciones', $txtObservaciones);
        $stmt->execute();
        header("Location: salud.php");
        exit;
    case 'Modificar':
        $stmt = $conexion->prepare("UPDATE desparasitaciones SET id_mascota=:mascota, id_producto=:producto, fecha_aplicacion=:fecha_aplicacion, observaciones=:observaciones WHERE id_desparasitacion=:id");
        $stmt->bindParam(':mascota', $txtIdMascotaDesparasitacion);
        $stmt->bindParam(':producto', $txtIdProducto);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionDesparasitacion);
        $stmt->bindParam(':observaciones', $txtObservaciones);
        $stmt->bindParam(':id', $txtIdDesparasitacion);
        $stmt->execute();
        header("Location: salud.php");
        exit;
    case 'Cancelar':
        header("Location: salud.php");
        exit;
    case 'Seleccionar':
        $stmt = $conexion->prepare("SELECT * FROM desparasitaciones WHERE id_desparasitacion=:id");
        $stmt->bindParam(':id', $txtIdDesparasitacion);
        $stmt->execute();
        $desparasitacion = $stmt->fetch(PDO::FETCH_ASSOC);
        $txtIdMascotaDesparasitacion = $desparasitacion['id_mascota'];
        $txtIdProducto = $desparasitacion['id_producto'];
        $txtFechaAplicacionDesparasitacion = $desparasitacion['fecha_aplicacion'];
        $txtObservaciones = $desparasitacion['observaciones'];
        break;
    case 'Borrar':
        $stmt = $conexion->prepare("UPDATE desparasitaciones SET estado=0 WHERE id_desparasitacion=:id");
        $stmt->bindParam(':id', $txtIdDesparasitacion);
        $stmt->execute();
        header("Location: salud.php");
        exit;
}

# Listar vacunas y desparasitaciones con info relacionada
$listaVacunas = $conexion->query("SELECT v.*, tv.tipo_vacuna, m.nombre FROM vacunas v LEFT JOIN tipo_vacuna tv ON v.id_tipo_vacuna = tv.id_tipo_vacuna LEFT JOIN mascotas m ON v.id_mascota = m.id_mascota WHERE v.estado=1 ORDER BY v.fecha_aplicacion DESC")->fetchAll(PDO::FETCH_ASSOC);

$listaDesparasitaciones = $conexion->query("SELECT d.*, p.producto, m.nombre FROM desparasitaciones d LEFT JOIN producto p ON d.id_producto = p.id_producto LEFT JOIN mascotas m ON d.id_mascota = m.id_mascota WHERE d.estado=1 ORDER BY d.fecha_aplicacion DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Gestión de Vacunas</h1>
    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIdVacuna" value="<?= htmlspecialchars($txtIdVacuna) ?>">
        <div class="mb-3">
            <label for="txtIdMascotaVacuna" class="form-label">Mascota</label>
            <select name="txtIdMascotaVacuna" id="txtIdMascotaVacuna" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaMascotas as $mascota) : ?>
                    <option value="<?= $mascota['id_mascota'] ?>" <?= ($txtIdMascotaVacuna == $mascota['id_mascota']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mascota['nombre_dueno']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIdTipoVacuna" class="form-label">Tipo de Vacuna</label>
            <select name="txtIdTipoVacuna" id="txtIdTipoVacuna" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaTiposVacuna as $vacuna) : ?>
                    <option value="<?= $vacuna['id_tipo_vacuna'] ?>" <?= ($txtIdTipoVacuna == $vacuna['id_tipo_vacuna']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($vacuna['tipo_vacuna']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtFechaAplicacionVacuna" class="form-label">Fecha de Aplicación</label>
            <input type="date" name="txtFechaAplicacionVacuna" id="txtFechaAplicacionVacuna" value="<?= htmlspecialchars($txtFechaAplicacionVacuna ?? '') ?>" class="form-control" required>
        </div>
        <div>
            <button type="submit" name="accionVacuna" value="Agregar" <?= ($accionVacuna == 'Seleccionar') ? 'disabled' : '' ?> class="btn btn-success">Agregar</button>
            <button type="submit" name="accionVacuna" value="Modificar" <?= ($accionVacuna == 'Seleccionar') ? '' : 'disabled' ?> class="btn btn-warning">Modificar</button>
            <button type="submit" name="accionVacuna" value="Cancelar" <?= ($accionVacuna == 'Seleccionar') ? '' : 'disabled' ?> class="btn btn-info">Cancelar</button>
        </div>
    </form>

    <h2>Listado de Vacunas</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mascota</th>
                <th>Tipo de Vacuna</th>
                <th>Fecha de Aplicación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaVacunas as $vacuna) : ?>
                <tr>
                    <td><?= $vacuna['id_vacuna'] ?></td>
                    <td><?= htmlspecialchars($vacuna['nombre']) ?></td>
                    <td><?= htmlspecialchars($vacuna['tipo_vacuna']) ?></td>
                    <td><?= htmlspecialchars($vacuna['fecha_aplicacion']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="txtIdVacuna" value="<?= $vacuna['id_vacuna'] ?>">
                            <button type="submit" name="accionVacuna" value="Seleccionar" class="btn btn-primary btn-sm">Editar</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="txtIdVacuna" value="<?= $vacuna['id_vacuna'] ?>">
                            <button type="submit" name="accionVacuna" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Gestión de Desparasitaciones</h1>
    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIdDesparasitacion" value="<?= htmlspecialchars($txtIdDesparasitacion) ?>">
        <div class="mb-3">
            <label for="txtIdMascotaDesparasitacion" class="form-label">Mascota</label>
            <select name="txtIdMascotaDesparasitacion" id="txtIdMascotaDesparasitacion" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaMascotas as $mascota) : ?>
                    <option value="<?= $mascota['id_mascota'] ?>" <?= ($txtIdMascotaDesparasitacion == $mascota['id_mascota']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mascota['nombre_dueno']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIdProducto" class="form-label">Producto</label>
            <select name="txtIdProducto" id="txtIdProducto" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaProducto as $producto) : ?>
                    <option value="<?= $producto['id_producto'] ?>" <?= ($txtIdProducto == $producto['id_producto']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($producto['producto']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtFechaAplicacionDesparasitacion" class="form-label">Fecha de Aplicación</label>
            <input type="date" name="txtFechaAplicacionDesparasitacion" id="txtFechaAplicacionDesparasitacion" value="<?= htmlspecialchars($txtFechaAplicacionDesparasitacion ?? '') ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control"><?= htmlspecialchars($txtObservaciones) ?></textarea>
        </div>
        <div>
            <button type="submit" name="accionDesparasitacion" value="Agregar" <?= ($accionDesparasitacion == 'Seleccionar') ? 'disabled' : '' ?> class="btn btn-success">Agregar</button>
            <button type="submit" name="accionDesparasitacion" value="Modificar" <?= ($accionDesparasitacion == 'Seleccionar') ? '' : 'disabled' ?> class="btn btn-warning">Modificar</button>
            <button type="submit" name="accionDesparasitacion" value="Cancelar" <?= ($accionDesparasitacion == 'Seleccionar') ? '' : 'disabled' ?> class="btn btn-info">Cancelar</button>
        </div>
    </form>
    
    <h2>Listado de Desparasitaciones</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mascota</th>
                <th>Producto</th>
                <th>Fecha de Aplicación</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaDesparasitaciones as $desparasita) : ?>
                <tr>
                    <td><?= $desparasita['id_desparasitacion'] ?></td>
                    <td><?= htmlspecialchars($desparasita['nombre']) ?></td>
                    <td><?= htmlspecialchars($desparasita['producto']) ?></td>
                    <td><?= htmlspecialchars($desparasita['fecha_aplicacion']) ?></td>
                    <td><?= htmlspecialchars($desparasita['observaciones']) ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="txtIdDesparasitacion" value="<?= $desparasita['id_desparasitacion'] ?>">
                            <button type="submit" name="accionDesparasitacion" value="Seleccionar" class="btn btn-primary btn-sm">Editar</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="txtIdDesparasitacion" value="<?= $desparasita['id_desparasitacion'] ?>">
                            <button type="submit" name="accionDesparasitacion" value="Borrar" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Logic for dynamic actions if needed
});
</script>

<?php include("../template/pie.php"); ?>
