<?php

namespace App\Http\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{

    public function sendResetPasswordMail($user, $link)
    {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->SMTPDebug = 0;                                    // Enable verbose debug output
            $mail->isSMTP();                                         // Set mailer to use SMTP
            $mail->Host = 'smtp.ionos.fr';                                                // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                  // Enable SMTP authentication
            $mail->Username = 'commande@remyvouslivre.fr';             // SMTP username
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port =     587;
            $mail->Priority = 3;
            // $mail->DKIM_domain = 'remyvouslivre.fr';
            // $mail->DKIM_private = '/app/dkim.private';
            // $mail->DKIM_selector = 'mail';
            // $mail->DKIM_passphrase = '';
            // $mail->DKIM_identity = 'commande@remyvouslivre.fr';
            $mail->AddCustomHeader("X-MSMail-Priority: High");
            $mail->AddCustomHeader("Importance: High");
            $mail->setFrom('commande@remyvouslivre.fr', 'Rémy');
            $mail->addAddress($user->email, $user->firstname . " " . $user->lastname);
            $mail->addReplyTo('commande@remyvouslivre.fr', 'Rémy');
            $mail->isHTML(true);
            $mail->Subject = mb_encode_mimeheader("Réinitialisation du mot de passe");
            $mail->Body    = view('mail.resetpassword', compact('link'));
            $mail->send();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
