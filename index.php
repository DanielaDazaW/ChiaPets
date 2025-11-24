<?php include("template/cabecera.php"); ?>
<style>
    body {
        background: linear-gradient(110deg, #f2ffd7 70%, #c8ff61 100%);
        font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
        margin: 0;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 85vh;
        justify-content: flex-start;
        padding-top: 2px;
    }

    .logo-area {
        //background: #fff;
        //border-radius: 50%;
        box-shadow: 0 8px 32px rgba(96, 180, 75, 0.11);
        padding: 18px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-area img {
        width: 200px;
        height: 200px;
        //border-radius: 50%;
        //object-fit: cover;
        background: transparent;
    }

    .welcome {
        color: #313330ff;
        font-size: 2.2em;
        font-weight: 500;
        text-align: center;
        margin-bottom: 39px;
        letter-spacing: 0.04em;
    }

    .cards-row {
        display: flex;
        gap: 46px;
        justify-content: center;
        margin-top: 5px;
        flex-wrap: wrap;
    }

    .card-btn {
        background: #f9ffe7;
        border-radius: 18px;
        box-shadow: 0 2px 18px rgba(82,180,71,0.12);
        padding: 27px 22px 19px 22px;
        width: 155px;
        height: 155px;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.17s, transform 0.22s, box-shadow 0.18s;
        border: none;
    }
    .card-btn img {
        width: 44px;
        height: 44px;
        margin-bottom: 26px;
    }
    .card-btn span {
        color: #234710;
        font-size: 1.19em;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    .card-btn:hover {
        background: #eaffd0;
        transform: translateY(-6px) scale(1.08);
        box-shadow: 0 10px 32px rgba(41,180,71,0.14);
    }
</style>

<div class="container">
    <div class="logo-area">
        <img src="img/chiapet3.png" alt="Logo CHIAPET">
    </div>
    <div class="welcome">Bienvenid@ a CHIAPET</div>
    <div class="cards-row">
        <a href="mascotas.php" class="card-btn">
            <img src="img/MisMascotas.png" alt="Mis Mascotas">
            <span>Mis Mascotas</span>
        </a>
        <a href="campanas.php" class="card-btn">
            <img src="img/Campanas.png" alt="Campañas">
            <span>Campañas</span>
        </a>
        <a href="reportes_mascota.php" class="card-btn">
            <img src="img/reportes.png" alt="Reportes">
            <span>Reportes</span>
        </a>
    </div>
</div>
<?php include("template/pie.php"); ?>
