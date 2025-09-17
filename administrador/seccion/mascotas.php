<?php
include("../template/cabecera.php");
include("../config/bd.php");

// Variables formulario
$txtIDMascota = $_POST['txtIDMascota'] ?? "";
$txtNombre = $_POST['txtNombre'] ?? "";
$txtPeso = $_POST['txtPeso'] ?? "";
$txtDuenoTipo = $_POST['txtDuenoTipo'] ?? "persona"; // persona u organizacion
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

$accionMascota = $_POST['accionMascota'] ?? "";

// Carga datos para selects
$listaEspecies = $conexion->query("SELECT id_especie, tipo_especie FROM especie WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaRazas = $conexion->query("SELECT id_raza, tipo_raza FROM raza WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaSexos = $conexion->query("SELECT id_sexo, tipo_sexo FROM sexo WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaColores = $conexion->query("SELECT id_color, color FROM color WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaTamanos = $conexion->query("SELECT id_tamano, tamano FROM tamano WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaPersonas = $conexion->query("SELECT id_persona, CONCAT(nombres, ' ', apellidos, ' (', numero_documento, ')') AS nombre_doc FROM personas WHERE estado=1 ORDER BY nombres")->fetchAll(PDO::FETCH_ASSOC);
$listaOrganizaciones = $conexion->query("SELECT id_organizacion, organizacion FROM organizacion WHERE estado=1 ORDER BY organizacion")->fetchAll(PDO::FETCH_ASSOC);

switch ($accionMascota) {
    case "AgregarMascota":
        $fechaRegistro = date('Y-m-d');
        $sentenciaSQL = $conexion->prepare("INSERT INTO mascotas (nombre, peso, id_persona, id_especie, id_raza, id_sexo, id_color, id_tamano, id_organizacion, fecha_nacimiento, esterilizado, microchip_codigo, foto_url, fecha_registro, estado) VALUES (:nombre, :peso, :id_persona, :id_especie, :id_raza, :id_sexo, :id_color, :id_tamano, :id_organizacion, :fecha_nacimiento, :esterilizado, :microchip_codigo, :foto_url, :fecha_registro, 1)");
        $id_persona = ($txtDuenoTipo === "persona") ? $txtIDPersona : null;
        $id_organizacion = ($txtDuenoTipo === "organizacion") ? $txtIDOrganizacion : null;
        $esterilizado = $txtEsterilizado === "" ? null : $txtEsterilizado;
        $microchip = $txtMicrochipCodigo === "" ? null : $txtMicrochipCodigo;
        $foto = $txtFotoUrl === "" ? null : $txtFotoUrl;
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':peso', $txtPeso);
        $sentenciaSQL->bindParam(':id_persona', $id_persona);
        $sentenciaSQL->bindParam(':id_especie', $txtIDEspecie);
        $sentenciaSQL->bindParam(':id_raza', $txtIDRaza);
        $sentenciaSQL->bindParam(':id_sexo', $txtIDSexo);
        $sentenciaSQL->bindParam(':id_color', $txtIDColor);
        $sentenciaSQL->bindParam(':id_tamano', $txtIDTamano);
        $sentenciaSQL->bindParam(':id_organizacion', $id_organizacion);
        $sentenciaSQL->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
        $sentenciaSQL->bindParam(':esterilizado', $esterilizado);
        $sentenciaSQL->bindParam(':microchip_codigo', $microchip);
        $sentenciaSQL->bindParam(':foto_url', $foto);
        $sentenciaSQL->bindParam(':fecha_registro', $fechaRegistro);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;

    case "ModificarMascota":
        $sentenciaSQL = $conexion->prepare("UPDATE mascotas SET nombre=:nombre, peso=:peso, id_persona=:id_persona, id_especie=:id_especie, id_raza=:id_raza, id_sexo=:id_sexo, id_color=:id_color, id_tamano=:id_tamano, id_organizacion=:id_organizacion, fecha_nacimiento=:fecha_nacimiento, esterilizado=:esterilizado, microchip_codigo=:microchip_codigo, foto_url=:foto_url WHERE id_mascota=:id");
        $id_persona = ($txtDuenoTipo === "persona") ? $txtIDPersona : null;
        $id_organizacion = ($txtDuenoTipo === "organizacion") ? $txtIDOrganizacion : null;
        $esterilizado = $txtEsterilizado === "" ? null : $txtEsterilizado;
        $microchip = $txtMicrochipCodigo === "" ? null : $txtMicrochipCodigo;
        $foto = $txtFotoUrl === "" ? null : $txtFotoUrl;
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':peso', $txtPeso);
        $sentenciaSQL->bindParam(':id_persona', $id_persona);
        $sentenciaSQL->bindParam(':id_especie', $txtIDEspecie);
        $sentenciaSQL->bindParam(':id_raza', $txtIDRaza);
        $sentenciaSQL->bindParam(':id_sexo', $txtIDSexo);
        $sentenciaSQL->bindParam(':id_color', $txtIDColor);
        $sentenciaSQL->bindParam(':id_tamano', $txtIDTamano);
        $sentenciaSQL->bindParam(':id_organizacion', $id_organizacion);
        $sentenciaSQL->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
        $sentenciaSQL->bindParam(':esterilizado', $esterilizado);
        $sentenciaSQL->bindParam(':microchip_codigo', $microchip);
        $sentenciaSQL->bindParam(':foto_url', $foto);
        $sentenciaSQL->bindParam(':id', $txtIDMascota);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;

    case "CancelarMascota":
        header("Location: registros.php");
        exit;
        break;

    case "SeleccionarMascota":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM mascotas WHERE id_mascota = :id");
        $sentenciaSQL->bindParam(':id', $txtIDMascota);
        $sentenciaSQL->execute();
        $registro = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $txtNombre = $registro['nombre'];
        $txtPeso = $registro['peso'];
        $txtDuenoTipo = $registro['id_persona'] !== null ? "persona" : "organizacion";
        $txtIDPersona = $registro['id_persona'];
        $txtIDOrganizacion = $registro['id_organizacion'];
        $txtIDEspecie = $registro['id_especie'];
        $txtIDRaza = $registro['id_raza'];
        $txtIDSexo = $registro['id_sexo'];
        $txtIDColor = $registro['id_color'];
        $txtIDTamano = $registro['id_tamano'];
        $txtFechaNacimiento = $registro['fecha_nacimiento'];
        $txtEsterilizado = $registro['esterilizado'];
        $txtMicrochipCodigo = $registro['microchip_codigo'];
        $txtFotoUrl = $registro['foto_url'];
        break;

    case "BorrarMascota":
        $sentenciaSQL = $conexion->prepare("UPDATE mascotas SET estado = 0 WHERE id_mascota = :id");
        $sentenciaSQL->bindParam(':id', $txtIDMascota);
        $sentenciaSQL->execute();
        header("Location: registros.php");
        exit;
        break;
}

// Listar mascotas activas con nombres de relaciones y dueño (persona u organización)
$sentenciaSQL = $conexion->prepare("
    SELECT m.*, e.tipo_especie, r.tipo_raza, s.tipo_sexo, c.color, t.tamano,
    p.nombres AS nombre_persona, p.numero_documento,
    o.organizacion
    FROM mascotas m
    LEFT JOIN especie e ON m.id_especie = e.id_especie
    LEFT JOIN raza r ON m.id_raza = r.id_raza
    LEFT JOIN sexo s ON m.id_sexo = s.id_sexo
    LEFT JOIN color c ON m.id_color = c.id_color
    LEFT JOIN tamano t ON m.id_tamano = t.id_tamano
    LEFT JOIN personas p ON m.id_persona = p.id_persona
    LEFT JOIN organizacion o ON m.id_organizacion = o.id_organizacion
    WHERE m.estado = 1
");
$sentenciaSQL->execute();
$listaMascotas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h1>Gestión Mascotas</h1>
    <form method="POST" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="txtIDMascota" value="<?php echo $txtIDMascota; ?>">
        <div class="mb-3">
            <label for="txtNombre" class="form-label">Nombre:</label>
            <input type="text" required class="form-control" id="txtNombre" name="txtNombre" value="<?php echo htmlspecialchars($txtNombre ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="txtPeso" class="form-label">Peso (kg):</label>
            <input type="number" step="0.01" required class="form-control" id="txtPeso" name="txtPeso" value="<?php echo htmlspecialchars($txtPeso ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">¿El dueño es una persona o una organización?</label><br>
            <input type="radio" name="txtDuenoTipo" value="persona" id="radioPersona" <?php if($txtDuenoTipo=='persona') echo 'checked'; ?>> <label for="radioPersona">Persona</label>
            <input type="radio" name="txtDuenoTipo" value="organizacion" id="radioOrganizacion" <?php if($txtDuenoTipo=='organizacion') echo 'checked'; ?>> <label for="radioOrganizacion">Organización</label>
        </div>
        <div class="mb-3" id="contenedorDuenoPersona" style="display: <?php echo ($txtDuenoTipo=='persona') ? 'block' : 'none'; ?>;">
            <label for="txtIDPersona" class="form-label">Seleccionar Persona (Documento y Nombre):</label>
            <select class="form-select" id="txtIDPersona" name="txtIDPersona">
                <option value="">Seleccione persona</option>
                <?php foreach($listaPersonas as $persona) { ?>
                    <option value="<?php echo $persona['id_persona']; ?>" <?php if($txtIDPersona == $persona['id_persona']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($persona['nombre_doc']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3" id="contenedorDuenoOrganizacion" style="display: <?php echo ($txtDuenoTipo=='organizacion') ? 'block' : 'none'; ?>;">
            <label for="txtIDOrganizacion" class="form-label">Seleccionar Organización:</label>
            <select class="form-select" id="txtIDOrganizacion" name="txtIDOrganizacion">
                <option value="">Seleccione organización</option>
                <?php foreach($listaOrganizaciones as $org) { ?>
                    <option value="<?php echo $org['id_organizacion']; ?>" <?php if($txtIDOrganizacion == $org['id_organizacion']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($org['organizacion']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="txtIDEspecie" class="form-label">Especie:</label>
            <select class="form-select" id="txtIDEspecie" name="txtIDEspecie" required>
                <option value="">Seleccione especie</option>
                <?php foreach ($listaEspecies as $especie) { ?>
                    <option value="<?php echo $especie['id_especie']; ?>" <?php if($txtIDEspecie == $especie['id_especie']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($especie['tipo_especie']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDRaza" class="form-label">Raza:</label>
            <select class="form-select" id="txtIDRaza" name="txtIDRaza" required>
                <option value="">Seleccione raza</option>
                <?php foreach ($listaRazas as $raza) { ?>
                    <option value="<?php echo $raza['id_raza']; ?>" <?php if($txtIDRaza == $raza['id_raza']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($raza['tipo_raza']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDSexo" class="form-label">Sexo:</label>
            <select class="form-select" id="txtIDSexo" name="txtIDSexo" required>
                <option value="">Seleccione sexo</option>
                <?php foreach ($listaSexos as $sexo) { ?>
                    <option value="<?php echo $sexo['id_sexo']; ?>" <?php if($txtIDSexo == $sexo['id_sexo']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($sexo['tipo_sexo']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDColor" class="form-label">Color:</label>
            <select class="form-select" id="txtIDColor" name="txtIDColor" required>
                <option value="">Seleccione color</option>
                <?php foreach ($listaColores as $color) { ?>
                    <option value="<?php echo $color['id_color']; ?>" <?php if($txtIDColor == $color['id_color']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($color['color']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIDTamano" class="form-label">Tamaño:</label>
            <select class="form-select" id="txtIDTamano" name="txtIDTamano" required>
                <option value="">Seleccione tamaño</option>
                <?php foreach ($listaTamanos as $tamano) { ?>
                    <option value="<?php echo $tamano['id_tamano']; ?>" <?php if($txtIDTamano == $tamano['id_tamano']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($tamano['tamano']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtFechaNacimientoMascota" class="form-label">Fecha de Nacimiento:</label>
            <input type="date" class="form-control" id="txtFechaNacimientoMascota" name="txtFechaNacimiento" value="<?php echo htmlspecialchars($txtFechaNacimiento ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="txtEsterilizado" class="form-label">¿Esterilizado? (opcional):</label>
            <select class="form-select" id="txtEsterilizado" name="txtEsterilizado">
                <option value="">Seleccione</option>
                <option value="1" <?php if($txtEsterilizado=="1") echo "selected";?>>Sí</option>
                <option value="0" <?php if($txtEsterilizado==="0") echo "selected";?>>No</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtMicrochipCodigo" class="form-label">Código Microchip (opcional):</label>
            <input type="number" class="form-control" id="txtMicrochipCodigo" name="txtMicrochipCodigo" value="<?php echo htmlspecialchars($txtMicrochipCodigo ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="txtFotoUrl" class="form-label">URL Foto (opcional):</label>
            <input type="text" class="form-control" id="txtFotoUrl" name="txtFotoUrl" value="<?php echo htmlspecialchars($txtFotoUrl ?? ''); ?>">
        </div>

        <div class="btn-group" role="group" aria-label="botones mascota">
            <button type="submit" name="accionMascota" value="AgregarMascota" <?php echo ($accionMascota == "SeleccionarMascota") ? "disabled" : ""; ?> class="btn btn-success">Agregar</button>
            <button type="submit" name="accionMascota" value="ModificarMascota" <?php echo ($accionMascota != "SeleccionarMascota") ? "disabled" : ""; ?> class="btn btn-warning">Modificar</button>
            <button type="submit" name="accionMascota" value="CancelarMascota" <?php echo ($accionMascota != "SeleccionarMascota") ? "disabled" : ""; ?> class="btn btn-info">Cancelar</button>
        </div>
    </form>

    <hr>

    <h2>Listado Mascotas</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
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
                    <th>Microchip Código</th>
                    <th>Foto</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaMascotas as $mascota){ ?>
                <tr>
                    <td><?php echo $mascota['id_mascota']; ?></td>
                    <td><?php echo htmlspecialchars($mascota['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['peso']); ?></td>
                    <td>
                        <?php
                        if($mascota['id_persona']){
                            echo htmlspecialchars($mascota['nombre_persona'])." (Doc: ".$mascota['numero_documento'].")";
                        }elseif($mascota['id_organizacion']){
                            echo htmlspecialchars($mascota['organizacion']);
                        }else{
                            echo "Sin dueño";
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($mascota['tipo_especie']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['tipo_raza']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['tipo_sexo']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['color']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['tamano']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['fecha_nacimiento']); ?></td>
                    <td><?php echo is_null($mascota['esterilizado']) ? "No info" : ($mascota['esterilizado'] ? "Sí" : "No"); ?></td>
                    <td><?php echo htmlspecialchars($mascota['microchip_codigo']); ?></td>
                    <td><?php if($mascota['foto_url']) { ?><img src="<?php echo htmlspecialchars($mascota['foto_url']); ?>" alt="Foto" width="50"><?php } ?></td>
                    <td><?php echo htmlspecialchars($mascota['fecha_registro']); ?></td>
                    <td>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="txtIDMascota" value="<?php echo $mascota['id_mascota']; ?>">
                            <input type="submit" name="accionMascota" value="SeleccionarMascota" class="btn btn-primary btn-sm">
                        </form>
                        <form method="post" style="display:inline-block;">
                            <input type="hidden" name="txtIDMascota" value="<?php echo $mascota['id_mascota']; ?>">
                            <input type="submit" name="accionMascota" value="BorrarMascota" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas desactivar esta mascota?');">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Javascript para mostrar u ocultar select dependiente del dueño
document.addEventListener('DOMContentLoaded', function(){
    const radioPersona = document.getElementById('radioPersona');
    const radioOrganizacion = document.getElementById('radioOrganizacion');
    const contenedorPersona = document.getElementById('contenedorDuenoPersona');
    const contenedorOrganizacion = document.getElementById('contenedorDuenoOrganizacion');

    function actualizarVisibilidad() {
        if(radioPersona.checked){
            contenedorPersona.style.display = 'block';
            contenedorOrganizacion.style.display = 'none';
        } else {
            contenedorPersona.style.display = 'none';
            contenedorOrganizacion.style.display = 'block';
        }
    }
    radioPersona.addEventListener('change', actualizarVisibilidad);
    radioOrganizacion.addEventListener('change', actualizarVisibilidad);

    actualizarVisibilidad(); // inicial
});
</script>

<?php include("../template/pie.php"); ?>
