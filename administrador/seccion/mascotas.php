<?php
ob_start();
include("../config/bd.php");

// VARIABLES
$txtID = $_POST['txtID'] ?? "";
$txtNombre = $_POST['txtNombre'] ?? "";
$txtPeso = $_POST['txtPeso'] ?? "";
$txtDuenoTipo = $_POST['txtDuenoTipo'] ?? "persona";
$txtIDPersona = $_POST['txtIDPersona'] ?? null;
$txtIDOrganizacion = $_POST['txtIDOrganizacion'] ?? null;
$txtIDEspecie = $_POST['txtIDEspecie'] ?? "";
$txtIDRaza = $_POST['txtIDRaza'] ?? "";
$txtIDSexo = $_POST['txtIDSexo'] ?? "";
$txtIDColor = $_POST['txtIDColor'] ?? "";
$txtIDTamano = $_POST['txtIDTamano'] ?? "";
$txtFechaNacimiento = $_POST['txtFechaNacimiento'] ?? "";
$txtEsterilizado = $_POST['txtEsterilizado'] ?? null;
$txtMicrochipCodigo = $_POST['txtMicrochipCodigo'] ?? null;
$txtFotoUrl = $_POST['txtFotoUrl'] ?? "";

$archivo = $_FILES['archivo'] ?? null;
$accion = $_POST['accion'] ?? "";

