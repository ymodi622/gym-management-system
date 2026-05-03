<?php
// session_start();
include 'conn.php';
include 'nav.php';
if ((isset($_POST['submit'])) || (!isset($_SESSION['user_email']))) {
    session_destroy(); //destroy the session
    $_SESSION = [];
    header("location:user_login.php");
}
if (isset($_POST['usmChangeBtn'])) {
    $newUsm = $_POST['usmChange'];
    $email = $_SESSION['user_email'];
    $usId = $_SESSION['user_id'];
    $usmUp = "UPDATE users SET name = '$newUsm' WHERE email = '$email'";
    if ($res = mysqli_query($conn, $usmUp)) {
        echo "<script>alert('Username updated sucessfully,Please log in again.');
        window.location = 'user_login.php'; 
        </script>";
    }
}
if (isset($_POST['submitPass'])) {
    if ((isset($_POST['newPass'])) && (isset($_POST['conPass']))) {
        $npass = $_POST['newPass'];
        $cpass = $_POST['conPass'];
        if ((strlen($npass) >= '8') && $npass == $cpass) {
            $_SESSION['new_pass'] = $npass;
            header("location:admin/sendmail.php");
        } else {
            echo "<script>alert('Something wrong with your password!')</script>";
        }
    }
}
if (isset($_SESSION['user_id'])) {
    $usId = $_SESSION['user_id'];
    $crSel = "SELECT course_id,end_date FROM memberships WHERE user_id = '$usId'";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
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

        .updateBtn {
            padding: 8px 30px;
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
            display: inline-block;
            margin: 5px;

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

        .detailsDiv {
            /* background: #fff; */
            margin: 8px 0px;
        }

        .classBtn {
            background-color: transparent;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            margin: 0px 1px;
            transform: translateY(5px);
            cursor: pointer;
        }

        input {
            border: none;
            border-radius: 3px;
            padding: 5px 8px;
        }

        .crownDiv {
            display: inline-block;
            height: 20px;
            width: 20px;
            transform: translateY(-5px);
        }

        .crownDiv img {
            height: 100%;
            width: 100%;
        }

        .headerDiv {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .memberDetails {
            background: #fff;
            width: 41%;
            display: inline-block;
            margin: 16px 20px;
            padding: 4px 15px;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="course-form-section">
        <div class="course-form-container">
            <div class="headerDiv">
                <h1>User panel</h1>
            </div>

            <!-- <form method="POST" autocomplete="off" enctype="multipart/form-data"> -->
            <?php
            $name = $_SESSION['user_name'];
            $email = $_SESSION['user_email'];
            $phone = $_SESSION['phone'];
            echo '<div class="detailsDiv">Username: ' . $name . ' <button class="classBtn" onclick="displayFunc()"><ion-icon
            name="create-outline">
        </ion-icon></button></div>
    <div id="inpField" class="inactive">
        <form method="POST">
        <input type="text" id="usmChange" name="usmChange"><button
            class="updateBtn" name="usmChangeBtn">Change</button></div> ' . '</form>
    <div class="detailsDiv">Phone: ' . $phone . '<button class="classBtn" onclick="displayFunc2()"><ion-icon
                name="create-outline"></ion-icon></button>
        <div id="inpField2" class="inactive">
        <form method="POST">
        <input type="text" id="phChange" name="phChange"><button
                class="updateBtn">Change</button></div> ' .
                '</form>
        <div class="detailsDiv">Email: ' . $email . '</div>
    </div>';
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
            <div id="memberDiv">
                <div class="headerDiv">
                    <h1>Courses Enrolled</h1>
                    <div class="crownDiv"><img src="crown2.png" alt=""></div>
                </div>
                <?php
                $date = date("Y-m-d H:i:s");
                if ($res = $conn->query($crSel)) {
                    while ($row = $res->fetch_assoc()) {
                        $crId = $row['course_id'];
                        $endDate = $row['end_date'];
                        $datetime1 = date_create($date);
                        $datetime2 = date_create($endDate);
                        $interval = date_diff($datetime1, $datetime2);
                        $sel = "SELECT * FROM courses WHERE course_id = '$crId'";
                        if ($result = $conn->query($sel)) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $title = $row['title'];
                            }
                            echo '<div class="memberDetails">
                        <div class="detailsDiv">Course enrolled: ' . $title . ' </div>
    
                        <div class="detailsDiv">Course ends on: ' . substr($endDate, 0, 10) . '</div>
                        <div class="detailsDiv">Time left: ' . $interval->format('%m months %d days') . '</div>
                    </div>';
                        }
                    }
                }
                ?>

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
        function displayFunc() {
            let inpField = document.getElementById('inpField');
            inpField.classList.toggle('inactive');
        }
        function displayFunc2() {
            let inpField2 = document.getElementById('inpField2');
            inpField2.classList.toggle('inactive');
        }
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
<?php
if (isset($_SESSION['is_mem'])) {
    $usEl = "SELECT user_id FROM members WHERE user_id = '$usId'";
    if ($result = $conn->query($usEl)) {
        if (!$row = mysqli_fetch_assoc($result)) {
            echo '<script>let memberDiv =document.getElementById("memberDiv")
            memberDiv.style.display = "none";</script>';
        }
    }
}
include 'foot.php';     
?>