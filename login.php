<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
session_start();
include("administrador/config/bd.php");

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    
    $stmt = $conexion->prepare("
        SELECT u.id_usuario, u.contrasena, p.nombres, p.apellidos 
        FROM usuario u 
        JOIN personas p ON u.id_persona = p.id_persona 
        WHERE p.estado = 1 AND p.correo = :correo
    ");
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
    $mensajeError = "Usuario no encontrado o inactivo.";
    // Aquí puede imprimir para debug
    // var_dump($usuario, $correo);
    } else {

    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nombre'] = $usuario['nombres'];
        $_SESSION['usuario_apellido'] = $usuario['apellidos'];
        header("Location:index.php");
        exit();
    } else {
        $mensajeError = "Usuario o contraseña incorrectos.";
    }
}
}
?>
<?php include("template/cabecera.php"); ?>
<style>
  /* Estilos para el formulario de login */
  .main-content {
    max-width: 420px;
    margin: 4rem auto;
    padding: 2rem;
    background: rgba(255,255,255,0.9);
    border-radius: 1rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.25);
  }
  .btn-primary {
    background-color: #007B3E;
    border: none;
    font-size: 1.1rem;
    border-radius: 0.5rem;
    padding: 0.75rem 0;
    width: 100%;
  }
</style>

<div class="main-content">
  <h2 class="mb-4 text-center">Iniciar Sesión</h2>

  <?php if ($mensajeError): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($mensajeError); ?></div>
  <?php endif; ?>

  <form method="post" autocomplete="off">
    <div class="mb-3">
      <label for="correo" class="form-label">Correo Electrónico</label>
      <input type="email" id="correo" name="correo" class="form-control" required placeholder="tu@email.com">
    </div>
    <div class="mb-3">
      <label for="contrasena" class="form-label">Contraseña</label>
      <input type="password" id="contrasena" name="contrasena" class="form-control" required placeholder="Contraseña">
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
  </form>

  <p class="text-center mt-3">
    ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
  </p>
</div>

<?php include("template/pie.php"); ?>
