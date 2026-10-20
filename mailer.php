<?php
// Envío de correos con PHPMailer + SMTP de Gmail.
// Requiere: composer require phpmailer/phpmailer  y  config_mail.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviar_bienvenida(string $correo, string $usuario): bool
{
    $autoload = __DIR__ . "/vendor/autoload.php";
    $config   = __DIR__ . "/config_mail.php";

    if (!is_file($autoload) || !is_file($config)) {
        error_log("LISP mail: falta vendor/autoload.php o config_mail.php");
        return false;
    }

    require_once $autoload;
    require_once $config;

    $nombre = htmlspecialchars($usuario, ENT_QUOTES, 'UTF-8');
    $enlace = SITIO_URL . "/login.php";

    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = "smtp.gmail.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USUARIO;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = "UTF-8";

        $mail->setFrom(MAIL_USUARIO, MAIL_NOMBRE);
        $mail->addAddress($correo, $usuario);

        $mail->isHTML(true);
        $mail->Subject = "¡Bienvenido/a a LISP!";
        $mail->Body = "
            <div style=\"font-family:Arial,sans-serif;max-width:520px;margin:auto;color:#111\">
                <h2 style=\"color:#00b85c\">&lt;/&gt; LISP</h2>
                <p>Hola <strong>{$nombre}</strong>,</p>
                <p>Tu cuenta en el club de Ingeniería en Sistemas <strong>LISP</strong>
                   se creó correctamente. Ya podés iniciar sesión con tu usuario
                   o con este correo.</p>
                <p><a href=\"{$enlace}\"
                      style=\"display:inline-block;background:#00e676;color:#06100a;
                             padding:12px 20px;border-radius:6px;text-decoration:none;
                             font-weight:bold\">Iniciar sesión</a></p>
                <p style=\"color:#666;font-size:13px\">
                    Si no creaste esta cuenta, ignorá este mensaje.
                </p>
            </div>";
        $mail->AltBody = "Hola {$usuario}, tu cuenta en LISP se creó correctamente. Iniciá sesión en: {$enlace}";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("LISP mail error: " . $e->getMessage());
        return false;
    }
}