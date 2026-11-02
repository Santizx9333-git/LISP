<?php
// Helpers compartidos del panel. Incluir con: require_once "auth.php";



if (session_status() === PHP_SESSION_NONE) {

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
    'samesite' => 'Lax',
    ]);

    session_start();
}

const ROL_ADMIN = 'admin';

function h($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function requerir_login(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../login.php");
        exit;
    }
}

function es_admin(): bool
{
    return ($_SESSION['rol'] ?? '') === ROL_ADMIN;
}

function requerir_admin(): void
{
    requerir_login();
    if (!es_admin()) {
        header("Location: index.php?error=permiso");
        exit;
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function csrf_validar(): void
{
    $enviado = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $enviado)) {
        http_response_code(403);
        exit("Solicitud inválida. Volvé atrás y recargá la página.");
    }
}

// Borra una imagen de proyecto solo si está dentro de uploads/proyectos/
function borrar_imagen_proyecto(?string $ruta): void
{
    if (!$ruta) {
        return;
    }
    $base    = realpath(__DIR__ . '/../uploads/proyectos');
    $archivo = realpath(__DIR__ . '/../' . $ruta);

    if ($base && $archivo
        && strpos($archivo, $base . DIRECTORY_SEPARATOR) === 0
        && is_file($archivo)) {
        unlink($archivo);
    }
}
