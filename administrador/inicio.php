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


// Campañas Activas (donde fecha_fin es mayor o igual a hoy)
$sentenciaCampanas = $conexion->prepare("SELECT COUNT(*) AS total FROM campanias WHERE fecha_fin >= CURDATE()");
$sentenciaCampanas->execute();
$rowCampanas = $sentenciaCampanas->fetch(PDO::FETCH_ASSOC);
$totalCampanas = $rowCampanas['total'];


// Desparasitaciones Realizadas
$sentenciaDesparasitaciones = $conexion->prepare("SELECT COUNT(*) AS total FROM desparasitaciones");
$sentenciaDesparasitaciones->execute();
$rowDesparasitaciones = $sentenciaDesparasitaciones->fetch(PDO::FETCH_ASSOC);
$totalDesparasitaciones = $rowDesparasitaciones['total'];


// Vacunas Realizadas
$sentenciaVacunas = $conexion->prepare("SELECT COUNT(*) AS total FROM vacunas");
$sentenciaVacunas->execute();
$rowVacunas = $sentenciaVacunas->fetch(PDO::FETCH_ASSOC);
$totalVacunas = $rowVacunas['total'];
?>



<div class="container mt-2">
    <div class="row align-items-center mb-4 justify-content-center">
        <div class="col-auto">
            <img src="../img/chiapet3.png" alt="Logo CHIAPETS" style="height: 190px;">
        </div>
        <div class="col">
            <h1 class="display-4 mb-0 text-center">Bienvenido, Administrador</h1>
            <p class="lead text-center">Desde aquí puedes gestionar todos los aspectos de CHIAPETS</p>
                    <div class="text-center mt-3">
                <a href="seccion/dashboard.php" class="btn btn-lg btn-outline-primary" style="font-size:1.3rem;">
                    Ver Dashboard de Estadísticas
                </a>
            </div>
        </div>
    </div>
    <hr>
    <div class="row justify-content-center flex-nowrap" style="overflow-x:auto;">
        <div class="col-12 col-md-2 mb-4 d-flex justify-content-center">
            <div class="card text-white" style="background: #AFE521; width: 700px;">
                <div class="card-body">
                    <h5 class="card-title text-center">Mascotas Registradas</h5>
                    <p class="card-text display-4 text-center"><?php echo $totalMascotas; ?></p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center">
                    <small class="w-100 text-center">Actualizado hoy</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2 mb-4 d-flex justify-content-center">
            <div class="card text-white" style="background: #FFCC18; width: 700px;">
                <div class="card-body">
                    <h5 class="card-title text-center">Usuarios Registrados</h5>
                    <p class="card-text display-4 text-center"><?php echo $totalUsuarios; ?></p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center">
                    <small class="w-100 text-center">Recuento actualizado</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2 mb-4 d-flex justify-content-center">
            <div class="card text-white" style="background: #3BAFDA; width: 700px;">
                <div class="card-body">
                    <h5 class="card-title text-center">Campañas Activas</h5>
                    <p class="card-text display-4 text-center"><?php echo $totalCampanas; ?></p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center">
                    <small class="w-100 text-center">Campañas vigentes</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2 mb-4 d-flex justify-content-center">
            <div class="card text-white" style="background: #62BF18; width: 700px;">
                <div class="card-body">
                    <h5 class="card-title text-center">Desparasitaciones Realizadas</h5>
                    <p class="card-text display-4 text-center"><?php echo $totalDesparasitaciones; ?></p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center">
                    <small class="w-100 text-center">Total de desparasitaciones</small>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-2 mb-4 d-flex justify-content-center">
            <div class="card text-white" style="background: #FF6F61; width: 700px;">
                <div class="card-body">
                    <h5 class="card-title text-center">Vacunas Realizadas</h5>
                    <p class="card-text display-4 text-center"><?php echo $totalVacunas; ?></p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-center">
                    <small class="w-100 text-center">Total de vacunas aplicadas</small>
                
                </div>
            </div>
        </div>
    </div>
</div>

<hr style="margin: 60px auto 25px auto; max-width:990px; border-top:2px solid #222;">
<div style="text-align:center;">
    <h2 style="margin-bottom:18px;">Manual de Administrador</h2>
    <iframe src="../img/AdmManual.pdf" width="80%" height="800" style="border:1px solid #ccc; border-radius:8px;" ></iframe>
</div>


<?php include('template/pie.php'); ?>