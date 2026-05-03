<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_POST['logInBtn'])) {
    header("location:user_login.php");
    exit;
}
?>
<style>
    .navbar {
        height: 80px;
        background: rgba(15, 23, 42, 0.8);
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
        gap: 30px;
    }

    .nav-link a {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-link a:hover {
        color: #fff;
    }

    .nav-link.active a {
        color: #38bdf8;
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
        font-size: 0.9rem;
    }

    .profile-info ion-icon {
        font-size: 1.5rem;
        color: #38bdf8;
    }

    .login-btn {
        padding: 10px 24px;
        background: linear-gradient(135deg, #0061eb, #00aeff);
        border: none;
        border-radius: 8px;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 97, 235, 0.3);
    }
</style>

<nav class="navbar">
    <div class="logo" onclick="location.href='home.php'">
        <img src="admin/logo2.png" alt="Willty Fitness">
    </div>
    
    <ul class="nav-links">
        <li class="nav-link"><a href="home.php">Home</a></li>
        <li class="nav-link"><a href="about.php">About</a></li>
        <li class="nav-link"><a href="courses.php">Courses</a></li>
        <li class="nav-link"><a href="us_profile.php">Profile</a></li>
    </ul>

    <div class="user-actions">
        <?php if (isset($_SESSION['user_name'])): ?>
            <div class="profile-info" onclick="location.href='us_profile.php'" style="cursor:pointer">
                <ion-icon name="person-circle-outline"></ion-icon>
                <span><?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php else: ?>
            <form method="POST">
                <button class="login-btn" name="logInBtn">Log In</button>
            </form>
        <?php endif; ?>
    </div>
</nav>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>