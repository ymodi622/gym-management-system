<?php
require_once __DIR__ . '/../config/app.php';

use App\Database;
use App\SessionManager;

include 'admin_nav.php';

$session = new SessionManager();
$session->start();
$session->checkTimeout();

if (!$session->isAdmin()) {
    header('Location: admin_login.php');
    exit;
}

$db = Database::getInstance();
$name = (string)$_SESSION['admin_name'];
$email = '';
$error = '';

$row = $db->fetchOne('SELECT email FROM admins WHERE name = ? LIMIT 1', [$name]);
if ($row) {
    $email = (string)$row['email'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request token.';
    } elseif (isset($_POST['submitPass'])) {
        $npass = (string)($_POST['newPass'] ?? '');
        $cpass = (string)($_POST['conPass'] ?? '');
        if (strlen($npass) >= 8 && hash_equals($npass, $cpass)) {
            $_SESSION['new_pass_hash'] = password_hash($npass, PASSWORD_BCRYPT);
            header('Location: sendmail.php');
            exit;
        }
        $error = 'Something is wrong with your password.';
    } elseif (isset($_POST['submit'])) {
        $session->destroy();
        header('Location: admin_login.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP verification</title>
    <style>
        .course-form-section {
            background-color: #fff;
            padding: 50px 0;
            margin-bottom: 150px;
        }

        .course-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 37px;
            /* border: 1px solid #444; */
            border-radius: 5px;
            background: #eff2fb;
        }

        .course-form-container h2 {
            margin-bottom: 20px;
        }

        .course-form-input {
            margin-bottom: 15px;
        }

        .course-form-input label {
            display: block;
            margin-bottom: 5px;
        }

        .course-form-input input[type="text"],
        .course-form-input input[type="number"],
        .course-form-input input[type="date"],
        .course-form-input textarea {
            width: 70%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffffff;
            color: #000;
        }

        .course-form-input .rd_lab {
            display: inline;
            width: 10%;
        }

        .course-form-input input[type="file"] {
            display: block;
            margin-top: 5px;
        }

        .course-form-button {
            text-align: center;
        }

        .course-form-button button {
            padding: 10px 30px;
            color: #fff;
            background-color: #0061eb;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 300ms all;
            margin: 12px 13px;
        }

        .course-form-button button:hover {
            background-color: #00aeff;
        }

        .course-form-input {
            margin-bottom: 15px;
        }

        .course-form-input label {
            display: block;
            margin-bottom: 5px;
        }

        .course-form-input input[type="password"] {
            width: 40%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffffff;
            color: #000;
        }

        h1 {
            text-align: center;
        }

        #logOutBtn {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            padding: 4px 18px;
            transform: translateY(24px);
        }

        #logOutBtn ion-icon {
            font-size: 1.4rem;
            margin: 3px;
        }

        .active {
            display: block;
        }

        .inactive {
            display: none;
        }
    </style>
</head>

<body>
    <div class="course-form-section">
        <div class="course-form-container">
            <h1>Admin panel</h1>
            <!-- <form method="POST" autocomplete="off" enctype="multipart/form-data"> -->
            <?php
            echo '<div>Username: ' . $name . '</div> ' .
                '<div>Email: ' . $email . '</div>'
                ?>
            <div class="course-form-button changePass">
                <button name="changeBtn" id="changeBtn" onclick="formDisplay()">Change password</button>
                <form method="POST" class="inactive" id="passForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                    <div class="course-form-input">
                        <label for="course-image">New Password</label>
                        <input type="password" name="newPass" required>
                    </div>
                    <div class="course-form-input">
                        <label for="course-image">Confirm Password</label>
                        <input type="password" name="conPass" required>
                        </div>
                    <button type="submit" name="submitPass">Proceed</button>
                </form>
                <!-- </form> -->
            </div>
        </div>
        <?php if ($error !== ''): ?><p style="color:red; text-align:center;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="course-form-button">
                <button id="logOutBtn" type="submit" name="submit">Log Out<ion-icon
                        name="log-out-outline"></ion-icon></button>
            </div>
        </form>
    </div>
    <script>
        function formDisplay() {
            let form = document.getElementById("passForm")
            let changeBtn = document.getElementById("changeBtn")
            changeBtn.classList.add("inactive")
            form.classList.remove("inactive")
            form.classList.add("active")
        }
    </script>
</body>

</html>
<?php include '../foot.php'; ?>