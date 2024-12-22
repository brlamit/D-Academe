<?php
require 'utils/PHPMailer/src/PHPMailer.php';
require 'utils/PHPMailer/src/SMTP.php';
require 'utils/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'constants.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;  // Use constants from config.php
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;  // Use constants from config.php
        $mail->Password = SMTP_PASSWORD;  // Use constants from config.php
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('your-email@gmail.com', "$name");
        $mail->addAddress('sunilsapkota9@gmail.com'); 
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "You have received a new message from the contact form.\n\n" .
                      "Name: $name\n" .
                      "Email: $email\n" .
                      "Message:\n$message";
        if ($mail->send()) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email.";
        }
    } catch (Exception $e) {
        echo "Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request method.";
    echo '<br><br>';
    echo '<button onclick="window.location.href=\'index.php\'">Go Back</button>';
}

?>