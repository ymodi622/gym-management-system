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

$db = Database::getInstance();
$error = '';
$success = '';

if (isset($_POST['cancel'])) {
    $mbsId = (string)$_POST['cancel'];
    // Logic to cancel membership
    $membership = $db->fetchOne("SELECT member_id FROM memberships WHERE mbs_id = ?", [$mbsId]);
    if ($membership) {
        $memId = $membership['member_id'];
        $db->execute("DELETE FROM memberships WHERE mbs_id = ?", [$mbsId]);
        
        // Check if user has other memberships
        $otherMbs = $db->fetchAll("SELECT mbs_id FROM memberships WHERE member_id = ?", [$memId]);
        if (empty($otherMbs)) {
            $db->execute("DELETE FROM members WHERE member_id = ?", [$memId]);
        }
        $success = "Membership cancelled successfully.";
    }
}

$memberships = $db->fetchAll("SELECT * FROM memberships");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memberships Management - Willty Fitness</title>
    <style>
        :root {
            --primary: #818cf8;
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
        }
        .container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .admin-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 40px;
            backdrop-filter: blur(20px);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .card-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .refresh-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .refresh-btn:hover { transform: rotate(180deg); }
        
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            text-align: left;
            padding: 15px;
            color: #64748b;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--glass-border);
        }
        td {
            padding: 20px 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02);
            font-size: 0.95rem;
        }
        tr:hover td {
            background: rgba(255, 255, 255, 0.01);
        }
        .cancel-btn {
            padding: 8px 16px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .cancel-btn:hover {
            background: #ef4444;
            color: #fff;
        }
        .status-msg {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
        }
        .status-success { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
    </style>
</head>
<body>
<?php include 'admin_nav.php'; ?>

<div class="container">
    <div class="admin-card">
        <div class="card-header">
            <h1>Memberships</h1>
            <button class="refresh-btn" onclick="location.reload()"><ion-icon name="refresh-circle-outline"></ion-icon></button>
        </div>

        <?php if ($success): ?>
            <div class="status-msg status-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Course</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($memberships as $row): 
                        $course = $db->fetchOne("SELECT title FROM courses WHERE course_id = ?", [$row['course_id']]);
                    ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($course['title'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['start_date'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['end_date'])); ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this membership?')">
                                    <button type="submit" name="cancel" value="<?php echo $row['mbs_id']; ?>" class="cancel-btn">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../foot.php'; ?>
</body>
</html>