<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// FIX: Set Timezone to Philippines
date_default_timezone_set('Asia/Manila');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $name      = strip_tags(trim($_POST["name"]));
    $email     = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject   = strip_tags(trim($_POST["subject"]));
    $message   = nl2br(htmlspecialchars(trim($_POST["message"])));
    
    // The date will now capture correctly for Asia/Manila
    $date_sent = date("F j, Y, g:i a");

    // Simple validation
    if (empty($name) || empty($email) || empty($message)) {
        header("Location: ../index.php?status=error#contact");
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // --- SERVER SETTINGS ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'dost.asenxo@gmail.com'; 
        $mail->Password   = 'qkoczbdhdfcmqnoi'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- RECIPIENTS ---
        $mail->setFrom('dost.asenxo@gmail.com', 'ASENXO Portal');
        $mail->addAddress('dost.asenxo@gmail.com');     
        $mail->addReplyTo($email, $name);               

        // --- EMBED LOGO ---
        $mail->addEmbeddedImage('../src/img/services/logo-header.png', 'asenxo_logo');

        // --- CONTENT ---
        $mail->isHTML(true);
        $mail->Subject = "ASENXO Inquiry: " . ($subject ?: "New Message from $name");

        $mail->Body = "
        <html>
        <head>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 0; background-color: #ffffff; }
                .wrapper { width: 100%; background-color: #ffffff; }
                .container { width: 100%; max-width: 650px; margin: 0 auto; border: 1px solid #eeeeee; }
                
                .header { background-color: #000000; padding: 60px 20px; text-align: center; }
                .header img { width: 400px; height: auto; max-width: 100%; }
                
                .content { padding: 50px 40px; color: #171717; text-align: center; }
                .headline { font-size: 22px; line-height: 1.6; margin-bottom: 50px; color: #333; font-weight: 400; }
                
                .details-table { width: 100%; border-collapse: collapse; text-align: left; }
                .details-table td { padding: 20px 10px; border-bottom: 1px solid #f5f5f5; vertical-align: top; }
                .label { font-weight: bold; width: 130px; color: #000; font-size: 13px; text-transform: uppercase; letter-spacing: 1.5px; }
                .value { color: #555; font-size: 16px; }
                
                .footer { padding: 30px; font-size: 12px; color: #aaaaaa; text-align: center; background-color: #fafafa; }
            </style>
        </head>
        <body>
            <div class='wrapper'>
                <div class='container'>
                    <div class='header'>
                        <img src='cid:asenxo_logo' alt='ASENXO'>
                    </div>
                    <div class='content'>
                        <p class='headline'>You have received a new message from your<br>website contact form.</p>
                        <table class='details-table'>
                            <tr><td class='label'>Name:</td><td class='value'>$name</td></tr>
                            <tr><td class='label'>Mailed From:</td><td class='value'>$email</td></tr>
                            <tr><td class='label'>Date:</td><td class='value'>$date_sent</td></tr>
                            <tr><td class='label'>Message:</td><td class='value' style='line-height: 1.8;'>$message</td></tr>
                        </table>
                    </div>
                    <div class='footer'>
                        &copy; " . date("Y") . " DOST Region VI - ASENXO Project. All rights reserved.
                    </div>
                </div>
            </div>
        </body>
        </html>";

        $mail->send();
        header("Location: ../index.php?status=success#contact");
        exit;

    } catch (Exception $e) {
        header("Location: ../index.php?status=error#contact");
        exit;
    }
} else {
    header("Location: ../index.php");
    exit;
}