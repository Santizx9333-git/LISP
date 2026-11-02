<?php
require_once "conexion.php";

$sql = "SELECT * FROM integrantes";
$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Integrantes | LISP</title>

    <link rel="icon" href="/img/favicon.png">

    <link rel="stylesheet" href="css/style.css">

    <!-- Iconos -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <!-- ================= HEADER ================= -->

    <header class="header">

        <a href="index.php" class="logo">
            <span>&lt;/&gt;</span> LISP
        </a>

        <button class="menu-toggle" type="button" aria-label="Abrir menú">
            <i class="fa-solid fa-bars"></i>
        </button>

        <nav class="navbar">
            <a href="index.php#inicio">Inicio</a>
            <a href="index.php#nosotros">LISP</a>
            <a href="index.php#enfoque">Enfoque</a>
            <a href="index.php#proyectos">Proyectos</a>
            <a href="index.php#integrantes">Integrantes</a>
        </nav>

        <a href="index.php#unirse" class="btn-header">
            Unirse
        </a>

    </header>


    <main>

        <!-- ================= INTEGRANTES ================= -->

        <section class="section members" style="padding-top: 160px;">

            <div class="section-title center">

                <p class="section-tag">04 · INTEGRANTES</p>

                <h2>
                    Personas detrás de <span>LISP.</span>
                </h2>

                <p>
                    Un equipo formado por estudiantes con diferentes
                    intereses y habilidades.
                </p>

            </div>

            <div class="members-grid">

                <?php while ($integrante = $resultado->fetch_assoc()): ?>

                    <article class="member-card">

                        <div class="member-photo">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <h3>
                            <?= htmlspecialchars($integrante['nombre']) ?>
                            <?= htmlspecialchars($integrante['apellido']) ?>
                        </h3>

                        <p>
                            <?= htmlspecialchars($integrante['rol'] ?? '') ?>
                        </p>

                        <?php if (!empty($integrante['descripcion'])): ?>
                            <p class="member-description">
                                <?= htmlspecialchars($integrante['descripcion']) ?>
                            </p>
                        <?php endif; ?>

                    </article>

                <?php endwhile; ?>

                <?php if ($resultado->num_rows === 0): ?>
                    <p class="members-empty">
                        Todavía no hay integrantes cargados.
                    </p>
                <?php endif; ?>

            </div>

        </section>

    </main>


    <!-- ================= FOOTER ================= -->

    <footer class="footer">

        <div class="footer-logo">
            <span>&lt;/&gt;</span> LISP
        </div>

        <p>
            Club de Ingeniería en Sistemas
        </p>

        <div class="socials">

            <a href="#">
                <i class="fa-brands fa-instagram"></i>
            </a>

            <a href="#">
                <i class="fa-brands fa-github"></i>
            </a>

            <a href="#">
                <i class="fa-brands fa-discord"></i>
            </a>

        </div>

        <p class="copyright">
            © 2026 LISP · UNViMe
        </p>

    </footer>

    <script src="js/menu.js" defer></script>
</body>
</html>