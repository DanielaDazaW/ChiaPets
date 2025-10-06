<?php
// actualizar_contrasena.php
session_start();
include("administrador/config/bd.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['reset_id_persona'])) {
    $_SESSION['mensaje_olvido'] = 'Flujo inválido.'; header('Location: olvido_contrasena.php'); exit;
}

$id_persona = $_SESSION['reset_id_persona'];
$contrasena = $_POST['contrasena'] ?? '';
$contrasena2 = $_POST['contrasena2'] ?? '';

if ($contrasena !== $contrasena2) {
    $_SESSION['mensaje_nueva'] = 'Las contraseñas no coinciden.'; header('Location: nueva_contrasena.php'); exit;
}

if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $contrasena)) {
    $_SESSION['mensaje_nueva'] = 'La contraseña debe tener mayúsculas, minúsculas, números y mínimo 8 caracteres.'; header('Location: nueva_contrasena.php'); exit;
}

// Verificar que token en sesión no haya expirado y exista en DB
$now = (new DateTime())->format('Y-m-d H:i:s');
$stmt = $conexion->prepare("SELECT id, token_hash, expires_at, attempts FROM password_resets WHERE id_persona = :id_persona ORDER BY id DESC LIMIT 1");
$stmt->execute([':id_persona' => $id_persona]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reset) {
    $_SESSION['mensaje_olvido'] = 'No hay solicitud de restablecimiento.'; header('Location: olvido_contrasena.php'); exit;
}

if ($reset['expires_at'] < $now) {
    $_SESSION['mensaje_olvido'] = 'El token expiró. Inicie nuevamente.'; header('Location: olvido_contrasena.php'); exit;
}

// Comparar token de sesión con hash guardado
$token_plain = $_SESSION['reset_token_plain'] ?? '';
if (!password_verify($token_plain, $reset['token_hash'])) {
    $_SESSION['mensaje_olvido'] = 'Token inválido.'; header('Location: olvido_contrasena.php'); exit;
}

// Actualizar contraseña en la tabla usuario (buscar usuario por id_persona)
$hash = password_hash($contrasena, PASSWORD_DEFAULT);
$stmtU = $conexion->prepare("UPDATE usuario SET contrasena = :contrasena WHERE id_persona = :id_persona");
$stmtU->execute([':contrasena' => $hash, ':id_persona' => $id_persona]);

// Invalidar el reset (borrar o marcar)
$stmtDel = $conexion->prepare("DELETE FROM password_resets WHERE id = :id");
$stmtDel->execute([':id' => $reset['id']]);

// Limpiar sesión de restablecimiento
unset($_SESSION['reset_id_persona'], $_SESSION['reset_token_plain'], $_SESSION['reset_bd_hash'], $_SESSION['reset_expires_at'], $_SESSION['olvido_intentos']);

$_SESSION['mensaje_login'] = 'Contraseña actualizada con éxito. Puede iniciar sesión.';
header('Location: login.php'); exit;
