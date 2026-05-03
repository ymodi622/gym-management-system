<?php
session_start();
include '../conn.php';
// include '../nav2.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';
if (isset($_SESSION['admin_name'])) {

    $name = $_SESSION['admin_name'];
    $sel = "SELECT * FROM `admins` WHERE name = '$name'";
    if ($result = mysqli_query($conn, $sel)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $addr = $row['email'];
        }
        $generator = "1357902468";


        $otp = "";

        for ($i = 1; $i <= 4; $i++) {
            $otp .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        $_SESSION['otp'] = $otp;
    }
} elseif (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
    $sel = "SELECT * FROM `users` WHERE email = '$email'";
    if ($result = mysqli_query($conn, $sel)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $addr = $row['email'];
        }
        $generator = "1357902468";


        $otp = "";

        for ($i = 1; $i <= 4; $i++) {
            $otp .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        $_SESSION['otp'] = $otp;
    }
}
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com;';
    $mail->SMTPAuth = true;
    $mail->Username = 'willtyfitness@gmail.com';
    $mail->Password = 'rmtmvrmsyqifqlns';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('willtyfitness@gmail.com', 'WILLTY FITNESS');
    $mail->addAddress($addr);
    // $mail->addAddress('receiver2@gfg.com', 'Name');

    $mail->isHTML(true);
    $mail->Subject = 'OTP Confirmation';
    $mail->Body = 'Hello ' . $name . ',

    <br>We have received a request to change your password for your admin account.
    <br><b style="font-size: 2rem;letter-spacing:2px">' . $otp . '</b>
    <br>Please enter this code in the provided field to complete your password change process.
    <br>Keep pushing towards your fitness goals!
    <br>Best regards,
    <br>Willty Fitness arena';
    $mail->AltBody = 'Body in plain number for non-HTML mail clients';
    $mail->send();
    header("location:verify_otp.php");
    $_SESSION['admin_mail'] = $addr;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>