<?php
session_start();
// include 'conn.php';
// include '../nav2.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
$email = $_SESSION['us_email'];
$name = $_SESSION['name'];
# code...

$generator = "1357902468";


$otp = "";

for ($i = 1; $i <= 4; $i++) {
    $otp .= substr($generator, (rand() % (strlen($generator))), 1);
}
$_SESSION['otp'] = $otp;


$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com;';
    $mail->SMTPAuth = true;
    $mail->Username = 'willtyfitness@gmail.com';
    $mail->Password = 'pjudmtsqjmlheuka';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('willtyfitness@gmail.com', 'WILLTY FITNESS');
    $mail->addAddress($email);
    // $mail->addAddress('receiver2@gfg.com', 'Name');

    $mail->isHTML(true);
    $mail->Subject = 'Complete your Willty Account Registration';
    $mail->Body = 'Hello ' . $name . ',

    <br>Thank you for choosing Willty fitness for your fitness journey!
    <br><b style="font-size: 2rem;letter-spacing:2px">' . $otp . '</b>
    <br>Please enter this code in the provided field to complete your registration process.
    <br>Keep pushing towards your fitness goals!
    <br>Best regards,
    <br>Willty Fitness arena';
    $mail->AltBody = 'Body in plain number for non-HTML mail clients';
    $mail->send();
    header("location:us_verify_otp.php");
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>