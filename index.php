<?php
include("template/cabecera.php");
?>

<style>
    body {
        background-color: #c8ff61; /* Verde claro */
    }
</style>

<!-- Contenedor del logo -->
<div style="display: flex; justify-content: center; align-items: center; margin-top: 20px;">
    <img src="img/chiapet3.png" alt="Logo CHIAPET" style="width: 230px; height: 230px;">
</div>

<!-- Contenedor de los botones -->
<div style="display: flex; gap: 40px; justify-content: center; align-items: center; margin-top: 50px;">

    <a href="mascotas.php" style="text-decoration: none;">
        <div style="width: 200px; height: 220px; border-radius: 15px; 
        box-shadow: 0 4px 8px rgba(0,0,0,0.7); background: #a2e436; 
        display: flex; flex-direction: column; align-items: center; 
        justify-content: center; transition: transform 0.2s;">
            <img src="img/MisMascotas.png" alt="Mascotas" 
            style="width: 100px; height:100px; margin-bottom: 10px;">
            <h2 style="color: #333;">Mis Mascotas</h2>
        </div>
    </a>

    <a href="campanas.php" style="text-decoration: none;">
        <div style="width: 200px; height: 220px; border-radius: 15px; 
        box-shadow: 0 4px 8px rgba(0,0,0,0.7); background: #a2e436; 
        display: flex; flex-direction: column; align-items: center; 
        justify-content: center; transition: transform 0.2s;">
            <img src="img/Campanas.png" alt="Campañas" 
            style="width: 120px; height:120px; margin-bottom: 10px;">
            <h2 style="color: #333;">Campañas</h2>
        </div>
    </a>

    <a href="reportes_mascota.php" style="text-decoration: none;">
        <div style="width: 200px; height: 220px; border-radius: 15px; 
        box-shadow: 0 4px 8px rgba(0,0,0,0.7); background: #a2e436; 
        display: flex; flex-direction: column; align-items: center; 
        justify-content: center; transition: transform 0.2s;">
            <img src="img/reportes.png" alt="Reportes" 
            style="width: 110px; height:110px; margin-bottom: 10px;">
            <h2 style="color: #333;">Reportes</h2>
        </div>
    </a>

</div>

<?php
include("template/pie.php");
?>
