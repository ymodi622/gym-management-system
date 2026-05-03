<?php
require_once __DIR__ . '/../config/app.php';
use App\Database;
use App\SessionManager;

$session = new SessionManager();
$session->start();

if (!isset($_SESSION['admin_name'])) {
    header("location:admin_login.php");
    exit;
}

if (isset($_POST['add'])) {
    header("location:course_add.php");
    exit;
}
if (isset($_POST['edit'])) {
    $_SESSION["course_id"] = $_POST['edit'];
    header("location:course_edit.php");
    exit;
}

$db = Database::getInstance();
$courses = $db->fetchAll("SELECT * FROM courses");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Willty Fitness</title>
    <style>
        :root {
            --primary: #818cf8;
            --secondary: #a855f7;
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
        .dashboard-header {
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }
        .dashboard-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .add-course-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .add-course-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 0 40px 40px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }
        .course-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .course-card:hover {
            border-color: rgba(129, 140, 248, 0.3);
            background: rgba(255, 255, 255, 0.05);
        }
        .course-img {
            height: 180px;
            background-size: cover;
            background-position: center;
        }
        .course-body {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .course-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .course-meta {
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 20px;
        }
        .edit-btn {
            margin-top: auto;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .edit-btn:hover {
            background: #fff;
            color: #000;
        }
    </style>
</head>
<body>
<?php include 'admin_nav.php'; ?>

<div class="dashboard-header">
    <h1>Course Management</h1>
    <form method="POST">
        <button type="submit" name="add" class="add-course-btn">
            <ion-icon name="add-circle-outline"></ion-icon>
            Add New Course
        </button>
    </form>
</div>

<div class="courses-grid">
    <?php foreach ($courses as $row): 
        $dur = $row['duration'];
        if ($dur == "3_mt") $dur = "3 Months";
        elseif ($dur == "6_mt") $dur = "6 Months";
        elseif ($dur == "12_mt") $dur = "12 Months";
    ?>
        <div class="course-card">
            <div class="course-img" style="background-image: url('img/<?php echo $row['image']; ?>')"></div>
            <div class="course-body">
                <h3 class="course-title"><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <div class="course-meta">
                    <span><ion-icon name="time-outline"></ion-icon> <?php echo $dur; ?></span>
                    <span style="margin-left: 15px;"><ion-icon name="cash-outline"></ion-icon> ₹<?php echo $row['price']; ?></span>
                </div>
                <form method="POST">
                    <button type="submit" name="edit" value="<?php echo $row['course_id']; ?>" class="edit-btn">Edit Course</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../foot.php'; ?>
</body>
</html>