<?php
require_once "auth.php";
requerir_admin();
require_once "../conexion.php";

$errores = ['vacio' => 'Nombre y apellido son obligatorios.'];
$mensajeError = $errores[$_GET['error'] ?? ''] ?? null;

$mensajeOk = null;
if (isset($_GET['guardado']))  $mensajeOk = 'Integrante guardado correctamente.';
if (isset($_GET['eliminado'])) $mensajeOk = 'Integrante eliminado.';

$editar = null;
if (isset($_GET['editar']) && ctype_digit($_GET['editar'])) {
    $idEd = (int)$_GET['editar'];
    $stmt = $conn->prepare("SELECT id, nombre, apellido, rol, descripcion FROM integrantes WHERE id = ?");
    $stmt->bind_param("i", $idEd);
    $stmt->execute();
    $editar = $stmt->get_result()->fetch_assoc() ?: null;
}

$resultado = $conn->query("SELECT id, nombre, apellido, rol FROM integrantes ORDER BY id DESC");

$paginaActiva = 'integrantes';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Integrantes | LISP Admin</title>

    <link rel="icon" href="/img/favicon.png">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <?php require "_header.php"; ?>

    <main class="admin-main">

        <section class="admin-welcome">
            <p class="section-tag">GESTIÓN DE INTEGRANTES</p>
            <h1><?= $editar ? 'Editar' : 'Agregar' ?> <span>integrante</span></h1>
        </section>

        <?php if ($mensajeOk): ?>
            <div class="login-success flash"><?= h($mensajeOk) ?></div>
        <?php endif; ?>
        <?php if ($mensajeError): ?>
            <div class="login-error flash"><?= h($mensajeError) ?></div>
        <?php endif; ?>

        <section class="admin-form-card">

            <form action="guardar_integrante.php" method="POST">

                <?= csrf_campo() ?>
                <?php if ($editar): ?>
                    <input type="hidden" name="id" value="<?= (int)$editar['id'] ?>">
                <?php endif; ?>

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" maxlength="100" required
                       value="<?= h($editar['nombre'] ?? '') ?>">

                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" maxlength="100" required
                       value="<?= h($editar['apellido'] ?? '') ?>">

                <label for="rol">Rol en el club</label>
                <input type="text" id="rol" name="rol" maxlength="100"
                       placeholder="Presidente, Desarrollador, etc."
                       value="<?= h($editar['rol'] ?? '') ?>">

                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3"><?= h($editar['descripcion'] ?? '') ?></textarea>

                <div class="admin-form-actions">
                    <button type="submit"><?= $editar ? 'Guardar cambios' : 'Agregar integrante' ?></button>
                    <?php if ($editar): ?>
                        <a href="integrantes.php" class="btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>

            </form>

        </section>

        <section class="admin-list">

            <h2 class="admin-list-title">Integrantes cargados</h2>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Rol</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while ($fila = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?= h($fila['nombre'] . ' ' . $fila['apellido']) ?></td>
                                    <td><?= h($fila['rol'] ?: '—') ?></td>
                                    <td>
                                        <div class="admin-table-actions">
                                            <a href="integrantes.php?editar=<?= (int)$fila['id'] ?>"
                                               class="admin-table-edit" aria-label="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="eliminar_integrante.php" method="POST"
                                                  onsubmit="return confirm('¿Eliminar este integrante?');">
                                                <?= csrf_campo() ?>
                                                <input type="hidden" name="id" value="<?= (int)$fila['id'] ?>">
                                                <button type="submit" class="admin-table-delete" aria-label="Eliminar">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-muted" style="text-align:center; padding:20px;">
                                    Todavía no hay integrantes cargados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>

    </main>

    <script src="../js/menu.js" defer></script>
</body>
</html>
