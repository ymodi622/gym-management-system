<?php
include 'admin_nav2.php';
session_start();
include '../conn.php';


if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $pass = $_POST["pass"];

    $sel = "SELECT * FROM ADMINS WHERE email='$email' and password='$pass'";
    $result = $conn->query($sel);

    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            $_SESSION['admin_name'] = $row['name'];
            header('location:admin_db.php');
        }
    }
    else{
  echo "<script>alert('Check email or password!')</script>";

    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Insertion Form</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
        body{
            font-family: 'Poppins', sans-serif;
            margin:0;
            padding: 0;
        }
        .form-section {
            background: #171717;
            padding: 50px 0;
            color: #fff;
            font-family: 'Poppins', sans-serif;

            height: 67.6vh;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
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
            margin-left: 20px;
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
            margin-left: 20px;
            background-color: #333;
            color: white;
        }

        .form-input select {
            width: 74%;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #333;
            color: white;
            appearance: none;


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
    </style>
</head>

<body>
    <div class="container">
        <div class="form-section">
            <div class="form-container">
                <h2>Admin Login</h2>
                <form method="post">
                    <div class="form-input">
                        <label for="weight">Email</label>
                        <input type="text" id="mail" name="email" required>
                    </div>
                    <div class="form-input">
                        <label for="weight">Password</label>
                        <input type="password" id="pass" name="pass" required>
                    </div>

                    <div class="btnDiv"><button type="submit" name="submit"> SUBMIT
                        </button></div>
                </form>
            </div>
        </div>
</body>

</html>
<?php include '../foot.php'; ?>