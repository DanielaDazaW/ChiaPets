<?php
session_start();

if (!isset($_SESSION['usuario_tipo']) || !in_array($_SESSION['usuario_tipo'], [1, 2])) {
  header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang = en>
<head>
    <meta charset = "UFT-8">
    <meta http-equiv= "X-UA-Compatible" content="IE=edge">
    <meta name="Viewport" content="width=device-width, initial-sacale=1.0">
    
    <link rel="stylesheet" href="./css/bootstrap.min.css"/>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-primary align-items-center">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="img/chiapet3.png" alt="Logo" width="40" height="40" class="me-2">
                CHIAPET
            </a>
            <ul class="navbar-nav flex-row align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="mascotas.php">Mis Mascotas</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="campanas.php">Campañas</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="reportes_mascota.php">Reportes de mis mascotas</a>
                </li>
                                <li class="nav-item">
                    <a class="nav-link" href="Nosotros.php">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Servicios.php">Servicios</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="actualizar_mi_informacion.php">Actualizar mi información</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cerrar_sesion.php">Cerrar Sesion</a>
                </li>
            </ul>
        </div>
    </nav>




    <div class="container">
        <br/><br/>

        <div class="row">