// Selects
$listaEspecies = $conexion->query("SELECT id_especie, tipo_especie FROM especie WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaRazas = $conexion->query("SELECT id_raza, tipo_raza FROM raza WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaSexos = $conexion->query("SELECT id_sexo, tipo_sexo FROM sexo WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaColores = $conexion->query("SELECT id_color, color FROM color WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaTamanos = $conexion->query("SELECT id_tamano, tamano FROM tamano WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaPersonas = $conexion->query("SELECT id_persona, CONCAT(nombres, ' ', apellidos, ' (', numero_documento, ')') AS nombre_completo FROM personas WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaOrganizaciones = $conexion->query("SELECT id_organizacion, organizacion FROM organizacion WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaTiposReporte = $conexion->query("SELECT id_tipo_reporte, tipo_reporte FROM tipo_reporte")->fetchAll(PDO::FETCH_ASSOC);

// Procesos de acción (igual que tu código original: Agregar, Modificar, Seleccionar, Borrar)

// Mascotas para listados
$sql = "SELECT m.*, e.tipo_especie, r.tipo_raza, s.tipo_sexo, c.color, t.tamano, p.nombres AS nombre_persona, p.numero_documento, o.organizacion
FROM mascotas m
LEFT JOIN especie e ON m.id_especie = e.id_especie
LEFT JOIN raza r ON m.id_raza = r.id_raza
LEFT JOIN sexo s ON m.id_sexo = s.id_sexo
LEFT JOIN color c ON m.id_color = c.id_color
LEFT JOIN tamano t ON m.id_tamano = t.id_tamano
LEFT JOIN personas p ON m.id_persona = p.id_persona
LEFT JOIN organizacion o ON m.id_organizacion = o.id_organizacion
WHERE m.estado=1";
$stmtData = $conexion->prepare($sql);
$stmtData->execute();
$listaMascotas = $stmtData->fetchAll(PDO::FETCH_ASSOC);

include("../template/cabecera.php");
?>

<div class="container">
    <h1>Gestión de Mascotas</h1>
    <!-- FORMULARIO DE MASCOTAS -->
    <form method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="txtID" value="<?= htmlspecialchars($txtID) ?>">
        <div class="mb-3">
            <label for="txtNombre" class="form-label">Nombre</label>
            <input type="text" name="txtNombre" id="txtNombre" class="form-control" value="<?= htmlspecialchars($txtNombre ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="txtPeso" class="form-label">Peso (Kg)</label>
            <input type="number" name="txtPeso" id="txtPeso" class="form-control" value="<?= htmlspecialchars($txtPeso ?? '') ?>" step="0.01" required>
        </div>
        <div class="mb-3">
            <label>Dueño</label><br>
            <input type="radio" name="txtDuenoTipo" id="radioPersona" value="persona" <?= $txtDuenoTipo === 'persona' ? 'checked' : '' ?>>
            <label for="radioPersona">Persona</label>
            <input type="radio" name="txtDuenoTipo" id="radioOrganizacion" value="organizacion" <?= $txtDuenoTipo === 'organizacion' ? 'checked' : '' ?>>
            <label for="radioOrganizacion">Organización</label>
        </div>
        <div id="contenedorDuenoPersona" style="display: <?= $txtDuenoTipo === 'persona' ? 'block' : 'none' ?>">
            <label for="txtIDPersona" class="form-label">Seleccionar persona</label>
            <select name="txtIDPersona" id="txtIDPersona" class="form-select">
                <option value="">Seleccione</option>
                <?php foreach ($listaPersonas as $persona): ?>
                    <option value="<?= htmlspecialchars($persona['id_persona']) ?>" <?= $persona['id_persona'] == $txtIDPersona ? 'selected' : '' ?>>
                        <?= htmlspecialchars($persona['nombre_completo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="contenedorDuenoOrganizacion" style="display: <?= $txtDuenoTipo === 'organizacion' ? 'block' : 'none' ?>">
            <label for="txtIDOrganizacion" class="form-label">Seleccionar organización</label>
            <select name="txtIDOrganizacion" id="txtIDOrganizacion" class="form-select">
                <option value="">Seleccione</option>
                <?php foreach ($listaOrganizaciones as $org): ?>
                    <option value="<?= htmlspecialchars($org['id_organizacion']) ?>" <?= $org['id_organizacion'] == $txtIDOrganizacion ? 'selected' : '' ?>>
                        <?= htmlspecialchars($org['organizacion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDEspecie" class="form-label">Especie</label>
            <select name="txtIDEspecie" id="txtIDEspecie" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaEspecies as $especie): ?>
                    <option value="<?= htmlspecialchars($especie['id_especie']) ?>" <?= $especie['id_especie'] == $txtIDEspecie ? 'selected' : '' ?>>
                        <?= htmlspecialchars($especie['tipo_especie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDRaza" class="form-label">Raza</label>
            <select name="txtIDRaza" id="txtIDRaza" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaRazas as $raza): ?>
                    <option value="<?= htmlspecialchars($raza['id_raza']) ?>" <?= $raza['id_raza'] == $txtIDRaza ? 'selected' : '' ?>>
                        <?= htmlspecialchars($raza['tipo_raza']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDSexo" class="form-label">Sexo</label>
            <select name="txtIDSexo" id="txtIDSexo" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaSexos as $sexo): ?>
                    <option value="<?= htmlspecialchars($sexo['id_sexo']) ?>" <?= $sexo['id_sexo'] == $txtIDSexo ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sexo['tipo_sexo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDColor" class="form-label">Color</label>
            <select name="txtIDColor" id="txtIDColor" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaColores as $color): ?>
                    <option value="<?= htmlspecialchars($color['id_color']) ?>" <?= $color['id_color'] == $txtIDColor ? 'selected' : '' ?>>
                        <?= htmlspecialchars($color['color']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDTamano" class="form-label">Tamaño</label>
            <select name="txtIDTamano" id="txtIDTamano" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaTamanos as $tamano): ?>
                    <option value="<?= htmlspecialchars($tamano['id_tamano']) ?>" <?= $tamano['id_tamano'] == $txtIDTamano ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tamano['tamano']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtFechaNacimiento" class="form-label">Fecha de nacimiento</label>
            <input type="date" name="txtFechaNacimiento" id="txtFechaNacimiento" class="form-control" value="<?= htmlspecialchars($txtFechaNacimiento ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="txtEsterilizado" class="form-label">Esterilizado</label>
            <select name="txtEsterilizado" id="txtEsterilizado" class="form-select">
                <option value="">Seleccione</option>
                <option value="1" <?= $txtEsterilizado === '1' ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= $txtEsterilizado === '0' ? 'selected' : '' ?>>No</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtMicrochipCodigo" class="form-label">Código microchip</label>
            <input type="number" name="txtMicrochipCodigo" id="txtMicrochipCodigo" class="form-control" value="<?= htmlspecialchars($txtMicrochipCodigo ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="archivo" class="form-label">Foto</label>
            <input type="file" name="archivo" id="archivo" class="form-control" accept="image/*" />
            <?php if (!empty($txtFotoUrl) && $txtFotoUrl !== 'imagen.jpg'): ?>
                <div class="mt-2">
                    <img src="../../img/<?= htmlspecialchars($txtFotoUrl) ?>" alt="Foto Mascota" style="max-width: 150px; max-height: 150px;" />
                </div>
                <input type="hidden" name="txtFotoUrl" value="<?= htmlspecialchars($txtFotoUrl) ?>" />
            <?php else: ?>
                <input type="hidden" name="txtFotoUrl" value="imagen.jpg" />
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <button type="submit" name="accion" value="Agregar" <?= $accion === 'Seleccionar' ? 'disabled' : '' ?> class="btn btn-success">Agregar</button>
            <button type="submit" name="accion" value="Modificar" <?= $accion !== 'Seleccionar' ? 'disabled' : '' ?> class="btn btn-warning">Modificar</button>
            <button type="submit" name="accion" value="Cancelar" <?= $accion !== 'Seleccionar' ? 'disabled' : '' ?> class="btn btn-info">Cancelar</button>
        </div>
    </form>
</div>

<!-- FORMULARIO PARA GENERAR REPORTES -->
<div class="container mt-4">
    <h2>Generar Reporte de Mascota</h2>
    <form method="post" action="reporte_mascota.php">
        <div class="mb-3">
            <label for="id_mascota" class="form-label">Mascota</label>
            <select name="id_mascota" id="id_mascota" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaMascotas as $m) : ?>
                    <option value="<?= $m['id_mascota'] ?>">
                        <?= htmlspecialchars($m['nombre']) ?> - 
                        <?php
                        if ($m['id_persona']) {
                            echo htmlspecialchars($m['nombre_persona']) . ' (' . $m['numero_documento'] . ')';
                        } elseif ($m['id_organizacion']) {
                            echo htmlspecialchars($m['organizacion']);
                        } else {
                            echo 'Sin dueño';
                        }
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción del reporte</label>
            <textarea name="descripcion" id="descripcion" class="form-control" maxlength="300" required></textarea>
        </div>
        <div class="mb-3">
            <label for="ubicacion" class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" maxlength="255" required>
        </div>
        <div class="mb-3">
            <label for="id_tipo_reporte" class="form-label">Tipo de reporte</label>
            <select name="id_tipo_reporte" id="id_tipo_reporte" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaTiposReporte as $tr): ?>
                    <option value="<?= $tr['id_tipo_reporte'] ?>"><?= htmlspecialchars($tr['tipo_reporte']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
    </form>
</div>

<hr />

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Peso</th>
                <th>Dueño</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Sexo</th>
                <th>Color</th>
                <th>Tamaño</th>
                <th>Fecha Nacimiento</th>
                <th>Esterilizado</th>
                <th>Microchip</th>
                <th>Foto</th>
                <th>Fecha Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaMascotas as $m) : ?>
                <tr>
                    <td><?= $m['id_mascota'] ?></td>
                    <td><?= htmlspecialchars($m['nombre']) ?></td>
                    <td><?= htmlspecialchars($m['peso']) ?></td>
                    <td>
                        <?php
                        if ($m['id_persona']) {
                            echo htmlspecialchars($m['nombre_persona']) . ' (' . $m['numero_documento'] . ')';
                        } elseif ($m['id_organizacion']) {
                            echo htmlspecialchars($m['organizacion']);
                        } else {
                            echo 'Sin dueño';
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($m['tipo_especie']) ?></td>
                    <td><?= htmlspecialchars($m['tipo_raza']) ?></td>
                    <td><?= htmlspecialchars($m['tipo_sexo']) ?></td>
                    <td><?= htmlspecialchars($m['color']) ?></td>
                    <td><?= htmlspecialchars($m['tamano']) ?></td>
                    <td><?= htmlspecialchars($m['fecha_nacimiento']) ?></td>
                    <td><?= is_null($m['esterilizado']) ? 'Sin info' : ($m['esterilizado'] ? 'Sí' : 'No') ?></td>
                    <td><?= htmlspecialchars($m['microchip_codigo']) ?></td>
                    <td>
                        <?php if ($m['foto_url'] && $m['foto_url'] !== 'imagen.jpg') : ?>
                            <img src="../../img/<?= htmlspecialchars($m['foto_url']) ?>" alt="Foto" style="max-width: 100px; max-height: 100px;" />
                        <?php else : ?>
                            Sin foto
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($m['fecha_registro']) ?></td>
                    <td>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="txtID" value="<?= $m['id_mascota'] ?>" />
                            <button type="submit" name="accion" value="Seleccionar" class="btn btn-info btn-sm">Editar</button>
                        </form>
                        <form method="post" style="display: inline-block;">
                            <input type="hidden" name="txtID" value="<?= $m['id_mascota'] ?>" />
                            <button type="submit" name="accion" value="Borrar" onclick="return confirm('¿Está seguro?')" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const radioPersona = document.getElementById("radioPersona");
    const radioOrganizacion = document.getElementById("radioOrganizacion");
    const contenedorPersona = document.getElementById("contenedorDuenoPersona");
    const contenedorOrganizacion = document.getElementById("contenedorDuenoOrganizacion");

    function toggleOwnerFields() {
        if (radioPersona.checked) {
            contenedorPersona.style.display = "block";
            contenedorOrganizacion.style.display = "none";
        } else {
            contenedorPersona.style.display = "none";
            contenedorOrganizacion.style.display = "block";
        }
    }

    radioPersona.addEventListener("change", toggleOwnerFields);
    radioOrganizacion.addEventListener("change", toggleOwnerFields);

    toggleOwnerFields();
});
</script>

<?php include("../template/pie.php"); ob_end_flush(); ?>
