<?php
session_start();
include 'conn.php';
include 'nav2.php';
$otp = $_SESSION['otp'];

if (isset($_POST['verify'])) {
    $i1 = strval($_POST['i1']);
    $i2 = strval($_POST['i2']);
    $i3 = strval($_POST['i3']);
    $i4 = strval($_POST['i4']);
    $inpOTP = $i1.$i2.$i3.$i4;
    if ($otp == $inpOTP) {
        $name = $_SESSION['name'];
         $email = $_SESSION['us_email'];
         $ph = $_SESSION['phone'];
         $gen = $_SESSION['gender'];
         $pass = $_SESSION['pass'];
         $us = $_SESSION['us'] ;
         $hg = $_SESSION['height'] ;
         $wt = $_SESSION['weight'] ;
        $ins = "INSERT INTO `users` (`user_id`,`name`,`gender`,`phone`,`email`,`password`,`height`,`weight`)
                 VALUES ('$us','$name','$gen',$ph,'$email','$pass',$hg,$wt)";
            if (mysqli_query($conn, $ins)) {
                header("location:user_login.php");
            }else {
            echo "<script>alert('Something went wrong, please try again!')</script>";
        }
    }else{
        echo "<script>alert('Incorrect OTP!')</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter OTP</title>
    <style>
        .course-form-section {
            background-color: #fff;
            padding: 50px 0;
            margin-bottom: 150px;
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: space-around;
        }

        .form {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: space-around;
            width: 300px;
            background-color: white;
            border-radius: 12px;
            padding: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: black
        }

        .message {
            color: #a3a3a3;
            font-size: 14px;
            margin-top: 4px;
            number-align: center;
            text-align: center;
        }

        .inputs {
            margin-top: 10px
        }

        .inputs input {
            width: 32px;
            height: 32px;
            number-align: center;
            border: none;
            border-bottom: 1.5px solid #d2d2d2;
            margin: 0 10px;
        }

        .inputs input:focus {
            border-bottom: 1.5px solid royalblue;
            outline: none;
        }

        .action {
            margin-top: 24px;
            padding: 12px 16px;
            border-radius: 8px;
            border: none;
            background-color: royalblue;
            color: white;
            cursor: pointer;
            align-self: end;
        }
    </style>
</head>

<body>
    <div class="course-form-section">
        <form class="form" method="POST">
            <div class="title">OTP</div>
            <div class="title">Email Verification Code</div>
            <p class="message">We have sent a verification code to your email address</p>
            <div class="inputs"> <input id="input1" name="i1" type="number" maxlength="1"> <input id="input2" name="i2" type="number"
                    maxlength="1"> <input id="input3" name="i3" type="number" maxlength="1"> <input id="input4"name="i4" type="number"
                    maxlength="1"> </div> <button class="action" type="submit" name= "verify">verify me</button>
        </form>
    </div>
</body>
<script>
    i1 = document.getElementById("input1")
    i2 = document.getElementById("input2")
    i3 = document.getElementById("input3")
    i4 = document.getElementById("input4")

    i1.onkeydown = function () {
        if (this.value.length == this.maxLength)
            document.getElementById('input2').focus();
    }
    i2.onkeydown = function () {
        if (this.value.length == this.maxLength)
            document.getElementById('input3').focus();
    }
    i3.onkeydown = function () {
        if (this.value.length == this.maxLength)
            document.getElementById('input4').focus();
    }
</script>

</html>