<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: admin/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | LISP</title>

    <link rel="icon" href="/img/favicon.png">

    <link rel="stylesheet" href="css/style.css">

    <!-- Iconos -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <h1>LISP</h1>

            <p>Panel de administración</p>

            <?php if (isset($_GET['registrado'])): ?>
                <div class="login-success">
                    Cuenta creada. Ya podés iniciar sesión.
                    <?php if (isset($_GET['correo'])): ?>
                        <br>No pudimos enviar el correo de bienvenida.
                    <?php else: ?>
                        <br>Te enviamos un correo de bienvenida.
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="login-error">
                    Usuario/correo o contraseña incorrectos.
                </div>
            <?php endif; ?>

            <form action="procesar_login.php" method="POST">

                <label for="usuario">Usuario o correo</label>

                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    autocomplete="username"
                    required
                >

                <label for="password">Contraseña</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                >

                <button type="submit">
                    Iniciar sesión
                </button>

            </form>

            <a href="index.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Volver al inicio
            </a>

            <a href="registro.php" class="back-link">
                <i class="fa-solid fa-user-plus"></i> Crear cuenta
            </a>

        </div>

    </div>

</body>

</html>