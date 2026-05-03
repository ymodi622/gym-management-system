<?php
require_once __DIR__ . '/../config/app.php';

use App\Database;
use App\SessionManager;

$session = new SessionManager();
$session->start();
$session->checkTimeout();

$db = Database::getInstance();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Verification failed. Please try again.';
    } elseif (time() > (int)($_SESSION['otp_expires'] ?? 0)) {
        $error = 'This OTP has expired. Please request a new one.';
    } else {
        $otp = (string)($_SESSION['otp'] ?? '');
        $digits = $_POST['otp_digits'] ?? [];
        $inputOtp = implode('', $digits);

        if (!hash_equals($otp, $inputOtp)) {
            $error = 'The OTP you entered is incorrect.';
        } else {
            $newHash = (string)($_SESSION['new_pass_hash'] ?? '');
            if ($newHash === '') {
                $error = 'Missing password reset session.';
            } elseif ($session->isAdmin()) {
                $addr = (string)($_SESSION['admin_mail'] ?? '');
                $ok = $db->execute('UPDATE admins SET password = ? WHERE email = ?', [$newHash, $addr]);
                if ($ok) {
                    unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['new_pass_hash'], $_SESSION['otp_sent_at']);
                    header('Location: admin_pro.php');
                    exit;
                }
                $error = 'Could not update password. Please try again.';
            } else {
                $addr = (string)($_SESSION['user_email'] ?? '');
                $ok = $db->execute('UPDATE users SET password = ? WHERE email = ?', [$newHash, $addr]);
                if ($ok) {
                    unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['new_pass_hash'], $_SESSION['otp_sent_at']);
                    header('Location: /GYMAPP/us_profile.php');
                    exit;
                }
                $error = 'Could not update password. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Willty Fitness</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.08);
        }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: #fff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.1), transparent);
        }
        .otp-card {
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 50px;
            width: 100%;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .otp-card h1 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0 0 10px;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .otp-card p {
            color: #94a3b8;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 30px;
        }
        .otp-inputs input {
            width: 50px;
            height: 60px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 12px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            transition: all 0.3s ease;
        }
        .otp-inputs input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            background: rgba(15, 23, 42, 0.8);
        }
        .verify-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none;
            border-radius: 14px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .verify-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }
        .error-msg {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 0.85rem;
        }
        .resend {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #64748b;
        }
        .resend a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="otp-card">
        <h1>Verify Account</h1>
        <p>We've sent a 6-digit verification code to your email. Enter it below to proceed.</p>

        <?php if ($error !== ''): ?>
            <div class="error-msg"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="POST" id="otp-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="otp-inputs">
                <input type="text" name="otp_digits[]" maxlength="1" pattern="\d" required autocomplete="off">
                <input type="text" name="otp_digits[]" maxlength="1" pattern="\d" required autocomplete="off">
                <input type="text" name="otp_digits[]" maxlength="1" pattern="\d" required autocomplete="off">
                <input type="text" name="otp_digits[]" maxlength="1" pattern="\d" required autocomplete="off">
                <input type="text" name="otp_digits[]" maxlength="1" pattern="\d" required autocomplete="off">
                <input type="text" name="otp_digits[]" maxlength="1" pattern="\d" required autocomplete="off">
            </div>
            <button type="submit" name="verify" class="verify-btn">Verify & Update</button>
        </form>

        <div class="resend">
            Didn't receive code? <a href="sendmail.php">Resend OTP</a>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-inputs input');
        
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length > 0 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Handle paste
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').slice(0, 6).split('');
                pasteData.forEach((char, i) => {
                    if (inputs[i]) {
                        inputs[i].value = char;
                    }
                });
                if (inputs[pasteData.length - 1]) inputs[pasteData.length - 1].focus();
            });
        });
    </script>
</body>
</html>
