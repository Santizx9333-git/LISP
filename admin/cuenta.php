<?php
require_once "auth.php";
requerir_login();
require_once "../conexion.php";

$uid = (int)$_SESSION['usuario_id'];
$error = null;
$ok = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_validar();

    $actual    = $_POST["actual"] ?? "";
    $nueva     = $_POST["nueva"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $fila = $stmt->get_result()->fetch_assoc();

    if ($actual === "" || $nueva === "" || $confirmar === "") {
        $error = "Completá todos los campos.";
    } elseif (!$fila || !password_verify($actual, $fila["password"])) {
        $error = "La contraseña actual no es correcta.";
    } elseif ($nueva !== $confirmar) {
        $error = "Las contraseñas nuevas no coinciden.";
    } elseif (strlen($nueva) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $uid);
        $stmt->execute();
        session_regenerate_id(true);
        $ok = true;
    }
}

$paginaActiva = 'cuenta';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi cuenta | LISP Admin</title>

    <link rel="icon" href="/img/favicon.png">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <?php require "_header.php"; ?>

    <main class="admin-main">

        <section class="admin-welcome">
            <p class="section-tag">MI CUENTA</p>
            <h1>Cambiar <span>contraseña</span></h1>
        </section>

        <?php if ($ok): ?>
            <div class="login-success flash">Contraseña actualizada correctamente.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="login-error flash"><?= h($error) ?></div>
        <?php endif; ?>

        <section class="admin-form-card">
            <form method="POST" action="cuenta.php">

                <?= csrf_campo() ?>

                <label for="actual">Contraseña actual</label>
                <input type="password" id="actual" name="actual" required autocomplete="current-password">

                <label for="nueva">Contraseña nueva</label>
                <input type="password" id="nueva" name="nueva" required autocomplete="new-password">

                <label for="confirmar">Confirmar contraseña nueva</label>
                <input type="password" id="confirmar" name="confirmar" required autocomplete="new-password">

                <div class="admin-form-actions">
                    <button type="submit">Actualizar contraseña</button>
                </div>

            </form>
        </section>

    </main>

    <script src="../js/menu.js" defer></script>
</body>
</html>
