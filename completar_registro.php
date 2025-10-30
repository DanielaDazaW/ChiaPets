<?php
session_start();
include("administrador/config/bd.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_persona = $_POST['id_persona'];

    $stmt = $conexion->prepare("SELECT * FROM personas WHERE id_persona = :id_persona");
    $stmt->bindParam(':id_persona', $id_persona);
    $stmt->execute();
    $persona = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($persona) {
        $tiposDocumento = $conexion->query("SELECT id_tipo_documento, tipo_documento FROM tipo_documento WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
        $generos = $conexion->query("SELECT id_genero, genero FROM genero WHERE estado=1")->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="utf-8" />
            <title>Completar Registro</title>
            <link rel="stylesheet" href="./css/bootstrap.min.css" />
        </head>
        <body style="background:#d4f763;">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="main-content p-4 shadow rounded bg-light">
                        <h2 class="mb-4 text-center">Completar registro</h2>
                        <form method="post" action="finalizar_registro.php" autocomplete="off">
                            <input type="hidden" name="id_persona" value="<?= $id_persona ?>">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" id="correo" name="correo" required value="<?= htmlspecialchars($persona['correo']) ?>" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" id="telefono" name="telefono" required value="<?= htmlspecialchars($persona['telefono']) ?>" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label for="id_genero" class="form-label">Género</label>
                                <select id="id_genero" name="id_genero" required class="form-select">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($generos as $g): ?>
                                        <option value="<?= htmlspecialchars($g['id_genero']) ?>" <?= ($persona['id_genero'] == $g['id_genero']) ? 'selected' : '' ?>><?= htmlspecialchars($g['genero']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" max="<?= date('Y-m-d') ?>" required value="<?= htmlspecialchars($persona['fecha_nacimiento']) ?>" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <input type="password" id="contrasena" name="contrasena" required placeholder="Min. 8 caracteres, mayúsculas, minúsculas y números" class="form-control" />
                            </div>
                            <div class="mb-3">
                                <label for="contrasena2" class="form-label">Confirmar Contraseña</label>
                                <input type="password" id="contrasena2" name="contrasena2" required class="form-control" />
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Finalizar Registro</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
        exit;
    } else {
        echo "No encontramos datos para completar el registro.";
        exit;
    }
} else {
    header("Location: registro.php");
    exit;
}
?>
