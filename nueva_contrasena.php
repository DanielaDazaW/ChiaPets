<?php
// nueva_contrasena.php
session_start();
include("administrador/config/bd.php");

if (!isset($_SESSION['reset_id_persona'])) {
    $_SESSION['mensaje_olvido'] = 'Flujo inválido. Inicie nuevamente.';
    header('Location: olvido_contrasena.php'); exit;
}

// Mostrar formulario para nueva contraseña
$mensaje = isset($_SESSION['mensaje_nueva']) ? $_SESSION['mensaje_nueva'] : '';
unset($_SESSION['mensaje_nueva']);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Nueva contraseña</title></head>
<body>
  <h2>Establecer nueva contraseña</h2>
  <?php if($mensaje): ?><div style="color:red;"><?php echo htmlspecialchars($mensaje); ?></div><?php endif; ?>
  <form method="post" action="actualizar_contrasena.php" autocomplete="off">
    <div>
      <label>Nueva contraseña</label>
      <input type="password" name="contrasena" required />
    </div>
    <div>
      <label>Confirmar contraseña</label>
      <input type="password" name="contrasena2" required />
    </div>
    <button type="submit">Guardar</button>
  </form>
</body>
</html>
