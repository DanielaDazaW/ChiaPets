<!DOCTYPE html>
<html lang="en">
<head>
     <title>Document</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
   
    <link href="https://bootswatch.com/5/brite/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
     
</head>
<body>
       
    <?php $url="http://".$_SERVER['HTTP_HOST']."/ChiaPet/ChiaPets"   ?>

    <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
        <div class="nav navbar-nav">

                <a class="nav-item nav-link active" href="#">Administrador ChiaPet<span class="sr-only">
                <a class="nav-item nav-link" href="<?php echo $url;?>/administrador/seccion/campanas.php">Campañas</a>
                <a class="nav-item nav-link" href="<?php echo $url;?>/administrador/inicio.php">Inicio</a>
                <a class="nav-item nav-link" href="<?php echo $url;?>/administrador/seccion/cerrar.php">Cerrar</a>
                <a class="nav-item nav-link" href="#">Mascotas</a>
                <a class="nav-item nav-link" href="">Servicios</a>
                <a class="nav-item nav-link" href="<?php echo $url;?>">Ver Sitio</a>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="administrarCatalogosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administrar Catalogos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="administrarCatalogosDropdown">
                        <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosAdministrativos.php">Catalogos administrativos</a></li>
                        <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosUsuario.php">Catalogos Persona/Usuario</a></li>
                        <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosMascota.php">Catalogos Mascota</a></li>
                        <li><a class="dropdown-item" href="<?php echo $url;?>/administrador/seccion/Catalogos/CatalogosUbicacion.php">Catalogos Ubicacion</a></li>
                    </ul>
                 </li>

        </div>
    </nav>


    <div class="container">
        <br/><br/>
        <div class="row">


</body>
