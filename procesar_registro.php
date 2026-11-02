<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

require_once "conexion.php";
require_once "mailer.php";

// Cambiá este valor cuando quieras invalidar el código actual
define("CODIGO_INVITACION", "LISP2026");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registro.php");
    exit;
}

$usuario   = trim($_POST["usuario"] ?? "");
$email     = strtolower(trim($_POST["email"] ?? ""));
$password  = $_POST["password"] ?? "";
$confirmar = $_POST["confirmar"] ?? "";
$codigo    = trim($_POST["codigo"] ?? "");

if ($usuario === "" || $email === "" || $password === "" || $confirmar === "" || $codigo === "") {
    header("Location: registro.php?error=vacio");
    exit;
}

if (!hash_equals(CODIGO_INVITACION, $codigo)) {
    header("Location: registro.php?error=codigo");
    exit;
}

// El usuario no puede contener "@" para no confundirse con un correo al iniciar sesión
if (strpos($usuario, "@") !== false || mb_strlen($usuario) > 50) {
    header("Location: registro.php?error=usuario");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
    header("Location: registro.php?error=email");
    exit;
}

if ($password !== $confirmar) {
    header("Location: registro.php?error=no_coincide");
    exit;
}

if (strlen($password) < 6) {
    header("Location: registro.php?error=largo");
    exit;
}

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? LIMIT 1");
$stmt->bind_param("s", $usuario);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    header("Location: registro.php?error=existe");
    exit;
}

$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    header("Location: registro.php?error=email_existe");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$rol  = "miembro";

$stmt = $conn->prepare("INSERT INTO usuarios (usuario, email, password, rol) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $usuario, $email, $hash, $rol);
$stmt->execute();

// Si el correo falla, la cuenta igual queda creada
$enviado = enviar_bienvenida($email, $usuario);

header("Location: login.php?registrado=1" . ($enviado ? "" : "&correo=0"));
exit;