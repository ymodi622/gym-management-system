<?php
session_start();
include '../../conn.php';

// Include Requests only if not already defined
if (class_exists('Requests') === false) {
    require_once __DIR__ . '/libs/Requests-1.8.0/library/Requests.php';
}

try {
    Requests::register_autoloader();

    if (version_compare(Requests::VERSION, '1.6.0') === -1) {
        throw new Exception('Requests class found but did not match');
    }
} catch (\Exception $e) {
    throw new Exception('Requests class found but did not match');
}

spl_autoload_register(function ($class) {
    // project-specific namespace prefix
    $prefix = 'Razorpay\Api';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    //
    // replace the namespace prefix with the base directory,
    // replace namespace separators with directory separators
    // in the relative class name, append with .php
    //
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

//recieving the data
if (isset($_SESSION['course_id'])) {
    $crId = $_SESSION['course_id'];
    $sel = "SELECT * FROM COURSES WHERE course_id = '$crId'";
    if ($result = $conn->query($sel)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['title'];
            $desc = $row['description'];
            $price = $row['price'];
            $dur = $row['duration'];
            $cr = $row['creator'];
        }
    }
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['phone'];
    $amt = $price;
}

use Razorpay\Api\Api;

$keyId = 'rzp_test_zZWVi3mPHmqmJ9';
$secretKey = 'kN4m7CF2utrJMgLqwmdiO5pa';



$api = new Api($keyId, $secretKey);

$order = $api->order->create(
    array(
        'amount' => $amt * 100,
        'payment_capture' => 1,
        'currency' => 'INR',
    )
);

?>

<style>
    /* .razorpay-payment-button{
    display: none;
} */
</style>

<form action="../alerts/success.php" method="post">
    <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="<?php echo $keyId; ?>"
        data-amount="<?php echo $order->amount; ?>" data-currency="INR" data-buttontext="Pay <?php echo $amt ?>"
        data-name="Willty Fitness" data-description="Payment with razorpay" data-image="https://th.bing.com/th/id/OIG.Vbg4eHKxf0mEjkoEA1Za?pid=ImgGn"
        data-prefill.name="<?php echo $name; ?>" data-prefill.email="<?php echo $email; ?>"
        data-prefill.contact="<?php echo $mobile; ?>" data-theme.color="#000"></script>
</form>

<script>document.querySelector(".razorpay-payment-button").click()</script>