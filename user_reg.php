<?php
$passwordErr = "";
session_start();
include 'nav2.php';
include 'conn.php';
if (isset($_POST["submit"])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $ph = $_POST['phone'];
  $gen = $_POST['gender'];
  $pass = $_POST['pass'];
  $cpass = $_POST['cpass'];
  if ((strlen($pass) >= '8') && ($pass == $cpass)) {
    if ((strlen(strval($ph)) == 10)) {
      if (($hg = floatval($_POST['height'])) && ($wt = floatval($_POST['weight']))) {
        
        if (isset($_POST["email"])) {
          
          $sel = "SELECT email FROM users WHERE email  = '$email'";
          $result = $conn->query($sel);
          
          if ($result->num_rows > 0) {
            echo "<script>alert('User already registered!')</script>";
            
          } else {
            $us = uniqid("willuser_");
            $_SESSION['name'] = $name;
            $_SESSION['us_email'] = $email;
            $_SESSION['phone'] = $ph;
            $_SESSION['gender'] = $gen;
            $_SESSION['pass'] = $pass;
            $_SESSION['us'] = $us;
            $_SESSION['height'] = $hg;
            $_SESSION['weight'] = $wt;
            header("location:us_sendmail.php");
          }
        }
      }else{
        echo "<script>alert('Height and weight should not be in characters!')</script>";

      }
    } else {
      echo "<script>alert('Phone number should be of 10 digits!')</script>";
    }
  }else {
    echo "<script>alert('Something wrong with your password!')</script>";

  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Gym Name</title>
  <style>
    /* ... (previous styles) ... */
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    .form-section {
      background: #171717;
      padding: 50px 0;
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }

    .form-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 10px 20px;
      border: 1px solid #444;
      border-radius: 5px;
      background: #292929;
    }

    .form-container h2 {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .form-input {
      margin-bottom: 15px;
    }

    .form-input label {
      display: block;
      margin-bottom: 5px;
      margin-left: 40px;

    }

    .form-input input[type="text"],
    .form-input input[type="number"],
    .form-input input[type="tel"],
    .form-input input[type="email"],
    .form-input input[type="password"],
    .form-input select {
      width: 70%;
      padding: 10px;
      border: 1px solid #444;
      border-radius: 5px;
      background-color: #333;
      color: white;
      margin-left: 40px;
    }

    .form-input select {
      width: 74%;
      padding: 10px;
      border: 1px solid #444;
      border-radius: 5px;
      background-color: #333;
      color: white;
      appearance: none;
      margin-left: 40px;

    }

    .form-button {
      text-align: center;
    }

    .form-button button {
      padding: 10px 20px;
      background-color: #FFC107;
      border: none;
      border-radius: 5px;
      color: black;
      font-weight: bold;
      cursor: pointer;
    }

    .form-button button:hover {
      background-color: #FFA000;
    }

    .btnDiv {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
    }

    button {
      padding: 12px 50px;
      margin: 15px auto;
      border-radius: 10px;
      border: 0;
      background-color: white;
      box-shadow: rgb(0 0 0 / 5%) 0 0 8px;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      font-size: 15px;
      font-weight: 600;
      transition: all .5s ease;
      color: #171717;
    }

    button:hover {
      letter-spacing: 3px;
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 7px 29px 0px;
    }

    button:active {
      letter-spacing: 3px;
      background-color: hsl(261deg 80% 48%);
      color: hsl(0, 0%, 100%);
      box-shadow: rgb(93 24 220) 0px 0px 0px 0px;
      transform: translateY(10px);
      transition: 100ms;
    }

    .form-input p {
      font-size: 0.8rem;
      margin: 2px 40px;
    }
    a {
       color: #fff;
       text-decoration: none;
        }
    .linkCon a:hover{
        text-decoration: underline;
        }
      .linkCon{
          margin-left: 41px;
        }
      .linkCon p{
        margin: 0px 0px 8px;
      }
      
      
  </style>
</head>

<body>
  <!-- ... (previous content) ... -->

  <div class="form-section">
    <div class="form-container">
      <h2>User Register</h2>
      <form method="POST">
        <div class=" form-input">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-input">
          <label for="height">Height (cm)</label>
          <input type="text" id="height" name="height" required>
        </div>
        <div class="form-input">
          <label for="weight">Weight (kg)</label>
          <input type="text" id="weight" name="weight" required>
        </div>
        <div class="form-input">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-input">
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" required>
        </div>
        <div class="form-input">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-input">
          <label for="email">Create Password</label>
          <input type="password" id="pass" name="pass" required>
          <p>At least 8 characters</p>
        </div>
        <div class="form-input">
          <label for="email">Confirm Password</label>
          <input type="password" id="cpass" name="cpass" required>
        </div>
        <div class="linkCon">
        <p>Already have an account? </p><a href="user_login.php"><b>login from here</b></a></div>
        <div class="btnDiv"><button type="submit" name="submit"> SUBMIT
          </button></div>
      </form>
    </div>
  </div>
</body>

</html>
<?php


include 'foot.php'; ?>