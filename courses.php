<?php
require_once __DIR__ . '/config/app.php';
use App\Database;
use App\SessionManager;

$session = new SessionManager();
$session->start();

if (isset($_POST['details'])) {
    $_SESSION["course_id"] = $_POST['details'];
    header("location:course_details.php");
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
    <title>Our Courses - Willty Fitness</title>
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
        .page-header {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(to bottom, rgba(56, 189, 248, 0.05), transparent);
        }
        .page-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .page-header p {
            color: #94a3b8;
            font-size: 1.1rem;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            padding: 40px;
            max-width: 1400px;
            margin: 0 auto;
            flex: 1;
        }
        .course-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(10px);
        }
        .course-card:hover {
            transform: translateY(-10px);
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            background: rgba(255, 255, 255, 0.05);
        }
        .course-img-wrapper {
            height: 200px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }
        .course-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .course-card:hover .course-img-wrapper img {
            transform: scale(1.1);
        }
        .course-content {
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .course-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fff;
        }
        .course-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid var(--glass-border);
        }
        .course-duration {
            color: #94a3b8;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .details-btn {
            padding: 10px 20px;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 12px;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .details-btn:hover {
            background: var(--primary);
            color: #000;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="page-header">
    <h1>Transformative Courses</h1>
    <p>Select a program tailored to your goals and start your journey today.</p>
</div>

<div class="courses-grid">
    <?php foreach ($courses as $row): 
        $dur = $row['duration'];
        if ($dur == "3_mt") $dur = "3 Months";
        elseif ($dur == "6_mt") $dur = "6 Months";
        elseif ($dur == "12_mt") $dur = "12 Months";
    ?>
        <div class="course-card">
            <div class="course-img-wrapper">
                <img src="admin/img/<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Course Image">
            </div>
            <div class="course-content">
                <h3 class="course-title"><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <div class="course-info">
                    <span class="course-duration">
                        <ion-icon name="time-outline"></ion-icon>
                        <?php echo $dur; ?>
                    </span>
                    <form method="POST">
                        <button class="details-btn" name="details" value="<?php echo $row['course_id']; ?>">View Details</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'foot.php'; ?>
</body>
</html>