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

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

// Puede ser el nombre de usuario o el correo
$identificador = trim($_POST["usuario"] ?? "");
$password      = $_POST["password"] ?? "";

if ($identificador === "" || $password === "") {
    header("Location: login.php?error=1");
    exit;
}

$email = strtolower($identificador);

$sql = "SELECT id, usuario, password, rol
        FROM usuarios
        WHERE usuario = ? OR email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $identificador, $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {

    $usuarioDB = $resultado->fetch_assoc();

    if (password_verify($password, $usuarioDB["password"])) {

        session_regenerate_id(true);

        $_SESSION["usuario_id"] = $usuarioDB["id"];
        $_SESSION["usuario"]    = $usuarioDB["usuario"];
        $_SESSION["rol"]        = $usuarioDB["rol"];

        header("Location: admin/");
        exit;
    }
}

header("Location: login.php?error=1");
exit;