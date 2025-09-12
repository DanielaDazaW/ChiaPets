<!DOCTYPE html>
<html lang="en">
<head>
     <title>Document</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
   
    <link href="https://bootswatch.com/5/brite/bootstrap.min.css" rel="stylesheet" />

     
</head>
<body>
       
    <?php $url="http://".$_SERVER['HTTP_HOST']."/ChiaPet/ChiaPets"   ?>

    <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
        <div class="nav navbar-nav">

                <a class="nav-item nav-link active" href="#">Administrador ChiaPet<span class="sr-only">(actual)</span></a>
                <a class="nav-item nav-link" href="<?php echo $url;?>/administrador/seccion/campanas.php">Campañas</a>
                <a class="nav-item nav-link" href="<?php echo $url;?>/administrador/inicio.php">Inicio</a>
                <a class="nav-item nav-link" href="<?php echo $url;?>/administrador/seccion/cerrar.php">Cerrar</a>
                <a class="nav-item nav-link" href="#">Mascotas</a>
                <a class="nav-item nav-link" href="">Servicios</a>
                <a class="nav-item nav-link" href="<?php echo $url;?>">Ver Sitio</a>
        </div>
    </nav>


    <div class="container">
        <br/><br/>
        <div class="row">


</body>
