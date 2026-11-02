<?php
require_once "auth.php";
requerir_login();

$paginaActiva = 'panel';
$mensajeError = (($_GET['error'] ?? '') === 'permiso')
    ? 'No tenés permiso para entrar a esa sección.'
    : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Panel | LISP</title>

    <link rel="icon" href="/img/favicon.png">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <?php require "_header.php"; ?>

    <main class="admin-main">

        <?php if ($mensajeError): ?>
            <div class="login-error flash"><?= h($mensajeError) ?></div>
        <?php endif; ?>

        <section class="admin-welcome">

            <p class="section-tag">PANEL DE ADMINISTRACIÓN</p>

            <h1>
                Hola, <span><?= h($_SESSION["usuario"]) ?></span>
            </h1>

            <p class="admin-role">
                <i class="fa-solid fa-shield-halved"></i>
                Rol: <?= h($_SESSION["rol"]) ?>
            </p>

        </section>

        <section class="admin-grid">

            <article class="admin-card">
                <div class="about-icon"><i class="fa-solid fa-users"></i></div>
                <h3>Integrantes</h3>

                <?php if (es_admin()): ?>
                    <p>Agregá, editá o eliminá los miembros que se muestran en la página principal.</p>
                    <a href="integrantes.php" class="admin-card-link">
                        Gestionar integrantes <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <p>Mirá los miembros del club que se muestran en el sitio.</p>
                    <a href="../integrantes.php" class="admin-card-link">
                        Ver integrantes <i class="fa-solid fa-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </article>

            <article class="admin-card">
                <div class="about-icon"><i class="fa-solid fa-diagram-project"></i></div>
                <h3>Proyectos</h3>
                <p>Subí y administrá los proyectos del club: estado, tecnologías, descripción e imagen.</p>
                <a href="proyectos.php" class="admin-card-link">
                    Gestionar proyectos <i class="fa-solid fa-arrow-right"></i>
                </a>
            </article>

            <article class="admin-card">
                <div class="about-icon"><i class="fa-solid fa-gear"></i></div>
                <h3>Mi cuenta</h3>
                <p>Cambiá tu contraseña de acceso.</p>
                <a href="cuenta.php" class="admin-card-link">
                    Ir a mi cuenta <i class="fa-solid fa-arrow-right"></i>
                </a>
            </article>

        </section>

    </main>

    <script src="../js/menu.js" defer></script>
</body>
</html>