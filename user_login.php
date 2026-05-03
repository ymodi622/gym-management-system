<?php
require_once __DIR__ . '/config/app.php';

use App\SessionManager;
use App\User;

$session = new SessionManager();
$session->start();
$session->checkTimeout();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request token.';
    } else {
        $user = new User();
        $result = $user->login((string)($_POST['email'] ?? ''), (string)($_POST['pass'] ?? ''));
        if (!$result) {
            $error = 'Check email or password.';
        } else {
            header('Location: home.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Willty Fitness</title>
    <style>
        :root {
            --primary-color: #0061eb;
            --secondary-color: #00aeff;
            --bg-dark: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--bg-dark);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background: radial-gradient(circle at top right, rgba(0, 97, 235, 0.1), transparent),
                        radial-gradient(circle at bottom left, rgba(0, 174, 255, 0.1), transparent);
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .form-container h2 {
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-input {
            margin-bottom: 20px;
        }

        .form-input label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .form-input input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-input input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 4px rgba(0, 174, 255, 0.1);
        }

        .btnDiv {
            margin-top: 30px;
        }

        .btnDiv button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btnDiv button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 97, 235, 0.4);
        }

        .btnDiv button:active {
            transform: translateY(0);
        }

        .form-container p {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .form-container a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .form-container a:hover {
            color: #fff;
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            text-align: center;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="form-section">
    <div class="form-container">
        <h2>User Login</h2>
        <?php if ($error !== ''): ?>
            <div class="error-message"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-input">
                <label for="mail">Email</label>
                <input type="email" id="mail" name="email" required placeholder="your@email.com">
            </div>
            <div class="form-input">
                <label for="pass">Password</label>
                <input type="password" id="pass" name="pass" required placeholder="••••••••">
            </div>
            <div class="btnDiv"><button type="submit" name="submit">Sign In</button></div>
            <p>Don't have an account? <a href="user_reg.php">Create one</a></p>
        </form>
    </div>
</div>
<?php include 'foot.php'; ?>
</body>
</html>
