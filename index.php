<?php include("template/cabecera.php"); ?>
<style>
.promo-card {
    max-width: 9000px;
    background: rgba(255, 255, 255, 0.90);
    border-radius: 16px; /* leve curva para amabilidad */
    box-shadow: 0 18px 58px rgba(142,170,107,0.13), 0 6px 30px rgba(255, 255, 255, 0.09);
    display: flex;
    flex-direction: column;
    align-items: center;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    min-height: 520px;
    padding: 72px 58px 58px 58px;
}

.logo-main {
    margin-top: 0px;
    margin-bottom: 10px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.logo-main img {
    width: 240px;
    height: 240px;
    border-radius: 50%;
    box-shadow: 0 10px 32px #aedf7e;
    background: none;
    border: none;
}

.promo-title {
    font-size: 2.8em;
    color: #303629;
    font-weight: 700;
    margin-bottom: 20px;
    letter-spacing: 0.04em;
    text-align:center;
}

.promo-subtitle {
    font-size: 1.35em;
    color: #47af2c;
    font-weight: 600;
    margin-bottom: 35px;
    margin-top: 8px;
    text-align:center;
}

.promo-btn-group {
    display: flex;
    gap: 38px;
    margin-bottom: 34px;
    flex-wrap: wrap;
    justify-content: center;
}

.promo-btn {
    box-shadow: 0 4px 16px #c9ee72a4;
    background: #cbf591;
    color: #383a33;
    border-radius: 30px;
    padding: 22px 48px;
    font-size: 1.35em;
    font-weight: 700;
    border: none;
    cursor: pointer;
    transition: background 0.18s, color 0.18s, box-shadow 0.16s;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.promo-btn:hover {
    background: #b6ec44;
    color: #fff;
    box-shadow: 0 6px 24px #b6ec44c7;
    transform: translateY(-3px) scale(1.05);
}

/* Responsive */
@media (max-width:850px){
    .promo-card{ padding:30px 2vw }
    .logo-main img{ width: 150px; height: 150px; }
    .promo-title{ font-size:1.6em;}
    .promo-btn{ font-size:1.12em; padding: 16px 28px;}
}

</style>

<div class="promo-card">
    <div class="logo-main">
        <img src="img/chiapet3.png" alt="Logo CHIAPET">
    </div>
    <div class="promo-title">Bienvenid@ a CHIAPET</div>
    <div class="promo-subtitle">¡Gestiona los datos de tus mascotas de forma fácil!</div>
    <div class="promo-btn-group">
        <button class="promo-btn" onclick="window.location.href='mascotas.php'">🐶 Ir a Mis Mascotas</button>
        <button class="promo-btn" onclick="window.location.href='campanas.php'">📢 Ir a Campañas</button>
        <button class="promo-btn" onclick="window.location.href='reportes_mascota.php'">📄 Ir a Reportes de mis mascotas</button>
    </div>
</div>
<?php include("template/pie.php"); ?>
