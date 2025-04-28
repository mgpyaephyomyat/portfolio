<?php 
session_start(); // Add this at the top

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(strip_tags(trim($_POST["subject"])));
    $message = htmlspecialchars(strip_tags(trim($_POST["message"])));

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['form_message'] = "Please fill in all fields.";
        $_SESSION['form_status'] = "error";
        header('Location: contact.php'); // Redirect back to your form page
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pyaephyomyat28102004@gmail.com';
        $mail->Password   = 'mkuf haea ssof xwhp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('pyaephyomyat28102004@gmail.com',  $name . ' sent to you: ');
        $mail->addAddress('pyaephyomyat28102004@gmail.com', 'Your Name');
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = $subject .': ';
        $mail->Body    = "
            <p><strong>Requester Name:</strong> {$name}</p>
            <p><strong>Requester Email:</strong> {$email}</p>
            <p><strong>Subject:</strong> {$subject}</p>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        ";
        $mail->AltBody = "Name: {$name}\nEmail: {$email}\nSubject: {$subject}\nMessage:\n{$message}";

        $mail->send();
        $_SESSION['form_message'] = "Message has been sent successfully.";
        $_SESSION['form_status'] = "success";
    } catch (Exception $e) {
        $_SESSION['form_message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        $_SESSION['form_status'] = "error";
    }

    header('Location: index.html'); // Redirect back to your form page
    exit;
}
?>
