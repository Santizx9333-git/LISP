<?php
require_once "auth.php";
requerir_admin();
require_once "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: integrantes.php");
    exit;
}
csrf_validar();

$id          = (isset($_POST["id"]) && ctype_digit($_POST["id"])) ? (int)$_POST["id"] : 0;
$nombre      = mb_substr(trim($_POST["nombre"] ?? ""), 0, 100);
$apellido    = mb_substr(trim($_POST["apellido"] ?? ""), 0, 100);
$rol         = mb_substr(trim($_POST["rol"] ?? ""), 0, 100);
$descripcion = trim($_POST["descripcion"] ?? "");

if ($nombre === "" || $apellido === "") {
    $extra = $id > 0 ? "&editar=" . $id : "";
    header("Location: integrantes.php?error=vacio" . $extra);
    exit;
}

if ($id > 0) {
    $stmt = $conn->prepare(
        "UPDATE integrantes SET nombre = ?, apellido = ?, rol = ?, descripcion = ? WHERE id = ?"
    );
    $stmt->bind_param("ssssi", $nombre, $apellido, $rol, $descripcion, $id);
} else {
    $stmt = $conn->prepare(
        "INSERT INTO integrantes (nombre, apellido, rol, descripcion) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param("ssss", $nombre, $apellido, $rol, $descripcion);
}
$stmt->execute();

header("Location: integrantes.php?guardado=1");
exit;
