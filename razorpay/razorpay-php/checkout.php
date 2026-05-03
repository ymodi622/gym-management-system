<?php
require_once __DIR__ . '/../../config/app.php';

use App\Database;
use Razorpay\Api\Api;

session_start();

if (class_exists('Requests') === false) {
    require_once __DIR__ . '/libs/Requests-1.8.0/library/Requests.php';
}

Requests::register_autoloader();

spl_autoload_register(function ($class) {
    $prefix = 'Razorpay\\Api';
    $baseDir = __DIR__ . '/src/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

if (!isset($_SESSION['course_id'])) {
    die('Invalid checkout session.');
}

$db = Database::getInstance();
$courseId = (string)$_SESSION['course_id'];
$course = $db->fetchOne('SELECT title, description, price FROM courses WHERE course_id = ? LIMIT 1', [$courseId]);

if (!$course) {
    die('Course not found.');
}

$name = trim((string)($_POST['name'] ?? $_SESSION['checkout_name'] ?? $_SESSION['user_name'] ?? ''));
$email = trim((string)($_POST['email'] ?? $_SESSION['checkout_email'] ?? $_SESSION['user_email'] ?? ''));
$mobile = trim((string)($_POST['phone'] ?? $_SESSION['checkout_phone'] ?? $_SESSION['phone'] ?? ''));
$amt = (float)$course['price'];

$keyId = $_ENV['RAZORPAY_KEY_ID'] ?? '';
$secretKey = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';

if ($keyId === '' || $secretKey === '') {
    die('Razorpay credentials are not configured.');
}

$api = new Api($keyId, $secretKey);
$order = $api->order->create([
    'amount' => (int)round($amt * 100),
    'payment_capture' => 1,
    'currency' => 'INR',
]);

$_SESSION['razorpay_order_id'] = $order['id'];
$_SESSION['checkout_amount'] = $amt;
?>
<form action="../alerts/success.php" method="post">
    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?>">
    <script src="https://checkout.razorpay.com/v1/checkout.js"
        data-key="<?php echo htmlspecialchars($keyId, ENT_QUOTES, 'UTF-8'); ?>"
        data-amount="<?php echo (int)$order['amount']; ?>"
        data-currency="INR"
        data-order_id="<?php echo htmlspecialchars($order['id'], ENT_QUOTES, 'UTF-8'); ?>"
        data-buttontext="Pay <?php echo htmlspecialchars((string)$amt, ENT_QUOTES, 'UTF-8'); ?>"
        data-name="Willty Fitness"
        data-description="Payment with Razorpay"
        data-image="https://th.bing.com/th/id/OIG.Vbg4eHKxf0mEjkoEA1Za?pid=ImgGn"
        data-prefill.name="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
        data-prefill.email="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
        data-prefill.contact="<?php echo htmlspecialchars($mobile, ENT_QUOTES, 'UTF-8'); ?>"
        data-theme.color="#000"></script>
</form>
<script>document.querySelector('.razorpay-payment-button').click();</script>
