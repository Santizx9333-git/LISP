<?php
require_once "auth.php";
requerir_admin();
require_once "../conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: integrantes.php");
    exit;
}
csrf_validar();

$id = $_POST["id"] ?? "";
if (ctype_digit($id)) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM integrantes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: integrantes.php?eliminado=1");
exit;
