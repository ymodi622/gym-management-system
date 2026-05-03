<?php
session_start();
if (isset($_POST['logInBtn'])) {
  header("location:admin_login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;1,400&display=swap');

    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background-image: url('logo.png')center cover no-repeat;
      font-weight: 800;
    }

    .navbar {
      height: 10vh;
      /* font-size: 3rem; */
      background-color: black;
      display: flex;
      justify-content: space-between;
      align-items: center;
      /* padding: 20px; */
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    }

    .logo {
      height: 12vh;
      display: flex;
      flex-direction: row;
      margin: 0pc 30px;
      z-index: 2;

    }

    .logoImg {
      /* margin: 2px 12px; */
      height: 100%;
    }

    .logoImg img {
      height: 12vh;
      width: 12vh;

    }

    .nav-links {
      list-style: none;
      margin: auto;
      padding: 0;
      display: flex;
      width: 100%;

    }

    .nav-link {
      margin-right: 20px;
    }

    .nav-link a {
      color: white;
      text-decoration: none;
      font-size: 16px;
      transition: color 0.3s ease;
    }

    .nav-link a:hover {
      color: #FFC107;
    }

    #logInForm {
      width: 11%;
    }

    .shadow__btn {
      padding: 8px 30px;
      border: none;
      font-size: 1.1rem;
      color: #fff;
      border-radius: 7px;
      /* letter-spacing: 4px; */
      font-weight: 700;
      /* text-transform: uppercase; */
      transition: 0.5s;
      transition-property: box-shadow;
      cursor: pointer;
    }

    .shadow__btn {
      background: rgb(0, 140, 255);
      box-shadow: 0 0 25px rgb(0, 140, 255);
    }

    .shadow__btn:hover {
      box-shadow: 0 0 5px rgb(0, 140, 255),
        0 0 25px rgb(0, 140, 255),
        0 0 50px rgb(0, 140, 255),
        0 0 100px rgb(0, 140, 255);
    }

    .profileSec {
      display: flex;
      align-items: center;
      color: #fff;
      width: 20%;
      font-size: 2.1rem;
      padding: 0px 45px;
    }

    .profileSec div {
      font-size: 1.12rem;
      margin: 0px 3px;
      width: auto;
    }

    .sticky {
      position: sticky;
      top: 0;
      width: 100%;
      z-index: 2;
    }
  </style>
</head>

<body>
  <div class="navbar">
    <div class="logo">
      <div class="logoImg">
        <img src="logo2.png" alt="no image">
      </div>
    </div>
    <ul class="nav-links">
      <li class="nav-link"><a href="admin_db.php">Dashboard</a></li>
      <li class="nav-link"><a href="admin_pro.php">Profile</a></li>
      <li class="nav-link"><a href="memberships.php">Memberships</a></li>
      <li class="nav-link"><a href="members.php">Members</a></li>
    </ul>
    <?php
    if (isset($_SESSION['admin_name'])) {
      echo '<div class="profileSec">
          <ion-icon name="person-circle-outline"></ion-icon><div>' . $_SESSION['admin_name'] . '(admin)</div>
          </div>';
    } else {
      echo '<form method="POST" id="logInForm">
          <button class="shadow__btn" name="logInBtn">
            Log In 
          </button>
        </form>';
    }
    ?>
  </div>
  <script>
    window.onscroll = function () { myFunction() };

    var navbar = document.getElementsByClassName("navbar")[0];
    var sticky = navbar.offsetTop;

    function myFunction() {
      if (window.pageYOffset >= sticky) {
        navbar.classList.add("sticky")
      } else {
        navbar.classList.remove("sticky");
      }
    }
  </script>
</body>

</html>