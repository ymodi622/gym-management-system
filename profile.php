<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
            body {
            margin: 0;
            padding: 0;
        }

        .profileSec {
            display: flex;
            align-items: center;
            /* justify-content: center; */
            height: 8vh;
            background: #eff2fb;
            font-size: 2.1rem;
            padding: 0px 45px;
        }

        .profileSec div {
            font-size: 1.12rem;
            margin: 0px 3px;

        }
        .logInBtn {
            padding: 6px 30px;
            color: #fff;
            background-color: #0061eb;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 300ms all;
            margin: 12px 13px;
        }

        .logInBtn:hover {
            background-color: #00aeff;
        }
</style>

<body>
    <div class="profileSec">
        
            <?php
            session_start();
            if (isset($_SESSION['user_name'])) {
                
                echo '<ion-icon name="person-circle-outline"></ion-icon><div>'.$_SESSION['user_name'].'</div>';
            }else{
                echo '<form method="POST">
                <button type="submit" name="logInBtn" class="logInBtn">Log In</button>
            </form>';
            }
            if (isset($_POST['logInBtn'])) {
                header("location:user_login.php");
            }
            ?>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>