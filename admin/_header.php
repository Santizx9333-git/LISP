<?php
// Header único del panel. Antes de incluirlo definí:
$paginaActiva = 'panel' | 'proyectos' | 'integrantes' | 'cuenta';
//$paginaActiva = $paginaActiva ?? '';
?>
<header class="header">

    <div class="logo">
        <span>&lt;/&gt;</span> LISP <span class="admin-tag">ADMIN</span>
    </div>

    <button class="menu-toggle" type="button" aria-label="Abrir menú">
        <i class="fa-solid fa-bars"></i>
    </button>

    <nav class="navbar">
        <a href="index.php" class="<?= $paginaActiva === 'panel' ? 'active' : '' ?>">Panel</a>
        <a href="proyectos.php" class="<?= $paginaActiva === 'proyectos' ? 'active' : '' ?>">Proyectos</a>
        <?php if (es_admin()): ?>
            <a href="integrantes.php" class="<?= $paginaActiva === 'integrantes' ? 'active' : '' ?>">Integrantes</a>
        <?php endif; ?>
        <a href="cuenta.php" class="<?= $paginaActiva === 'cuenta' ? 'active' : '' ?>">Mi cuenta</a>
        <a href="../index.php">Ver sitio</a>
    </nav>

    <a href="../logout.php" class="btn-header btn-logout">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <span class="btn-text">Cerrar sesión</span>
    </a>

</header>
