<?php
include("template/cabecera.php");
include("administrador/config/bd.php");

// La sesión ya está iniciada en la cabecera
$idPersonaSesion = $_SESSION['usuario_id_persona'] ?? null;
if (!$idPersonaSesion) {
    header("Location: ../login.php");
    exit();
}

// ------------------------
// VARIABLES MASCOTA
// ------------------------
$txtID = $_POST['txtID'] ?? "";
$txtNombre = $_POST['txtNombre'] ?? "";
$txtPeso = $_POST['txtPeso'] ?? "";
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

// ------------------------
// VARIABLES VACUNA
// ------------------------
$txtIdVacuna = $_POST['txtIdVacuna'] ?? '';
$txtIdTipoVacuna = $_POST['txtIdTipoVacuna'] ?? '';
$txtIdMascotaVacuna = $_POST['txtIdMascotaVacuna'] ?? '';
$txtFechaAplicacionVacuna = $_POST['txtFechaAplicacionVacuna'] ?? '';
$accionVacuna = $_POST['accionVacuna'] ?? '';

// ------------------------
// VARIABLES DESPARASITACION
// ------------------------
$txtIdDesparasitacion = $_POST['txtIdDesparasitacion'] ?? '';
$txtIdMascotaDesparasitacion = $_POST['txtIdMascotaDesparasitacion'] ?? '';
$txtIdProducto = $_POST['txtIdProducto'] ?? '';
$txtFechaAplicacionDesparasitacion = $_POST['txtFechaAplicacionDesparasitacion'] ?? '';
$txtObservaciones = $_POST['observaciones'] ?? '';
$accionDesparasitacion = $_POST['accionDesparasitacion'] ?? '';

