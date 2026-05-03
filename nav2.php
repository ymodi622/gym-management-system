<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Willty Fitness</title>
  <script>
    function homeCaller() {
      window.location.pathname = 'GYMAPP/home.php'
    }
  </script>
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
      cursor:pointer;

    }

    .logoImg {
      /* margin: 2px 12px; */
      height: 100%;
    }

    .logoImg img {
      height: 12vh;
      width: 12vh;

    }

    h3{
        color: #fff;
        margin: auto;
        letter-spacing: 1px;
        font-size: 1.3rem;
    }


  </style>
</head>

<body>
  <div class="navbar">
    <div class="logo" onclick="homeCaller()">
      <div class="logoImg">
        <img src="admin/logo2.png" alt="no image">
      </div>
      <h3>Willity Fitness</h3>
    </div>
  </div>

</body>

</html>