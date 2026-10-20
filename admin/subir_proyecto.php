<?php
// Crea un proyecto nuevo o actualiza uno existente (si viene "id").
require_once "auth.php";
requerir_login();
require_once "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: proyectos.php");
    exit;
}
csrf_validar();

$id          = (isset($_POST["id"]) && ctype_digit($_POST["id"])) ? (int)$_POST["id"] : 0;
$nombre      = mb_substr(trim($_POST["nombre"] ?? ""), 0, 150);
$descripcion = trim($_POST["descripcion"] ?? "");
$estado      = trim($_POST["estado"] ?? "En progreso");
$tecnologias = mb_substr(trim($_POST["tecnologias"] ?? ""), 0, 255);
$usuarioId   = (int)$_SESSION["usuario_id"];

if (!in_array($estado, ["En progreso", "Finalizado"], true)) {
    $estado = "En progreso";
}

function volver(string $query, int $id = 0): void
{
    $extra = $id > 0 ? "&editar=" . $id : "";
    header("Location: proyectos.php?" . $query . $extra);
    exit;
}

if ($nombre === "") {
    volver("error=vacio", $id);
}

// Si es edición: comprobar que existe y que puede modificarlo
$imagenActual = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT imagen, usuario_id FROM proyectos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $actual = $stmt->get_result()->fetch_assoc();

    if (!$actual) {
        header("Location: proyectos.php");
        exit;
    }
    if (!es_admin() && (int)$actual["usuario_id"] !== $usuarioId) {
        header("Location: proyectos.php?error=permiso");
        exit;
    }
    $imagenActual = $actual["imagen"];
}

$rutaImagen = $imagenActual;

if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {

    $err = $_FILES["imagen"]["error"];

    if ($err === UPLOAD_ERR_INI_SIZE || $err === UPLOAD_ERR_FORM_SIZE) {
        volver("error=imagen", $id);
    }
    if ($err !== UPLOAD_ERR_OK) {
        volver("error=subida", $id);
    }

    $tmp = $_FILES["imagen"]["tmp_name"];

    if ($_FILES["imagen"]["size"] > 5 * 1024 * 1024) {
        volver("error=imagen", $id);
    }

    // El tipo se detecta por el CONTENIDO real, no por lo que declara el navegador
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($tmp);

    $extensiones = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp",
    ];

    if (!isset($extensiones[$mime]) || @getimagesize($tmp) === false) {
        volver("error=imagen", $id);
    }

    $carpeta = __DIR__ . "/../uploads/proyectos/";
    if (!is_dir($carpeta) && !mkdir($carpeta, 0755, true)) {
        volver("error=subida", $id);
    }

    // La extensión sale del tipo detectado, nunca del nombre que manda el usuario
    $nombreArchivo = "proy_" . bin2hex(random_bytes(8)) . "." . $extensiones[$mime];

    if (!move_uploaded_file($tmp, $carpeta . $nombreArchivo)) {
        volver("error=subida", $id);
    }

    $rutaImagen = "uploads/proyectos/" . $nombreArchivo;

    if ($imagenActual) {
        borrar_imagen_proyecto($imagenActual);
    }
}

if ($id > 0) {
    $stmt = $conn->prepare(
        "UPDATE proyectos
         SET nombre = ?, descripcion = ?, estado = ?, tecnologias = ?, imagen = ?
         WHERE id = ?"
    );
    $stmt->bind_param("sssssi", $nombre, $descripcion, $estado, $tecnologias, $rutaImagen, $id);
    $stmt->execute();

    header("Location: proyectos.php?actualizado=1");
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO proyectos (nombre, descripcion, estado, tecnologias, imagen, usuario_id)
     VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("sssssi", $nombre, $descripcion, $estado, $tecnologias, $rutaImagen, $usuarioId);
$stmt->execute();

header("Location: proyectos.php?creado=1");
exit;