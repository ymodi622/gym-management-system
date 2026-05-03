<?php
require_once __DIR__ . '/../config/app.php';

use App\Course;
use App\SessionManager;

include 'admin_nav.php';

$session = new SessionManager();
$session->start();
$session->checkTimeout();

if (!$session->isAdmin()) {
    header('Location: admin_login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$session->verifyCsrf($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request token.';
    } else {
        $course = new Course();
        $result = $course->create([
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'pricing' => $_POST['pricing'] ?? 0,
            'duration' => $_POST['duration'] ?? '',
            'creator' => $_SESSION['admin_name'] ?? 'admin',
        ]);

        if ($result === true) {
            header('Location: admin_db.php');
            exit;
        }

        $error = (string)$result;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willty Fitness - Admin Course Creation</title>
</head>
<body>
<div class="course-form-section">
    <div class="course-form-container">
        <h2>Add a New Course</h2>
        <?php if ($error !== ''): ?><p style="color:red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form method="POST" autocomplete="off" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->csrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <div class="course-form-input"><label for="course-title">Title</label><input type="text" id="course-title" name="title" required></div>
            <div class="course-form-input"><label for="course-image">Image</label><input type="file" id="course-image" name="spanImg" accept=".jpg,.jpeg,.png,.webp" required></div>
            <div class="course-form-input"><label for="description">Description</label><textarea id="description" name="description" rows="14" required></textarea></div>
            <div class="course-form-input"><label for="pricing">Pricing</label><input type="number" id="pricing" name="pricing" min="1" required></div>
            <div class="course-form-input">
                <label for="course-pricing">Duration</label>
                <input type="radio" id="3m" name="duration" value="3_mt" required><label for="3m" class="rd_lab">3 months</label><br>
                <input type="radio" id="6m" name="duration" value="6_mt"><label for="6m" class="rd_lab">6 months</label><br>
                <input type="radio" id="12m" name="duration" value="12_mt"><label for="12m" class="rd_lab">12 months</label>
            </div>
            <div class="course-form-button"><button type="submit" name="submit">Add Course</button></div>
        </form>
    </div>
</div>
</body>
</html>
<?php include '../foot.php'; ?>
