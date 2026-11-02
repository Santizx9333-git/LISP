<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: admin/");
    exit;
}

$errores = [
    'vacio'        => 'Completá todos los campos.',
    'no_coincide'  => 'Las contraseñas no coinciden.',
    'codigo'       => 'El código de invitación no es válido.',
    'existe'       => 'Ese usuario ya existe.',
    'largo'        => 'La contraseña debe tener al menos 6 caracteres.',
    'email'        => 'Ingresá un correo válido.',
    'email_existe' => 'Ese correo ya tiene una cuenta.',
    'usuario'      => 'El usuario no puede contener "@" ni superar los 50 caracteres.',
];

$error = $_GET['error'] ?? null;
$mensajeError = $errores[$error] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | LISP</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <h1>LISP</h1>

            <p>Crear cuenta</p>

            <?php if ($mensajeError): ?>
                <div class="login-error">
                    <?= htmlspecialchars($mensajeError) ?>
                </div>
            <?php endif; ?>

            <form action="procesar_registro.php" method="POST">

                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario" maxlength="50" required>

                <label for="email">Correo (Gmail)</label>
                <input type="email" id="email" name="email" maxlength="255"
                       placeholder="tucorreo@gmail.com" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>

                <label for="confirmar">Confirmar contraseña</label>
                <input type="password" id="confirmar" name="confirmar" required>

                <label for="codigo">Código de invitación</label>
                <input type="text" id="codigo" name="codigo" required>

                <button type="submit">
                    Crear cuenta
                </button>

            </form>

            <a href="login.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Ya tengo cuenta
            </a>

        </div>

    </div>

</body>

</html>