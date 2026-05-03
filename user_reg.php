<?php
require_once __DIR__ . '/config/app.php';

use App\SessionManager;
use App\User;
use App\Validator;

$session = new SessionManager();
$session->start();
$session->checkTimeout();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request token.';
    } elseif (($_POST['pass'] ?? '') !== ($_POST['cpass'] ?? '')) {
        $error = 'Password and confirm password do not match.';
    } else {
        $payload = [
            'name' => Validator::sanitizeInput((string)($_POST['name'] ?? '')),
            'email' => Validator::sanitizeInput((string)($_POST['email'] ?? '')),
            'phone' => Validator::sanitizeInput((string)($_POST['phone'] ?? '')),
            'gender' => Validator::sanitizeInput((string)($_POST['gender'] ?? 'other')),
            'password' => (string)($_POST['pass'] ?? ''),
            'height' => (float)($_POST['height'] ?? 0),
            'weight' => (float)($_POST['weight'] ?? 0),
        ];

        $user = new User();
        $result = $user->register($payload);

        if ($result === true) {
            $success = 'Registration completed. You can log in now.';
        } else {
            $error = (string)$result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register - Willty Fitness</title>
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
            max-width: 600px;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-input {
            margin-bottom: 15px;
        }

        .form-input label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .form-input input, .form-input select {
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

        .form-input input:focus, .form-input select:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 4px rgba(0, 174, 255, 0.1);
        }

        .btnDiv {
            margin-top: 30px;
            grid-column: span 2;
        }

        @media (max-width: 600px) {
            .btnDiv {
                grid-column: span 1;
            }
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

        .linkCon {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #94a3b8;
            grid-column: span 2;
        }

        @media (max-width: 600px) {
            .linkCon {
                grid-column: span 1;
            }
        }

        .linkCon a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .linkCon a:hover {
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
            grid-column: span 2;
        }

        .success-message {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #22c55e;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            text-align: center;
            grid-column: span 2;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
<div class="form-section">
    <div class="form-container">
        <h2>User Register</h2>
        <form method="POST" class="form-grid">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            
            <?php if ($error !== ''): ?><div class="error-message"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
            <?php if ($success !== ''): ?><div class="success-message"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

            <div class="form-input"><label for="name">Name</label><input type="text" id="name" name="name" required placeholder="Full Name"></div>
            <div class="form-input">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-input"><label for="height">Height (cm)</label><input type="number" step="0.01" id="height" name="height" required placeholder="175"></div>
            <div class="form-input"><label for="weight">Weight (kg)</label><input type="number" step="0.01" id="weight" name="weight" required placeholder="70"></div>
            <div class="form-input"><label for="phone">Phone</label><input type="tel" id="phone" name="phone" required placeholder="+91 ..."></div>
            <div class="form-input"><label for="email">Email</label><input type="email" id="email" name="email" required placeholder="your@email.com"></div>
            <div class="form-input"><label for="pass">Password</label><input type="password" id="pass" name="pass" required placeholder="••••••••"></div>
            <div class="form-input"><label for="cpass">Confirm Password</label><input type="password" id="cpass" name="cpass" required placeholder="••••••••"></div>
            
            <div class="btnDiv"><button type="submit" name="submit">Create Account</button></div>
            <div class="linkCon"><p>Already have an account? <a href="user_login.php">Login from here</a></p></div>
        </form>
    </div>
</div>
<?php include 'foot.php'; ?>
</body>
</html>
