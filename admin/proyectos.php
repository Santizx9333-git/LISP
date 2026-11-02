<?php
require_once "auth.php";
requerir_login();
require_once "../conexion.php";

$uid = (int)$_SESSION['usuario_id'];

function puede_modificar(array $fila): bool
{
    return es_admin() || (int)$fila['usuario_id'] === (int)$_SESSION['usuario_id'];
}

$errores = [
    'vacio'   => 'Completá al menos el nombre del proyecto.',
    'imagen'  => 'La imagen debe ser JPG, PNG o WEBP y pesar menos de 5MB.',
    'subida'  => 'Hubo un error al subir la imagen, intentá de nuevo.',
    'permiso' => 'No tenés permiso para modificar ese proyecto.',
];
$mensajeError = $errores[$_GET['error'] ?? ''] ?? null;

$mensajeOk = null;
if (isset($_GET['creado']))      $mensajeOk = 'Proyecto creado correctamente.';
if (isset($_GET['actualizado'])) $mensajeOk = 'Proyecto actualizado correctamente.';
if (isset($_GET['eliminado']))   $mensajeOk = 'Proyecto eliminado.';

// Modo edición
$editar = null;
if (isset($_GET['editar']) && ctype_digit($_GET['editar'])) {
    $idEd = (int)$_GET['editar'];
    $stmt = $conn->prepare("SELECT id, nombre, descripcion, estado, tecnologias, imagen, usuario_id
                            FROM proyectos WHERE id = ?");
    $stmt->bind_param("i", $idEd);
    $stmt->execute();
    $editar = $stmt->get_result()->fetch_assoc() ?: null;

    if ($editar && !puede_modificar($editar)) {
        header("Location: proyectos.php?error=permiso");
        exit;
    }
}

$resultado = $conn->query(
    "SELECT p.id, p.nombre, p.estado, p.imagen, p.usuario_id, u.usuario AS creador
     FROM proyectos p
     LEFT JOIN usuarios u ON u.id = p.usuario_id
     ORDER BY p.id DESC"
);

$paginaActiva = 'proyectos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Proyectos | LISP Admin</title>

    <link rel="icon" href="/img/favicon.png">

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

    <?php require "_header.php"; ?>

    <main class="admin-main">

        <section class="admin-welcome">
            <p class="section-tag">GESTIÓN DE PROYECTOS</p>
            <h1><?= $editar ? 'Editar' : 'Subir' ?> <span>proyecto</span></h1>
        </section>

        <?php if ($mensajeOk): ?>
            <div class="login-success flash"><?= h($mensajeOk) ?></div>
        <?php endif; ?>

        <?php if ($mensajeError): ?>
            <div class="login-error flash"><?= h($mensajeError) ?></div>
        <?php endif; ?>

        <section class="admin-form-card">

            <form action="subir_proyecto.php" method="POST" enctype="multipart/form-data">

                <?= csrf_campo() ?>
                <?php if ($editar): ?>
                    <input type="hidden" name="id" value="<?= (int)$editar['id'] ?>">
                <?php endif; ?>

                <label for="nombre">Nombre del proyecto</label>
                <input type="text" id="nombre" name="nombre" maxlength="150" required
                       value="<?= h($editar['nombre'] ?? '') ?>">

                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4"><?= h($editar['descripcion'] ?? '') ?></textarea>

                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <?php foreach (['En progreso', 'Finalizado'] as $op): ?>
                        <option value="<?= h($op) ?>"
                            <?= (($editar['estado'] ?? 'En progreso') === $op) ? 'selected' : '' ?>>
                            <?= h($op) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="tecnologias">Tecnologías (separadas por coma)</label>
                <input type="text" id="tecnologias" name="tecnologias" maxlength="255"
                       placeholder="PHP, MySQL, JavaScript"
                       value="<?= h($editar['tecnologias'] ?? '') ?>">

                <label for="imagen">
                    Imagen del proyecto<?= ($editar && $editar['imagen']) ? ' (dejá vacío para conservar la actual)' : '' ?>
                </label>
                <input type="file" id="imagen" name="imagen" accept="image/png, image/jpeg, image/webp">

                <div class="admin-form-actions">
                    <button type="submit"><?= $editar ? 'Guardar cambios' : 'Subir proyecto' ?></button>
                    <?php if ($editar): ?>
                        <a href="proyectos.php" class="btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>

            </form>

        </section>

        <section class="admin-list">

            <h2 class="admin-list-title">Proyectos cargados</h2>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Subido por</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while ($fila = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php if ($fila['imagen']): ?>
                                            <img src="../<?= h($fila['imagen']) ?>" alt="" class="admin-table-thumb">
                                        <?php else: ?>
                                            <span class="text-muted">Sin imagen</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= h($fila['nombre']) ?></td>
                                    <td><?= h($fila['estado'] ?? '—') ?></td>
                                    <td><?= h($fila['creador'] ?? 'Desconocido') ?></td>
                                    <td>
                                        <?php if (puede_modificar($fila)): ?>
                                            <div class="admin-table-actions">
                                                <a href="proyectos.php?editar=<?= (int)$fila['id'] ?>"
                                                   class="admin-table-edit" aria-label="Editar">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                                <form action="eliminar_proyecto.php" method="POST"
                                                      onsubmit="return confirm('¿Eliminar este proyecto?');">
                                                    <?= csrf_campo() ?>
                                                    <input type="hidden" name="id" value="<?= (int)$fila['id'] ?>">
                                                    <button type="submit" class="admin-table-delete" aria-label="Eliminar">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-muted" style="text-align:center; padding:20px;">
                                    Todavía no hay proyectos cargados.
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