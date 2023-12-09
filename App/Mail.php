<?php

namespace App;

use App\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

class Mail
{
    public static function send($to, $subject, $text, $html)
    {

        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;        //Enable verbose debug output
            $mail->isSMTP();
            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth = true;                    //Set the SMTP server to send through
            //Enable SMTP authentication
            $mail->Username   = Config::USER_EMAIL;                     //SMTP username
            $mail->Password   = Config::USER_EMAIL_PASSWORD;                              //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(Config::USER_EMAIL, 'BuGetAPP');    //Add a recipient
            $mail->addAddress($to);               //Name is optional


            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = $text;

            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
