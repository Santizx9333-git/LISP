<?php
require_once "conexion.php";

$sql_integrantes = "SELECT * FROM integrantes";
$resultado_integrantes = $conn->query($sql_integrantes);

$sqlProyectos = "SELECT nombre, descripcion, estado, tecnologias, imagen
                 FROM proyectos
                 ORDER BY id DESC";
$resultadoProyectos = $conn->query($sqlProyectos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LISP | Club de Ingeniería en Sistemas</title>

    <link rel="stylesheet" href="css/style.css">

    <!-- Iconos -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <!-- ================= HEADER ================= -->

    <header class="header">

        <div class="logo">
            <span>&lt;/&gt;</span> LISP
        </div>

        <button class="menu-toggle" type="button" aria-label="Abrir menú">
            <i class="fa-solid fa-bars"></i>
        </button>

        <nav class="navbar">
            <a href="#inicio">Inicio</a>
            <a href="#nosotros">LISP</a>
            <a href="#enfoque">Enfoque</a>
            <a href="#proyectos">Proyectos</a>
            <a href="#integrantes">Integrantes</a>
        </nav>

        <a href="#unirse" class="btn-header">
            Unirse
        </a>

    </header>


    <!-- ================= HERO ================= -->

    <main>

        <section id="inicio" class="hero">

            <div class="hero-content">

                <p class="hero-tag">
                    CLUB UNIVERSITARIO · INGENIERÍA EN SISTEMAS
                </p>

                <h1>
                    Llevamos las ideas<br>
                    <span>a sistemas reales.</span>
                </h1>

                <p class="hero-description">
                    LISP es un espacio universitario donde estudiantes de
                    Ingeniería en Sistemas se reúnen para aprender, crear
                    proyectos y transformar ideas en soluciones tecnológicas.
                </p>

                <div class="hero-buttons">

                    <a href="#proyectos" class="btn-primary">
                        Ver proyectos
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <a href="#nosotros" class="btn-secondary">
                        Conocer LISP
                    </a>

                </div>

            </div>


            <div class="hero-terminal">

                <div class="terminal-header">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="terminal-body">

                    <p>
                        <span class="terminal-green">lisp@UNViMe</span>:~$
                        ./iniciar_club
                    </p>

                    <p class="terminal-output">
                        Inicializando LISP...
                    </p>

                    <p class="terminal-output">
                        ✓ Proyectos
                    </p>

                    <p class="terminal-output">
                        ✓ Investigación
                    </p>

                    <p class="terminal-output">
                        ✓ Aprendizaje
                    </p>

                    <p class="terminal-output">
                        ✓ Comunidad
                    </p>

                    <p class="terminal-success">
                        Sistema iniciado correctamente.
                    </p>

                    <span class="cursor">_</span>

                </div>

            </div>

        </section>


        <!-- ================= NOSOTROS ================= -->

        <section id="nosotros" class="section about">

            <div class="section-title">

                <p class="section-tag">01 · SOBRE NOSOTROS</p>

                <h2>
                    ¿Qué es <span>LISP</span>?
                </h2>

            </div>

            <div class="about-grid">

                <div class="about-text">

                    <p>
                        LISP es un club universitario orientado a estudiantes
                        de Ingeniería en Sistemas de Información interesados
                        en la tecnología, la programación y el desarrollo
                        de soluciones innovadoras.
                    </p>

                    <p>
                        Nuestro objetivo es complementar la formación
                        académica mediante proyectos, investigación,
                        experimentación y trabajo colaborativo.
                    </p>

                </div>


                <div class="about-card">

                    <div class="about-icon">
                        <i class="fa-solid fa-microchip"></i>
                    </div>

                    <h3>
                        Aprender haciendo
                    </h3>

                    <p>
                        Convertimos los conocimientos adquiridos en la
                        universidad en proyectos y experiencias prácticas.
                    </p>

                </div>

            </div>

        </section>


        <!-- ================= ENFOQUE ================= -->

        <section id="enfoque" class="section focus">

            <div class="section-title center">

                <p class="section-tag">02 · NUESTRO ENFOQUE</p>

                <h2>
                    Tecnología para <span>crear.</span>
                </h2>

                <p>
                    Exploramos diferentes áreas de la Ingeniería en Sistemas
                    para desarrollar soluciones y adquirir experiencia.
                </p>

            </div>


            <div class="focus-grid">

                <article class="focus-card">

                    <div class="card-number">01</div>

                    <i class="fa-solid fa-code"></i>

                    <h3>Programación</h3>

                    <p>
                        Desarrollo de software, algoritmos, estructuras de
                        datos y aplicaciones.
                    </p>

                </article>


                <article class="focus-card">

                    <div class="card-number">02</div>

                    <i class="fa-solid fa-globe"></i>

                    <h3>Desarrollo Web</h3>

                    <p>
                        Diseño y desarrollo de páginas, sistemas y
                        aplicaciones web.
                    </p>

                </article>


                <article class="focus-card">

                    <div class="card-number">03</div>

                    <i class="fa-solid fa-robot"></i>

                    <h3>IA & Automatización</h3>

                    <p>
                        Exploración de inteligencia artificial,
                        automatización y nuevas tecnologías.
                    </p>

                </article>


                <article class="focus-card">

                    <div class="card-number">04</div>

                    <i class="fa-solid fa-network-wired"></i>

                    <h3>Sistemas</h3>

                    <p>
                        Redes, arquitectura, infraestructura y soluciones
                        informáticas.
                    </p>

                </article>

            </div>

        </section>


        <!-- ================= PROYECTOS ================= -->

        <section id="proyectos" class="section projects">

            <div class="section-title">

                <p class="section-tag">03 · PROYECTOS</p>

                <h2>
                    Lo que estamos <span>construyendo.</span>
                </h2>

            </div>

            <div class="projects-grid">

                <?php if ($resultadoProyectos && $resultadoProyectos->num_rows > 0): ?>

                    <?php while ($proyecto = $resultadoProyectos->fetch_assoc()): ?>

                        <?php
                        $esFinalizado = ($proyecto['estado'] === 'Finalizado');
                        $etiquetaEstado = $esFinalizado ? 'FINALIZADO' : 'EN DESARROLLO';

                        $listaTecnologias = array_filter(array_map('trim', explode(',', $proyecto['tecnologias'] ?? '')));
                        ?>

                        <article class="project-card">

                            <div class="project-image">
                                <?php if (!empty($proyecto['imagen'])): ?>
                                    <img src="<?= htmlspecialchars($proyecto['imagen']) ?>"
                                         alt="<?= htmlspecialchars($proyecto['nombre']) ?>"
                                         style="width:100%; height:100%; object-fit:cover;">
                                <?php else: ?>
                                    <div class="project-placeholder">
                                        <i class="fa-solid fa-code"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="project-content">

                                <span class="project-status<?= $esFinalizado ? ' finished' : '' ?>">
                                    <?= htmlspecialchars($etiquetaEstado) ?>
                                </span>

                                <h3>
                                    <?= htmlspecialchars($proyecto['nombre']) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars($proyecto['descripcion'] ?? '') ?>
                                </p>

                                <?php if (!empty($listaTecnologias)): ?>
                                    <div class="technologies">
                                        <?php foreach ($listaTecnologias as $tecnologia): ?>
                                            <span><?= htmlspecialchars($tecnologia) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                            </div>

                        </article>

                    <?php endwhile; ?>

                <?php else: ?>

                    <p class="members-empty">
                        Todavía no hay proyectos cargados.
                    </p>

                <?php endif; ?>

            </div>

        </section>


        <!-- ================= INTEGRANTES ================= -->

        <section id="integrantes" class="section members">

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

                <?php while ($integrante = $resultado_integrantes->fetch_assoc()): ?>

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

                <?php if ($resultado_integrantes->num_rows === 0): ?>
                    <p class="members-empty">
                        Todavía no hay integrantes cargados.
                    </p>
                <?php endif; ?>

            </div>

        </section>


        <!-- ================= UNIRSE ================= -->

        <section id="unirse" class="join">

            <div class="join-content">

                <p class="section-tag">
                    05 · ¿QUERÉS PARTICIPAR?
                </p>

                <h2>
                    Tu próxima idea<br>
                    puede empezar <span>aquí.</span>
                </h2>

                <p>
                    No importa si recién estás comenzando o si ya tenés
                    experiencia. LISP es un espacio para aprender,
                    experimentar y construir junto a otros estudiantes.
                </p>

                <a href="registro.php" class="btn-primary">
                    Quiero formar parte
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

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