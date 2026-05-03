<?php
require_once __DIR__ . '/config/app.php';

use App\SessionManager;

$session = new SessionManager();
$session->start();
$session->checkTimeout();

if (!$session->isLoggedIn()) {
    header('Location: user_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        http_response_code(419);
        die('CSRF validation failed');
    }

    $_SESSION['checkout_name'] = trim((string)($_POST['title'] ?? ''));
    $_SESSION['checkout_phone'] = trim((string)($_POST['phone'] ?? ''));
    $_SESSION['checkout_email'] = trim((string)($_POST['email'] ?? ''));

    header('Location: razorpay/razorpay-php/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
<div class="course-form-section">
    <div class="course-form-container">
        <h2>Checkout</h2>
        <form method="POST" autocomplete="off">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="course-form-input"><label for="course-title">Name</label><input type="text" id="course-title" name="title" required></div>
            <div class="course-form-input"><label for="course-phone">Phone number</label><input type="tel" id="course-phone" name="phone" required></div>
            <div class="course-form-input"><label for="pricing">Email</label><input type="email" id="pricing" name="email" required></div>
            <div class="course-form-button"><button type="submit" name="submit">Pay Now</button></div>
        </form>
    </div>
</div>
<?php include 'foot.php' ?>
</body>
</html>