// ------------------------
// LISTAS SELECTS GLOBALES
// ------------------------
$listaEspecies = $conexion->query("SELECT id_especie, tipo_especie FROM especie WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaRazas = $conexion->query("SELECT id_raza, tipo_raza FROM raza WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaSexos = $conexion->query("SELECT id_sexo, tipo_sexo FROM sexo WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaColores = $conexion->query("SELECT id_color, color FROM color WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
$listaTamanos = $conexion->query("SELECT id_tamano, tamano FROM tamano WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);

// Mascotas solo del usuario activo
$listaMascotasUsuario = $conexion->query("
    SELECT id_mascota, nombre 
    FROM mascotas 
    WHERE estado=1 AND id_persona = $idPersonaSesion
    ORDER BY nombre
")->fetchAll(PDO::FETCH_ASSOC);

// Vacunas y productos
$listaTiposVacuna = $conexion->query("SELECT id_tipo_vacuna, tipo_vacuna FROM tipo_vacuna WHERE estado=1 ORDER BY tipo_vacuna")->fetchAll(PDO::FETCH_ASSOC);
$listaProducto = $conexion->query("SELECT id_producto, producto FROM producto WHERE estado=1 ORDER BY producto")->fetchAll(PDO::FETCH_ASSOC);

// ------------------------
// CRUD MASCOTA
// ------------------------
switch ($accion) {
    case 'Agregar':
        $nombreArchivo = "imagen.jpg";
        if ($archivo && $archivo['name']) {
            $nombreArchivo = time() . "_" . $archivo['name'];
            move_uploaded_file($archivo['tmp_name'], "img/" . $nombreArchivo);
        }
        $fechaRegistro = date('Y-m-d');
        $stmt = $conexion->prepare("
            INSERT INTO mascotas 
            (nombre, peso, id_persona, id_organizacion, id_especie, id_raza, id_sexo, id_color, id_tamano, fecha_nacimiento, esterilizado, microchip_codigo, foto_url, estado, fecha_registro) 
            VALUES 
            (:nombre, :peso, :id_persona, NULL, :id_especie, :id_raza, :id_sexo, :id_color, :id_tamano, :fecha_nacimiento, :esterilizado, :microchip_codigo, :foto_url, 1, :fecha_registro)
        ");
        $stmt->bindParam(':nombre', $txtNombre);
        $stmt->bindParam(':peso', $txtPeso);
        $stmt->bindParam(':id_persona', $idPersonaSesion);
        $stmt->bindParam(':id_especie', $txtIDEspecie);
        $stmt->bindParam(':id_raza', $txtIDRaza);
        $stmt->bindParam(':id_sexo', $txtIDSexo);
        $stmt->bindParam(':id_color', $txtIDColor);
        $stmt->bindParam(':id_tamano', $txtIDTamano);
        $stmt->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
        $stmt->bindParam(':esterilizado', $txtEsterilizado);
        $stmt->bindParam(':microchip_codigo', $txtMicrochipCodigo);
        $stmt->bindParam(':foto_url', $nombreArchivo);
        $stmt->bindParam(':fecha_registro', $fechaRegistro);
        $stmt->execute();
        header("Location: mascotas.php");
        exit;
    case 'Modificar':
        $nombreArchivo = $txtFotoUrl;
        if ($archivo && $archivo['name']) {
            $stmtImg = $conexion->prepare("SELECT foto_url FROM mascotas WHERE id_mascota=:id");
            $stmtImg->bindParam(':id', $txtID);
            $stmtImg->execute();
            $registroImg = $stmtImg->fetch(PDO::FETCH_ASSOC);
            $nuevoNombre = time() . "_" . $archivo['name'];
            move_uploaded_file($archivo['tmp_name'], "img/" . $nuevoNombre);
            if ($registroImg['foto_url'] && $registroImg['foto_url'] !== 'imagen.jpg' && file_exists("img/" . $registroImg['foto_url'])) {
                unlink("img/" . $registroImg['foto_url']);
            }
            $nombreArchivo = $nuevoNombre;
        }
        $stmt = $conexion->prepare("
            UPDATE mascotas SET nombre=:nombre, peso=:peso, id_persona=:id_persona, id_organizacion=NULL, id_especie=:id_especie, 
            id_raza=:id_raza, id_sexo=:id_sexo, id_color=:id_color, id_tamano=:id_tamano, fecha_nacimiento=:fecha_nacimiento, 
            esterilizado=:esterilizado, microchip_codigo=:microchip_codigo, foto_url=:foto_url 
            WHERE id_mascota=:id AND id_persona=:id_persona
        ");
        $stmt->bindParam(':nombre', $txtNombre);
        $stmt->bindParam(':peso', $txtPeso);
        $stmt->bindParam(':id_persona', $idPersonaSesion);
        $stmt->bindParam(':id_especie', $txtIDEspecie);
        $stmt->bindParam(':id_raza', $txtIDRaza);
        $stmt->bindParam(':id_sexo', $txtIDSexo);
        $stmt->bindParam(':id_color', $txtIDColor);
        $stmt->bindParam(':id_tamano', $txtIDTamano);
        $stmt->bindParam(':fecha_nacimiento', $txtFechaNacimiento);
        $stmt->bindParam(':esterilizado', $txtEsterilizado);
        $stmt->bindParam(':microchip_codigo', $txtMicrochipCodigo);
        $stmt->bindParam(':foto_url', $nombreArchivo);
        $stmt->bindParam(':id', $txtID);
        $stmt->execute();
        header("Location: mascotas.php");
        exit;
    case 'Seleccionar':
        $stmt = $conexion->prepare("SELECT * FROM mascotas WHERE id_mascota=:id AND id_persona=:id_persona");
        $stmt->bindParam(':id', $txtID);
        $stmt->bindParam(':id_persona', $idPersonaSesion);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $txtNombre = $row['nombre'];
            $txtPeso = $row['peso'];
            $txtIDEspecie = $row['id_especie'];
            $txtIDRaza = $row['id_raza'];
            $txtIDSexo = $row['id_sexo'];
            $txtIDColor = $row['id_color'];
            $txtIDTamano = $row['id_tamano'];
            $txtFechaNacimiento = $row['fecha_nacimiento'];
            $txtEsterilizado = $row['esterilizado'];
            $txtMicrochipCodigo = $row['microchip_codigo'];
            $txtFotoUrl = $row['foto_url'];
        }
        break;
    case 'Borrar':
        $stmt = $conexion->prepare("UPDATE mascotas SET estado=0 WHERE id_mascota=:id AND id_persona=:id_persona");
        $stmt->bindParam(':id', $txtID);
        $stmt->bindParam(':id_persona', $idPersonaSesion);
        $stmt->execute();
        header("Location: mascotas.php");
        exit;
}

// LISTADO DE MASCOTAS (solo del usuario activo)
$sql = "SELECT m.*, e.tipo_especie, r.tipo_raza, s.tipo_sexo, c.color, t.tamano, m.foto_url
        FROM mascotas m
        LEFT JOIN especie e ON m.id_especie = e.id_especie
        LEFT JOIN raza r ON m.id_raza = r.id_raza
        LEFT JOIN sexo s ON m.id_sexo = s.id_sexo
        LEFT JOIN color c ON m.id_color = c.id_color
        LEFT JOIN tamano t ON m.id_tamano = t.id_tamano
        WHERE m.estado=1 AND m.id_persona=:id_persona";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id_persona', $idPersonaSesion);
$stmt->execute();
$listaMascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ------------------------
// CRUD VACUNA
// ------------------------
switch ($accionVacuna) {
    case 'Agregar':
        $stmt = $conexion->prepare("INSERT INTO vacunas (id_tipo_vacuna, id_mascota, fecha_aplicacion, estado) VALUES (:tipo_vacuna, :mascota, :fecha_aplicacion, 1)");
        $stmt->bindParam(':tipo_vacuna', $txtIdTipoVacuna);
        $stmt->bindParam(':mascota', $txtIdMascotaVacuna);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionVacuna);
        $stmt->execute();
        header("Location: mascotas.php#vacunas");
        exit;
    case 'Modificar':
        $stmt = $conexion->prepare("UPDATE vacunas SET id_tipo_vacuna=:tipo_vacuna, id_mascota=:mascota, fecha_aplicacion=:fecha_aplicacion WHERE id_vacuna=:id");
        $stmt->bindParam(':tipo_vacuna', $txtIdTipoVacuna);
        $stmt->bindParam(':mascota', $txtIdMascotaVacuna);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionVacuna);
        $stmt->bindParam(':id', $txtIdVacuna);
        $stmt->execute();
        header("Location: mascotas.php#vacunas");
        exit;
    case 'Cancelar':
        header("Location: mascotas.php#vacunas");
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
        header("Location: mascotas.php#vacunas");
        exit;
}

// LISTADO DE VACUNAS
$listaVacunas = $conexion->query("
    SELECT v.*, tv.tipo_vacuna, m.nombre 
    FROM vacunas v
    LEFT JOIN tipo_vacuna tv ON v.id_tipo_vacuna = tv.id_tipo_vacuna
    LEFT JOIN mascotas m ON v.id_mascota = m.id_mascota
    WHERE v.estado=1 AND m.id_persona = $idPersonaSesion
    ORDER BY v.fecha_aplicacion DESC
")->fetchAll(PDO::FETCH_ASSOC);

// ------------------------
// CRUD DESPARASITACION
// ------------------------
switch ($accionDesparasitacion) {
    case 'Agregar':
        $stmt = $conexion->prepare("INSERT INTO desparasitaciones (id_mascota, id_producto, fecha_aplicacion, observaciones, estado) VALUES (:mascota, :producto, :fecha_aplicacion, :observaciones, 1)");
        $stmt->bindParam(':mascota', $txtIdMascotaDesparasitacion);
        $stmt->bindParam(':producto', $txtIdProducto);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionDesparasitacion);
        $stmt->bindParam(':observaciones', $txtObservaciones);
        $stmt->execute();
        header("Location: mascotas.php#desparasitaciones");
        exit;
    case 'Modificar':
        $stmt = $conexion->prepare("UPDATE desparasitaciones SET id_mascota=:mascota, id_producto=:producto, fecha_aplicacion=:fecha_aplicacion, observaciones=:observaciones WHERE id_desparasitacion=:id");
        $stmt->bindParam(':mascota', $txtIdMascotaDesparasitacion);
        $stmt->bindParam(':producto', $txtIdProducto);
        $stmt->bindParam(':fecha_aplicacion', $txtFechaAplicacionDesparasitacion);
        $stmt->bindParam(':observaciones', $txtObservaciones);
        $stmt->bindParam(':id', $txtIdDesparasitacion);
        $stmt->execute();
        header("Location: mascotas.php#desparasitaciones");
        exit;
    case 'Cancelar':
        header("Location: mascotas.php#desparasitaciones");
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
        header("Location: mascotas.php#desparasitaciones");
        exit;
}

// LISTADO DESPARASITACIONES
$listaDesparasitaciones = $conexion->query("
    SELECT d.*, p.producto, m.nombre 
    FROM desparasitaciones d
    LEFT JOIN producto p ON d.id_producto = p.id_producto
    LEFT JOIN mascotas m ON d.id_mascota = m.id_mascota
    WHERE d.estado=1 AND m.id_persona = $idPersonaSesion
    ORDER BY d.fecha_aplicacion DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container">
    <h1>Gestión de Mascotas</h1>Registra a tu mascota</p>
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
                    <img src="img/<?= htmlspecialchars($txtFotoUrl) ?>" alt="Foto Mascota" style="max-width: 150px; max-height: 150px;" />
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

    <hr />

    <div class="table-responsive mb-5">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Peso</th>
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
                                <img src="img/<?= htmlspecialchars($m['foto_url']) ?>" alt="Foto" style="max-width: 100px; max-height: 100px;" />
                            <?php else : ?>
                                Sin foto
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($m['fecha_registro']) ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtID" value="<?= $m['id_mascota'] ?>">
                                <button type="submit" name="accion" value="Seleccionar" class="btn btn-info btn-sm">Editar</button>
                            </form>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="txtID" value="<?= $m['id_mascota'] ?>">
                                <button type="submit" name="accion" value="Borrar" onclick="return confirm('¿Está seguro?')" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- VACUNAS -->
    <hr />
    <h3 id="vacunas">Gestión de Vacunas</h3>
    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIdVacuna" value="<?= htmlspecialchars($txtIdVacuna) ?>">
        <div class="mb-3">
            <label for="txtIdMascotaVacuna" class="form-label">Mascota</label>
            <select name="txtIdMascotaVacuna" id="txtIdMascotaVacuna" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaMascotasUsuario as $mascota): ?>
                    <option value="<?= $mascota['id_mascota'] ?>" <?= ($txtIdMascotaVacuna == $mascota['id_mascota']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mascota['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIdTipoVacuna" class="form-label">Tipo de Vacuna</label>
            <select name="txtIdTipoVacuna" id="txtIdTipoVacuna" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaTiposVacuna as $vacuna): ?>
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
    <div class="table-responsive mb-5">
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
                <?php foreach ($listaVacunas as $vacuna): ?>
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
    </div>

    <!-- DESPARASITACIONES -->
    <hr />
    <h3 id="desparasitaciones">Gestión de Desparasitaciones</h3>
    <form method="post" autocomplete="off">
        <input type="hidden" name="txtIdDesparasitacion" value="<?= htmlspecialchars($txtIdDesparasitacion) ?>">
        <div class="mb-3">
            <label for="txtIdMascotaDesparasitacion" class="form-label">Mascota</label>
            <select name="txtIdMascotaDesparasitacion" id="txtIdMascotaDesparasitacion" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaMascotasUsuario as $mascota): ?>
                    <option value="<?= $mascota['id_mascota'] ?>" <?= ($txtIdMascotaDesparasitacion == $mascota['id_mascota']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($mascota['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="txtIdProducto" class="form-label">Producto</label>
            <select name="txtIdProducto" id="txtIdProducto" class="form-select" required>
                <option value="">Seleccione</option>
                <?php foreach ($listaProducto as $producto): ?>
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
    <div class="table-responsive mb-5">
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
                <?php foreach ($listaDesparasitaciones as $desparasita): ?>
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
    </div>
</div>
<?php include("template/pie.php") ?>
