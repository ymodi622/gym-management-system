<?php
require_once __DIR__ . '/config/app.php';
use App\SessionManager;
$session = new SessionManager();
$session->start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willty Fitness - Elevate Your Life</title>
    <style>
        :root {
            --primary: #38bdf8;
            --bg-dark: #0f172a;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-dark);
            color: #fff;
        }
        .hero {
            position: relative;
            height: 90vh;
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.7)), url('bg1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0 20px;
        }
        .hero h1 {
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            font-weight: 800;
            margin-bottom: 20px;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.1;
        }
        .hero p {
            font-size: clamp(1rem, 4vw, 1.25rem);
            max-width: 700px;
            color: #94a3b8;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        .hero-btn {
            padding: 16px 32px;
            background: linear-gradient(135deg, #0061eb, #00aeff);
            border-radius: 12px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .hero-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 97, 235, 0.4);
        }
        .contact-details {
            padding: 80px 20px;
            background: rgba(255, 255, 255, 0.02);
            text-align: center;
        }
        .contact-details h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--primary);
        }
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 40px auto 0;
        }
        .contact-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>

<div class="hero">
    <h1>ELEVATE YOUR <br>FITNESS JOURNEY</h1>
    <p>Premium coaching, state-of-the-art equipment, and a community dedicated to pushing boundaries. Your transformation starts now.</p>
    <a href="courses.php" class="hero-btn">Explore Courses</a>
</div>

<div class="contact-details" id="contact">
    <h2>Get In Touch</h2>
    <p>Ready to start? Have questions? Our experts are here to guide you.</p>
    <div class="contact-grid">
        <div class="contact-card">
            <h3>Phone</h3>
            <p>+91 7861048356<br>+91 7227949431</p>
        </div>
        <div class="contact-card">
            <h3>Email</h3>
            <p>yashmodi622@gmail.com</p>
        </div>
        <div class="contact-card">
            <h3>Location</h3>
            <p>Ahemdabad, Gujarat</p>
        </div>
    </div>
</div>

<?php include 'foot.php'; ?>
</body>
</html>