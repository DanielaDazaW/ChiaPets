<?php
session_start();
include("administrador/config/bd.php");
$mensaje = isset($_SESSION['mensaje_olvido']) ? $_SESSION['mensaje_olvido'] : '';
unset($_SESSION['mensaje_olvido']);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recuperar Contraseña</title>
    <style>
    body {
        background: linear-gradient(160deg, #e0ffc4 0%, #b9efa9 100%);
        min-height: 100vh;
        margin: 0;
    }
    .main-header {
        width: 100%;
        background: #d4f763;
        padding: 2.3rem 0 2.1rem 0;
        box-shadow: 0 4px 15px rgba(80,150,60,0.14);
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 0;
    }
    .header-content {
        text-align: center;
    }
    .header-logo {
        width: 80px;
        border-radius: 50%;
        background: #d4f763;
        margin-bottom: 7px;
    }
    .header-title {
        font-size: 2.25rem;
        font-weight: bold;
        color: #50770a;
        margin-bottom: 0.4rem;
        letter-spacing: 1.2px;
    }
    .header-desc {
        font-size: 1.1rem;
        color: #3d570b;
        margin-bottom: 0.3rem;
    }
    .login-container {
        max-width: 360px;
        margin: 4rem auto;
        background: #d4f763;
        border-radius: 1rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        padding: 2rem 1.5rem 1rem 1.5rem;
        text-align: center;
    }
    .login-logo {
        width: 100px;
        height: 100px;
        margin-bottom: 10px;
        margin-top: -36px;
        border-radius: 50%;
        background-color: #d4f763;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-title {
        font-size: 1.45rem;
        font-weight: bold;
        margin-bottom: 0.7rem;
        color: #5e921a;
    }
    .form-group label {
        font-weight: 500;
        color: #5e921a;
        text-align: left;
        width: 100%;
    }
    .form-group input {
        border-radius: 0.5rem;
        border: 1px solid #a9a9a9;
        padding: 0.7rem;
        width: 100%;
        margin-bottom: 18px;
    }
    .login-btn {
        background: #95e300;
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 0.45rem;
        padding: 0.85rem 0;
        width: 100%;
        margin-bottom: 7px;
        cursor: pointer;
        font-size: 1.1rem;
        transition: background 0.2s;
    }
    .login-btn:hover {
        background: #78bc15;
    }
    .alert-warning {
        background: #fffbe1;
        color: #b59300;
        border-radius: 0.5rem;
        padding: 0.7rem;
        margin-bottom: 1rem;
    }
    </style>
</head>
<body>
<header class="main-header">
    <div class="header-content">
        <img src="img/chiapet3.png" alt="Logo Chiapet" class="header-logo">
        <h1 class="header-title">Recuperar contraseña</h1>
        <p class="header-desc">Ingresa tus datos para restablecer tu contraseña.</p>
    </div>
</header>
<div class="login-container">
    <div class="login-logo">
        <img src="img/chiapet3.png" alt="Logo" style="width:100px;">
    </div>
    <div class="login-title">Recuperar Contraseña</div>
    <?php if($mensaje): ?>
        <div class="alert-warning"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <form method="post" action="validar_dato.php" autocomplete="off">
        <div class="form-group">
            <label for="correo">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" required />
        </div>
        <div class="form-group">
            <label for="numero_documento">Número de Documento</label>
            <input type="text" id="numero_documento" name="numero_documento" required />
        </div>
        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required />
        </div>
        <div class="form-group">
            <label for="telefono">Número Telefónico</label>
            <input type="text" id="telefono" name="telefono" required />
        </div>
        <button type="submit" class="login-btn">Validar</button>
    </form>
    <div style="margin-top:18px;">
        <a href="login.php" style="color:#1da52b;font-weight:600;">Volver al Inicio de Sesión</a>
    </div>
</div>
</body>
</html>