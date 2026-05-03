<?php
require_once __DIR__ . '/../config/app.php';

use App\Admin;
use App\SessionManager;

$session = new SessionManager();
$session->start();
$session->checkTimeout();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request token.';
    } else {
        $admin = new Admin();
        $result = $admin->login((string)($_POST['email'] ?? ''), (string)($_POST['pass'] ?? ''));

        if (!$result || !$session->isAdmin()) {
            $error = 'Check email or password.';
        } else {
            header('Location: admin_db.php');
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
    <title>Admin Login - Willty Fitness</title>
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #a855f7;
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
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.15), transparent),
                        radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.15), transparent);
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .form-container h2 {
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 800;
            text-align: center;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.025em;
        }

        .form-input {
            margin-bottom: 24px;
        }

        .form-input label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.875rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-input input {
            width: 100%;
            padding: 14px 18px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        .form-input input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            background: rgba(15, 23, 42, 0.8);
        }

        .btnDiv {
            margin-top: 32px;
        }

        .btnDiv button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btnDiv button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(99, 102, 241, 0.5);
            filter: brightness(1.1);
        }

        .btnDiv button:active {
            transform: translateY(0);
        }

        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.875rem;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>
<?php include 'admin_nav2.php'; ?>
<div class="form-section">
    <div class="form-container">
        <h2>Admin Portal</h2>
        <?php if ($error !== ''): ?>
            <div class="error-message"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="form-input">
                <label for="mail">Email Address</label>
                <input type="email" id="mail" name="email" required placeholder="admin@willtyfitness.com">
            </div>
            <div class="form-input">
                <label for="pass">Access Password</label>
                <input type="password" id="pass" name="pass" required placeholder="••••••••">
            </div>
            <div class="btnDiv"><button type="submit" name="submit">Verify & Login</button></div>
        </form>
    </div>
</div>
<?php include '../foot.php'; ?>
</body>
</html>
