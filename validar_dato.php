<?php
// validar_dato.php
session_start();
include("administrador/config/bd.php"); // $conexion (PDO)

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: olvido_contrasena.php'); exit;
}

$numero_documento = isset($_POST['numero_documento']) ? trim($_POST['numero_documento']) : '';
$usuario_input = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';

// simple rate-limit por sesión para este flujo (mejorar con DB o cache en producción)
if (!isset($_SESSION['olvido_intentos'])) { $_SESSION['olvido_intentos'] = 0; }
if ($_SESSION['olvido_intentos'] >= 5) {
    $_SESSION['mensaje_olvido'] = 'Demasiados intentos. Intente más tarde.'; header('Location: olvido_contrasena.php'); exit;
}

if (empty($numero_documento)) {
    $_SESSION['mensaje_olvido'] = 'Ingrese número de documento.'; header('Location: olvido_contrasena.php'); exit;
}

// buscar persona activa
$stmt = $conexion->prepare("SELECT p.id_persona, u.id_usuario FROM personas p LEFT JOIN usuario u ON u.id_persona = p.id_persona WHERE p.numero_documento = :numero_documento AND p.estado = 1");
$stmt->execute([':numero_documento' => $numero_documento]);
$persona = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$persona) {
    $_SESSION['olvido_intentos']++;
    $_SESSION['mensaje_olvido'] = 'Datos no encontrados.'; header('Location: olvido_contrasena.php'); exit;
}

// Si se ingresó usuario, validar que coincida con id_persona (opcional)
if (!empty($usuario_input)) {
    // buscar coincidencia usuario - id_persona
    $stmtU = $conexion->prepare("SELECT id_usuario FROM usuario WHERE id_persona = :id_persona LIMIT 1");
    $stmtU->execute([':id_persona' => $persona['id_persona']]);
    $u = $stmtU->fetch(PDO::FETCH_ASSOC);
    if (!$u) {
        $_SESSION['olvido_intentos']++;
        $_SESSION['mensaje_olvido'] = 'Usuario no asociado al documento.'; header('Location: olvido_contrasena.php'); exit;
    }
}

// Crear token temporal y guardarlo en tabla password_resets (recomendado)
$token = bin2hex(random_bytes(16)); // token en claro (no enviado por email aquí)
$token_hash = password_hash($token, PASSWORD_DEFAULT);
$expires_at = (new DateTime('+30 minutes'))->format('Y-m-d H:i:s');

$stmtIns = $conexion->prepare("INSERT INTO password_resets (id_persona, token_hash, expires_at, attempts) VALUES (:id_persona, :token_hash, :expires_at, 0)");
$stmtIns->execute([':id_persona' => $persona['id_persona'], ':token_hash' => $token_hash, ':expires_at' => $expires_at]);

// Guardar identificadores en sesión para permitir cambio de contraseña sin correo
$_SESSION['reset_id_persona'] = $persona['id_persona'];
$_SESSION['reset_token_plain'] = $token; // temporal en sesión (no recomendable en prod si hay muchos usuarios concurrentes)
$_SESSION['reset_bd_hash'] = $token_hash;
$_SESSION['reset_expires_at'] = $expires_at;

// Redirigir a formulario para nueva contraseña
header('Location: nueva_contrasena.php'); exit;
