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
</style>

<body>
    <div class="profileSec">
        <ion-icon name="person-circle-outline"></ion-icon>
        <div>
            <?php
            session_start();
            if (isset($_SESSION['admin_name'])) {
                echo $_SESSION['admin_name'] . "(admin)";
            }
            ?>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>