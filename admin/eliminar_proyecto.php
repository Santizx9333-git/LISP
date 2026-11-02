<?php
require_once "auth.php";
requerir_login();
require_once "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: proyectos.php");
    exit;
}
csrf_validar();

$id = $_POST["id"] ?? "";
if (!ctype_digit($id)) {
    header("Location: proyectos.php");
    exit;
}
$id = (int)$id;

$stmt = $conn->prepare("SELECT imagen, usuario_id FROM proyectos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$fila = $stmt->get_result()->fetch_assoc();

if (!$fila) {
    header("Location: proyectos.php");
    exit;
}

if (!es_admin() && (int)$fila["usuario_id"] !== (int)$_SESSION["usuario_id"]) {
    header("Location: proyectos.php?error=permiso");
    exit;
}

borrar_imagen_proyecto($fila["imagen"]);

$stmt = $conn->prepare("DELETE FROM proyectos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: proyectos.php?eliminado=1");
exit;