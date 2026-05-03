<?php
include '../conn.php';
include 'admin_nav.php';

if (isset($_SESSION['admin_name'])) {
    $name = $_SESSION['admin_name'];
    $email = "";
    $sel = "SELECT * FROM admins where name = '$name'";
    if ($res = mysqli_query($conn, $sel)) {
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $email = $row['email'];

            }
        }
    }
    if (isset($_POST['submitPass'])) {
        if ((isset($_POST['newPass'])) && (isset($_POST['conPass']))) {
            $npass = $_POST['newPass'];
            $cpass = $_POST['conPass'];
            if ((strlen($npass) >= '8') && $npass == $cpass) {
                $_SESSION['new_pass']= $npass;
                header("location:sendmail.php");
                } else {
                    echo "<script>alert('Something wrong with your password!')</script>";
                }
            }
        }

    }
    if (isset($_POST['submit'])) {
        session_destroy(); //destroy the session
        $_SESSION = [];
        header("location:admin_login.php"); //to redirect back to "index.php" after logging out
        // exit();
    } 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP verification</title>
    <style>
        .course-form-section {
            background-color: #fff;
            padding: 50px 0;
            margin-bottom: 150px;
        }

        .course-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 37px;
            /* border: 1px solid #444; */
            border-radius: 5px;
            background: #eff2fb;
        }

        .course-form-container h2 {
            margin-bottom: 20px;
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

        .course-form-input {
            margin-bottom: 15px;
        }

        .course-form-input label {
            display: block;
            margin-bottom: 5px;
        }

        .course-form-input input[type="password"] {
            width: 40%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffffff;
            color: #000;
        }

        h1 {
            text-align: center;
        }

        #logOutBtn {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            padding: 4px 18px;
            transform: translateY(24px);
        }

        #logOutBtn ion-icon {
            font-size: 1.4rem;
            margin: 3px;
        }

        .active {
            display: block;
        }

        .inactive {
            display: none;
        }
    </style>
</head>

<body>
    <div class="course-form-section">
        <div class="course-form-container">
            <h1>Admin panel</h1>
            <!-- <form method="POST" autocomplete="off" enctype="multipart/form-data"> -->
            <?php
            echo '<div>Username: ' . $name . '</div> ' .
                '<div>Email: ' . $email . '</div>'
                ?>
            <div class="course-form-button changePass">
                <button name="changeBtn" id="changeBtn" onclick="formDisplay()">Change password</button>
                <form method="POST" class="inactive" id="passForm">
                    <div class="course-form-input">
                        <label for="course-image">New Password</label>
                        <input type="password" name="newPass" required>
                    </div>
                    <div class="course-form-input">
                        <label for="course-image">Confirm Password</label>
                        <input type="password" name="conPass"" required>
                        </div>
                    <button type=" submit" name="submitPass">Proceed</button>
                </form>
                <!-- </form> -->
            </div>
        </div>
        <form method="POST">
            <div class="course-form-button">
                <button id="logOutBtn" type="submit" name="submit">Log Out<ion-icon
                        name="log-out-outline"></ion-icon></button>
            </div>
        </form>
    </div>
    <script>
        function formDisplay() {
            let form = document.getElementById("passForm")
            let changeBtn = document.getElementById("changeBtn")
            changeBtn.classList.add("inactive")
            form.classList.remove("inactive")
            form.classList.add("active")
        }
    </script>
</body>

</html>
<?php include '../foot.php'; ?>