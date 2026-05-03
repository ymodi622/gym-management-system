<?php
require_once __DIR__ . '/config/app.php';

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
        $digits = $_POST['otp_digits'] ?? [];
        $inputOtp = implode('', $digits);
        $otp = (string)($_SESSION['otp'] ?? '');

        if (!hash_equals($otp, $inputOtp)) {
            $error = 'The OTP you entered is incorrect.';
        } else {
            $name = (string)($_SESSION['name'] ?? '');
            $email = (string)($_SESSION['us_email'] ?? '');
            $ph = (string)($_SESSION['phone'] ?? '');
            $gen = (string)($_SESSION['gender'] ?? 'other');
            $pass = (string)($_SESSION['pass'] ?? '');
            $us = (string)($_SESSION['us'] ?? uniqid('willuser_'));
            $hg = (float)($_SESSION['height'] ?? 0);
            $wt = (float)($_SESSION['weight'] ?? 0);

            $hash = password_hash($pass, PASSWORD_BCRYPT);
            $ok = $db->execute(
                'INSERT INTO users (user_id, name, gender, phone, email, password, height, weight, is_member) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)',
                [$us, $name, $gen, $ph, $email, $hash, $hg, $wt]
            );

            if ($ok) {
                unset($_SESSION['otp'], $_SESSION['otp_expires'], $_SESSION['pass'], $_SESSION['otp_sent_at']);
                header('Location: user_login.php');
                exit;
            }

            $error = 'Something went wrong during registration. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Registration - Willty Fitness</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0ea5e9;
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
            background: radial-gradient(circle at top right, rgba(14, 165, 233, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(56, 189, 248, 0.1), transparent);
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
            background: linear-gradient(to right, #fff, #38bdf8);
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
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
            background: rgba(15, 23, 42, 0.8);
        }
        .verify-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
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
            box-shadow: 0 10px 20px -5px rgba(14, 165, 233, 0.4);
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
        <h1>Registration</h1>
        <p>Complete your registration by entering the 6-digit code sent to your email.</p>

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
            <button type="submit" name="verify" class="verify-btn">Complete Setup</button>
        </form>

        <div class="resend">
            Didn't receive code? <a href="us_sendmail.php">Resend OTP</a>
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
