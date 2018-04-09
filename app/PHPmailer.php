<?php

require "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function mailer($mailTo, $subject, $message)
{
    try {

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.yandex.ru";
        $mail->SMTPAuth = true;
        $mail->Username = "PHPLucifer@yandex.ru";
        $mail->Password = "LuciferPHP";
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom("PHPLucifer@yandex.ru", 'Mailer');
        $mail->addAddress("$mailTo");
        $mail->addReplyTo('PHPLucifer@yandex.ru');
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = "$subject";
        $mail->Body = "$message";
        $mail->send();

        echo "Сообщение отправлено на почту : $mailTo";
    } catch (Exception $e) {
        echo 'Сообщение не отправлено, ошибка : ', $mail->ErrorInfo;
    }
}