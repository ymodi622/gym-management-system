<?php
require_once __DIR__ . '/../../config/app.php';

use App\Database;

session_start();

$date = date('Y-m-d H:i:s');
$curDay = new DateTime();
$crId = (string)($_SESSION['course_id'] ?? '');
$mbsId = uniqid('mbs_');
$payId = uniqid('pay_');
$name = (string)($_SESSION['user_name'] ?? '');
$usId = (string)($_SESSION['user_id'] ?? '');

$orderId = (string)($_POST['razorpay_order_id'] ?? '');
$paymentId = (string)($_POST['razorpay_payment_id'] ?? '');
$signature = (string)($_POST['razorpay_signature'] ?? '');

if ($orderId === '' || $paymentId === '' || $signature === '') {
    die('Invalid payment callback payload.');
}

if (!hash_equals((string)($_SESSION['razorpay_order_id'] ?? ''), $orderId)) {
    die('Order mismatch detected.');
}

$secretKey = $_ENV['RAZORPAY_KEY_SECRET'] ?? '';
$expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $secretKey);

if (!hash_equals($expectedSignature, $signature)) {
    die('Payment signature verification failed.');
}

$db = Database::getInstance();
$course = $db->fetchOne('SELECT title, description, price, duration FROM courses WHERE course_id = ? LIMIT 1', [$crId]);

if (!$course) {
    die('Course not found.');
}

$price = (float)$course['price'];
$dur = (string)$course['duration'];

if ($dur === '3_mt') {
    $curDay->modify('+3 months');
} elseif ($dur === '6_mt') {
    $curDay->modify('+6 months');
} else {
    $curDay->modify('+12 months');
}

$endDay = $curDay->format('Y-m-d H:i:s');

$member = $db->fetchOne('SELECT member_id FROM members WHERE user_id = ? LIMIT 1', [$usId]);
if ($member) {
    $memId = (string)$member['member_id'];
} else {
    $memId = uniqid('mem_');
    $db->execute('INSERT INTO members (member_id, user_id, name) VALUES (?, ?, ?)', [$memId, $usId, $name]);
}

$paymentOk = $db->execute('INSERT INTO payments (payment_id, user_id, course_id, amount, payment_date, status) VALUES (?, ?, ?, ?, ?, ?)', [$payId, $usId, $crId, $price, $date, 'success']);
$userOk = $db->execute('UPDATE users SET is_member = 1 WHERE user_id = ?', [$usId]);
$membershipOk = $db->execute('INSERT INTO memberships (mbs_id, member_id, course_id, user_id, name, start_date, end_date, amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', [$mbsId, $memId, $crId, $usId, $name, $date, $endDay, $price]);

if ($paymentOk && $userOk && $membershipOk) {
    unset($_SESSION['razorpay_order_id'], $_SESSION['checkout_amount']);
    echo "<script>alert('Course purchased successfully!'); window.location.assign('/GYMAPP/us_profile.php');</script>";
} else {
    echo "<script>alert('Something went wrong!');</script>";
}
?>
