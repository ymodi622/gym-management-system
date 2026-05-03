<?php
session_start();
header('Access-Control-Allow-Origin: http://localhost');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS,PUT,DELETE');
header('Access-Control-Allow-Headers: Content-type, X-Auth-Token, Oigirn, Authorization');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;1,400&display=swap');

    body {
        height: 100vh;
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
    }

    .course-form-section {
        height: 77.5vh;
        width: 100vw;
        background-color: #fff;
        padding: 50px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .course-form-container {
        width: 800px;
        margin: 0 auto;
        padding: 20px;
        /* border: 1px solid #444; */
        border-radius: 5px;
        background: #eff2fb;
    }

    .course-form-container h2 {
        margin-bottom: 20px;
        text-align: center;
    }

    .course-form-input {
        margin-bottom: 15px;
    }

    .course-form-input label {
        display: block;
        margin-bottom: 5px;
    }

    .course-form-input input[type="text"],
    .course-form-input input[type="number"],
    .course-form-input input[type="date"],
    .course-form-input textarea {
        width: 70%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #ffffff;
        color: #000;
    }

    .course-form-input .rd_lab {
        display: inline;
        width: 10%;
    }

    .course-form-input input[type="file"] {
        display: block;
        margin-top: 5px;
    }

    .course-form-button {
        text-align: center;
    }

    .course-form-button button {
        padding: 10px 30px;
        color: #fff;
        background-color: #0061eb;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        cursor: pointer;
        transition: 300ms all;
        margin: 12px 13px;
    }

    .course-form-button button:hover {
        background-color: #00aeff;
    }
</style>
</head>

<body>
    <!-- ... (previous content) ... -->

    </div>
    <div class="course-form-section">
        <div class="course-form-container">
            <h2>Checkout</h2>
            <form method="POST" action="checkout.php" autocomplete="off" enctype="multipart/form-data">
                <div class="course-form-input">
                    <label for="course-title">Name</label>
                    <input type="text" id="course-title" name="name" value="<?php echo $_SESSION['user_name'] ?>" required>
                </div>
                <div class="course-form-input">
                    <label for="course-description">Phone number</label>
                    <input type="number" id="course-title" name="phone" value="<?php echo $_SESSION['phone'] ?>" required>
                </div>
                <div class="course-form-input">
                    <label for="course-pricing">Email</label>
                    <input type="text" id="pricing" name="email" value="<?php echo $_SESSION['user_email'] ?>" required>
                </div>
                <div class="course-form-button">
                    <button type="submit" name="submit">Pay Now</button>
                </div>
            </form>
        </div>
    </div>
    <?php include '../../foot.php' ?>
</body>

</html>