<?php
include('config/bd.php');
include('template/cabecera.php');

// Mascotas Registradas
$sentenciaMascotas = $conexion->prepare("SELECT COUNT(*) AS total FROM mascotas");
$sentenciaMascotas->execute();
$rowMascotas = $sentenciaMascotas->fetch(PDO::FETCH_ASSOC);
$totalMascotas = $rowMascotas['total'];

// Usuarios (todos)
$sentenciaUsuarios = $conexion->prepare("SELECT COUNT(*) AS total FROM personas");
$sentenciaUsuarios->execute();
$rowUsuarios = $sentenciaUsuarios->fetch(PDO::FETCH_ASSOC);
$totalUsuarios = $rowUsuarios['total'];
?>

<div class="container mt-4">
    <div class="row align-items-center mb-4 justify-content-center">
        <div class="col-auto">
            <img src="../img/chiapet3.png" alt="Logo CHIAPETS" style="height: 190px;">
        </div>
        <div class="col">
            <h1 class="display-4 mb-0 text-center">Bienvenido, Administrador</h1>
            <p class="lead text-center">Desde aquí puedes gestionar todos los aspectos de CHIAPETS</p>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center">
        <div class="col-12 col-md-4 mb-4 d-flex justify-content-center">
            <div class="card text-white" style="background: #AFE521; width: 280px;">
                <div class="card-body">
                    <h5 class="card-title">Mascotas Registradas</h5>
                    <p class="card-text display-4 text-center"><?php echo $totalMascotas; ?></p>
                </div>
                <div class="card-footer text-center">
                    <small>Actualizado hoy</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4 mb-4 d-flex justify-content-center">
            <div class="card text-white" style="background: #FFCC18; width: 280px;">
                <div class="card-body">
                    <h5 class="card-title">Usuarios Registrados</h5>
                    <p class="card-text display-4 text-center"><?php echo $totalUsuarios; ?></p>
                </div>
                <div class="card-footer text-center">
                    <small>Recuento actualizado</small>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include('template/pie.php'); ?>
