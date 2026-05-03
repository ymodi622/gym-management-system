<?php
require_once __DIR__ . '/config/app.php';

use App\Database;
use App\SessionManager;

$session = new SessionManager();
$session->start();
$session->checkTimeout();

if (!$session->isLoggedIn()) {
    $session->destroy();
    header('Location: user_login.php');
    exit;
}

$db = Database::getInstance();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        die('CSRF validation failed');
    }

    if (isset($_POST['submit'])) {
        $session->destroy();
        header('Location: user_login.php');
        exit;
    }

    if (isset($_POST['usmChangeBtn'])) {
        $newUsm = trim((string)($_POST['usmChange'] ?? ''));
        $email = (string)($_SESSION['user_email'] ?? '');
        if ($newUsm !== '') {
            $ok = $db->execute('UPDATE users SET name = ? WHERE email = ?', [$newUsm, $email]);
            if ($ok) {
                $_SESSION['user_name'] = $newUsm;
                header('Location: us_profile.php');
                exit;
            }
            $error = 'Unable to update username.';
        }
    }

    if (isset($_POST['submitPass'])) {
        $npass = (string)($_POST['newPass'] ?? '');
        $cpass = (string)($_POST['conPass'] ?? '');
        if (strlen($npass) >= 8 && hash_equals($npass, $cpass)) {
            $_SESSION['new_pass_hash'] = password_hash($npass, PASSWORD_BCRYPT);
            header('Location: admin/sendmail.php');
            exit;
        }
        $error = 'Something wrong with your password!';
    }
}

$usId = (string)($_SESSION['user_id'] ?? '');
$memberships = $db->fetchAll('SELECT course_id, end_date FROM memberships WHERE user_id = ?', [$usId]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - Willty Fitness</title>
    <style>
        :root {
            --primary: #38bdf8;
            --bg-dark: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .profile-container {
            max-width: 1000px;
            margin: 60px auto;
            padding: 0 20px;
            width: 100%;
            flex: 1;
        }
        .profile-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 40px;
            backdrop-filter: blur(20px);
            margin-bottom: 40px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
        }
        .profile-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .info-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 20px;
            position: relative;
        }
        .info-label {
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            display: block;
        }
        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }
        .edit-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }
        .edit-btn:hover {
            transform: scale(1.2);
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 40px 0 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .enrolled-courses {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .course-item {
            background: rgba(56, 189, 248, 0.05);
            border: 1px solid rgba(56, 189, 248, 0.1);
            padding: 25px;
            border-radius: 24px;
        }
        .course-item h3 {
            margin: 0 0 10px 0;
            color: var(--primary);
        }
        .course-meta {
            font-size: 0.9rem;
            color: #94a3b8;
        }
        .btn-logout {
            margin-top: 40px;
            padding: 14px 28px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: fit-content;
        }
        .btn-logout:hover {
            background: #ef4444;
            color: #fff;
        }
        .modal-form {
            margin-top: 15px;
            display: none;
        }
        .modal-form.active {
            display: block;
        }
        .input-group {
            display: flex;
            gap: 10px;
        }
        input {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--glass-border);
            padding: 10px 15px;
            border-radius: 10px;
            color: #fff;
            flex: 1;
        }
        .update-btn {
            background: var(--primary);
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }
        .inactive { display: none; }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <ion-icon name="person-circle" style="font-size: 4rem; color: var(--primary);"></ion-icon>
            <h1>User Profile</h1>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Full Name</span>
                <span class="info-value"><?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <button class="edit-btn" onclick="toggleEdit('nameForm')"><ion-icon name="create-outline"></ion-icon></button>
                <form method="POST" id="nameForm" class="modal-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="input-group">
                        <input type="text" name="usmChange" placeholder="New Name">
                        <button type="submit" name="usmChangeBtn" class="update-btn">Save</button>
                    </div>
                </form>
            </div>
            <div class="info-item">
                <span class="info-label">Email Address</span>
                <span class="info-value"><?php echo htmlspecialchars($_SESSION['user_email'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Phone</span>
                <span class="info-value"><?php echo htmlspecialchars($_SESSION['phone'] ?? 'N/A', ENT_QUOTES, 'UTF-8'); ?></span>
                <button class="edit-btn" onclick="toggleEdit('phoneForm')"><ion-icon name="create-outline"></ion-icon></button>
                <form method="POST" id="phoneForm" class="modal-form">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="input-group">
                        <input type="text" name="phChange" placeholder="New Phone">
                        <button type="submit" name="phChangeBtn" class="update-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <button class="btn-logout" onclick="toggleEdit('passForm')" style="background: rgba(56, 189, 248, 0.1); border-color: rgba(56, 189, 248, 0.2); color: var(--primary); margin-right: 10px;">Change Password</button>
        <form method="POST" id="passForm" class="modal-form" style="max-width: 300px;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <input type="password" name="newPass" placeholder="New Password" style="margin-bottom: 10px; width: 100%;">
            <input type="password" name="conPass" placeholder="Confirm Password" style="margin-bottom: 10px; width: 100%;">
            <button type="submit" name="submitPass" class="update-btn" style="width: 100%;">Update Password</button>
        </form>

        <form method="POST" style="display: inline-block;">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" name="submit" class="btn-logout">Log Out</button>
        </form>
    </div>

    <h2 class="section-title"><ion-icon name="ribbon-outline"></ion-icon> Enrolled Courses</h2>
    <div class="enrolled-courses">
        <?php if (empty($memberships)): ?>
            <p style="color: #64748b;">You haven't enrolled in any courses yet.</p>
        <?php else: ?>
            <?php foreach ($memberships as $memberRow): 
                $courseRow = $db->fetchOne('SELECT title FROM courses WHERE course_id = ? LIMIT 1', [$memberRow['course_id']]);
                if ($courseRow):
            ?>
                <div class="course-item">
                    <h3><?php echo htmlspecialchars($courseRow['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <div class="course-meta">
                        <p>Valid until: <?php echo date('M d, Y', strtotime($memberRow['end_date'])); ?></p>
                    </div>
                </div>
            <?php endif; endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'foot.php'; ?>

<script>
    function toggleEdit(id) {
        document.getElementById(id).classList.toggle('active');
    }
</script>
</body>
</html>
