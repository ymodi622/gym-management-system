<?php
require_once __DIR__ . '/config/app.php';
use App\Database;
use App\SessionManager;

$session = new SessionManager();
$session->start();

$db = Database::getInstance();
$flag = 0;
$title = '';
$desc = '';
$price = '';
$dur = '';
$image = '';
$crId = (string)($_SESSION['course_id'] ?? '');
$usId = (string)($_SESSION['user_id'] ?? '');

if ($crId !== '') {
    $course = $db->fetchOne('SELECT * FROM courses WHERE course_id = ? LIMIT 1', [$crId]);
    if ($course) {
        $title = (string)$course['title'];
        $desc = (string)$course['description'];
        $price = (string)$course['price'];
        $dur = (string)$course['duration'];
        $image = (string)$course['image'];
    }

    if ($usId !== '') {
        $membership = $db->fetchOne('SELECT course_id FROM memberships WHERE user_id = ? AND course_id = ? LIMIT 1', [$usId, $crId]);
        if ($membership) {
            $flag = 1;
        }
    }

    if ($dur === '3_mt') {
        $dur = '3 Months';
    } elseif ($dur === '6_mt') {
        $dur = '6 Months';
    } elseif ($dur === '12_mt') {
        $dur = '12 Months';
    }
}

if (isset($_POST['enroll'])) {
    if (!$session->isLoggedIn()) {
        header('Location: user_login.php');
        exit;
    }
    if ($flag === 1) {
        echo "<script>alert('You already enrolled in " . addslashes($title) . "!')</script>";
    } else {
        echo '<script>location.replace("razorpay/razorpay-php/");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?> - Details</title>
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
        .details-section {
            flex: 1;
            padding: 80px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .details-container {
            width: 100%;
            max-width: 1000px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            backdrop-filter: blur(20px);
        }
        @media (max-width: 800px) {
            .details-container {
                grid-template-columns: 1fr;
            }
        }
        .details-image {
            height: 100%;
            min-height: 400px;
            background: url('admin/img/<?php echo $image; ?>') center/cover no-repeat;
        }
        .details-content {
            padding: 50px;
            display: flex;
            flex-direction: column;
        }
        .details-content h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .details-desc {
            color: #94a3b8;
            line-height: 1.8;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .details-meta {
            margin-top: auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 40px;
        }
        .meta-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 16px;
            text-align: center;
        }
        .meta-label {
            display: block;
            font-size: 0.8rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .meta-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary);
        }
        .enroll-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #0061eb, #00aeff);
            border: none;
            border-radius: 16px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .enroll-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 97, 235, 0.4);
        }
        .enroll-btn:disabled {
            background: #475569;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="details-section">
    <div class="details-container">
        <div class="details-image"></div>
        <div class="details-content">
            <h2><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
            <p class="details-desc"><?php echo nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')); ?></p>
            
            <div class="details-meta">
                <div class="meta-item">
                    <span class="meta-label">Duration</span>
                    <span class="meta-value"><?php echo $dur; ?></span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Price</span>
                    <span class="meta-value">₹<?php echo number_format((float)$price, 2); ?></span>
                </div>
            </div>

            <form method="POST">
                <button type="submit" name="enroll" class="enroll-btn" <?php echo $flag === 1 ? 'disabled' : ''; ?>>
                    <?php echo $flag === 1 ? 'Already Enrolled' : 'Enroll Now'; ?>
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'foot.php'; ?>
</body>
</html>