<?php
session_start();

// Validar si hay usuario y si es administrador
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] != 1) {
    header("Location: ../login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <title>Administrador ChiaPet</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link href="https://bootswatch.com/5/brite/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php $url="http://".$_SERVER['HTTP_HOST']."/ChiaPet/ChiaPets"; ?>

    <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Administrador ChiaPet</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/administrador/inicio.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/administrador/seccion/roles.php">Administrar Roles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/administrador/seccion/registros.php">Registro Personas/Organizaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/administrador/seccion/eventos.php">Eventos y Campañas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/administrador/seccion/salud.php">Desparasitaciones y Vacunas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/administrador/seccion/mascotas.php">Mascotas y Reportes</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="administrarCatalogosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Administrar Catálogos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="administrarCatalogosDropdown">
                            <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosAdministrativos.php">Catálogos Administrativos</a></li>
                            <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosUsuario.php">Catálogos Persona/Usuario</a></li>
                            <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosMascota.php">Catálogos Mascota</a></li>
                            <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosUbicacion.php">Catálogos Ubicación</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/index.php">Ver Sitio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?php echo $url;?>/administrador/seccion/cerrar.php">Cerrar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <br/><br/>
        <div class="row">
            <!-- Aquí irán los contenidos de cada página conforme navegas -->
        </div>
    </div>
</body>
</html>
