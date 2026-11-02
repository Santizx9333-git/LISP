<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

$_SESSION = [];

session_destroy();

header("Location: login.php");
exit;