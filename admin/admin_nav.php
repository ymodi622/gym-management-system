<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['logInBtn'])) {
    header("location:admin_login.php");
    exit;
}
?>
<style>
    .navbar {
        height: 80px;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 40px;
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logo {
        height: 50px;
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .logo img {
        height: 100%;
        width: auto;
    }

    .nav-links {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 25px;
    }

    .nav-link a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .nav-link a:hover {
        color: #818cf8;
    }

    .user-actions {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        background: rgba(99, 102, 241, 0.1);
        padding: 8px 16px;
        border-radius: 12px;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    .profile-info ion-icon {
        font-size: 1.2rem;
        color: #818cf8;
    }

    .login-btn {
        padding: 10px 24px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
    }
</style>

<nav class="navbar">
    <div class="logo" onclick="location.href='admin_db.php'">
        <img src="logo2.png" alt="Willty Admin">
    </div>
    
    <ul class="nav-links">
        <li class="nav-link"><a href="admin_db.php">Dashboard</a></li>
        <li class="nav-link"><a href="admin_pro.php">Profile</a></li>
        <li class="nav-link"><a href="memberships.php">Memberships</a></li>
        <li class="nav-link"><a href="members.php">Members</a></li>
    </ul>

    <div class="user-actions">
        <?php if (isset($_SESSION['admin_name'])): ?>
            <div class="profile-info">
                <ion-icon name="shield-checkmark-outline"></ion-icon>
                <span><?php echo htmlspecialchars($_SESSION['admin_name'], ENT_QUOTES, 'UTF-8'); ?> (Admin)</span>
            </div>
        <?php else: ?>
            <form method="POST">
                <button class="login-btn" name="logInBtn">Admin Login</button>
            </form>
        <?php endif; ?>
    </div>
</nav>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>