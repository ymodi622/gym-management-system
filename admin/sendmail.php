<?php
require_once __DIR__ . '/../config/app.php';

use App\Database;
use App\SessionManager;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

$session = new SessionManager();
$session->start();
$session->checkTimeout();

$db = Database::getInstance();
$addr = '';
$name = 'User';

if ($session->isAdmin()) {
    $name = (string)($_SESSION['admin_name'] ?? 'Admin');
    $row = $db->fetchOne('SELECT email FROM admins WHERE name = ? LIMIT 1', [$name]);
    $addr = (string)($row['email'] ?? '');
} elseif (isset($_SESSION['user_email'])) {
    $email = (string)$_SESSION['user_email'];
    $name = (string)($_SESSION['user_name'] ?? 'User');
    $row = $db->fetchOne('SELECT email FROM users WHERE email = ? LIMIT 1', [$email]);
    $addr = (string)($row['email'] ?? '');
}

if ($addr === '') {
    die('Unable to resolve recipient email. Please ensure you are logged in correctly.');
}

// Prevent redundant OTP sends within 60 seconds
$lastSent = (int)($_SESSION['otp_sent_at'] ?? 0);
if (isset($_SESSION['otp']) && (time() - $lastSent) < 60) {
    header('Location: verify_otp.php');
    exit;
}

$otp = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$_SESSION['otp'] = $otp;
$_SESSION['otp_sent_at'] = time();
$_SESSION['otp_expires'] = time() + 300;

$mail = new PHPMailer(true);

try {
    // Mail Server Settings
    $mail->isSMTP();
    $mail->Host = app_env('MAIL_HOST', 'smtp.gmail.com');
    $mail->SMTPAuth = true;
    $mail->Username = app_env('MAIL_USER', '');
    $mail->Password = app_env('MAIL_PASS', '');
    $mail->SMTPSecure = app_env('MAIL_ENCRYPTION', 'tls');
    $mail->Port = (int)app_env('MAIL_PORT', '587');

    // Check for credentials
    if (empty($mail->Username) || empty($mail->Password)) {
        throw new Exception('SMTP credentials are not configured in the .env file.');
    }

    $from = app_env('MAIL_FROM', $mail->Username);
    $fromName = app_env('MAIL_FROM_NAME', 'WILLTY FITNESS');

    if (empty($from)) {
        throw new Exception('The "From" email address is not configured.');
    }

    // Recipients
    $mail->setFrom($from, $fromName);
    $mail->addAddress($addr);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'OTP Confirmation - Willty Fitness';
    $mail->Body = '
        <div style="font-family: sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
            <h2 style="color: #0061eb; text-align: center;">OTP Verification</h2>
            <p>Hello ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ',</p>
            <p>Your one-time password for verification is:</p>
            <div style="text-align: center; margin: 30px 0;">
                <b style="font-size: 2.5rem; letter-spacing: 5px; color: #0f172a; background: #f1f5f9; padding: 10px 20px; border-radius: 8px;">' . $otp . '</b>
            </div>
            <p style="color: #64748b; font-size: 0.9rem;">This code will expire in 5 minutes. If you did not request this, please ignore this email.</p>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            <p style="text-align: center; color: #94a3b8; font-size: 0.8rem;">&copy; ' . date('Y') . ' Willty Fitness Gym. All rights reserved.</p>
        </div>';
    $mail->AltBody = 'Hello ' . $name . ', Your OTP is ' . $otp . '. It expires in 5 minutes.';

    $mail->send();

    if ($session->isAdmin()) {
        $_SESSION['admin_mail'] = $addr;
    }

    header('Location: verify_otp.php');
    exit;
} catch (Exception $e) {
    die('Message could not be sent. <br><b>Mailer Error:</b> ' . $e->getMessage() . '<br><br>Please check your .env file and ensure MAIL_USER, MAIL_PASS, and MAIL_FROM are correctly set.');
}
?>
