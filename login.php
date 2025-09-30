<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("administrador/config/bd.php");

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    
    $stmt = $conexion->prepare("
        SELECT u.id_usuario, u.contrasena, p.nombres, p.apellidos,  u.id_tipo_usuario, p.id_persona  
        FROM usuario u 
        JOIN personas p ON u.id_persona = p.id_persona 
        WHERE p.estado = 1 AND p.correo = :correo
    ");
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $mensajeError = "Usuario no encontrado o inactivo.";
    } else {
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombres'];
            $_SESSION['usuario_apellido'] = $usuario['apellidos'];
            $_SESSION['usuario_tipo'] = $usuario['id_tipo_usuario'];
            $_SESSION['usuario_id_persona'] = $usuario['id_persona'];
            header("Location:index.php");
            exit();
        } else {
            $mensajeError = "Usuario o contraseña incorrectos.";
        }
    }
}
?>

<style>
.login-container {
    max-width: 360px;
    margin: 3rem auto;
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
    font-size: 1.65rem;
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
.login-links {
    margin-top: 8px;
}
.login-links a {
    color: #1da52b;
    text-decoration: none;
    font-weight: 600;
}
.login-links a:hover {
    text-decoration: underline;
}
.alert-danger {
    background: #ffd8dd;
    color: #c70000;
    border-radius: 0.5rem;
    padding: 0.7rem;
    margin-bottom: 1rem;
}
</style>

<div class="login-container">
    <div class="login-logo">
        <!-- Coloca aquí la imagen del logo (ajusta ruta si es necesario) -->
        <img src="img/chiapet3.png" alt="Logo" style="width:100px;">
    </div>
    <div class="login-title">Iniciar Sesión</div>

    <?php if ($mensajeError): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($mensajeError); ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <div class="form-group">
            <input type="email" id="correo" name="correo" required placeholder="Usuario">
        </div>
        <div class="form-group">
            <input type="password" id="contrasena" name="contrasena" required placeholder="Contraseña">
        </div>
        <button type="submit" class="login-btn">Ingresar</button>
    </form>
    <div class="login-links">
        <a href="#">¿Olvidó su contraseña?</a> <br>
        <a href="registro.php">¿No tiene una cuenta? Registrarse</a> <br>
        <form action="administrador/login_admin.php" method="get" style="margin-top:8px;">
            <button type="submit" class="login-btn" style="background:#1da52b;">
                Ingresar como Administrador
            </button>
        </form>
    </div>
</div>
<?php include("template/pie.php"); ?>
