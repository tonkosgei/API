<?php
require 'C:/Apache24/htdocs/API/Plugins/PHPMailer/src/Exception.php';
require 'C:/Apache24/htdocs/API/Plugins/PHPMailer/src/PHPMailer.php';
require 'C:/Apache24/htdocs/API/Plugins/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/list.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Only process form submission if request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validate inputs
    if (empty($name) || empty($email)) {
        echo "Name and email are required.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format: $email";
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $stmt->close();

    try {
        // Create an instance; passing true enables exceptions
        $mail = new PHPMailer(true);

        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Uncomment for debugging
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tony.kosgei@strathmore.edu';
        $mail->Password   = 'diymbxabsygbrefd'; // <-- app password, not Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('tony.kosgei@strathmore.edu', 'Tony Kosgei');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Hello, $name. Welcome to LK Collections!";
        $mail->Body    = '
            <h2>Thank you for joining our fashion family</h2>
            <p>At <b>LK Collections</b>, we bring you the latest trends, timeless designs, and exclusive offers tailored just for you.</p>
            <p>Stay stylish,</p>
            <p><i>The LK Collections Team</i></p>';
        $mail->AltBody = 'Welcome to LK Collections! Thank you for joining our fashion family. Stay stylish - The LK Collections Team';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Please submit the form to register.";
}
