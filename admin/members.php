<?php
require_once __DIR__ . '/../config/app.php';

use App\Admin;
use App\SessionManager;

$session = new SessionManager();
$session->start();
$session->checkTimeout();

if (!$session->isAdmin()) {
    header('Location: admin_login.php');
    exit;
}

$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;

$admin = new Admin();
$members = $admin->getAllMembers($page, $perPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Directory - Willty Fitness</title>
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
        .id-badge {
            font-family: monospace;
            background: rgba(255, 255, 255, 0.05);
            padding: 4px 8px;
            border-radius: 6px;
            color: #94a3b8;
        }
        .count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background: rgba(129, 140, 248, 0.1);
            color: var(--primary);
            border-radius: 50%;
            font-weight: 700;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<?php include 'admin_nav.php'; ?>

<div class="container">
    <div class="admin-card">
        <div class="card-header">
            <h1>Member Directory</h1>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Courses</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td style="font-weight: 600;"><?= htmlspecialchars((string)($member['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><span class="id-badge">#<?= htmlspecialchars((string)($member['member_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></td>
                            <td><?= htmlspecialchars((string)($member['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string)($member['phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><span class="count-badge"><?= (int)($member['enrolled_courses'] ?? 0) ?></span></td>
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
