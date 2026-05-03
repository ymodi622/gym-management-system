<?php
include 'admin_nav.php';
include '../conn.php';

$gridVal = 0;
if (isset($_SESSION['admin_name'])) {
    function button1()
    {
        header("location:course_add.php");
    }
    function button2()
    {
        $val = $_POST['edit'];
        $_SESSION["course_id"] = $val;
        header("location:course_edit.php");
    }
    if (array_key_exists('add', $_POST)) {
        button1();
    }
    if (array_key_exists('edit', $_POST)) {
        button2();
    }
} else {
    header("location:admin_login.php");

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .cardSec {
            display: grid;
            grid-template-columns: auto auto auto auto;
            height: auto;
            max-width: 82vw;
            margin: auto;
            padding: 45px 12px;
            overflow: auto;
            row-gap: 20px;
            column-gap: 2px;
        }

        /* before adding the img to the div with the *************************************************
"card-img" class, remove css styles 
.card-img .img::before and .card-img .img::after,
then set the desired styles for .card-img. */
        .card {
            --font-color: #323232;
            --font-color-sub: #666;
            --bg-color: #fff;
            --main-color: #323232;
            --main-focus: #2d8cf0;
            width: 230px;
            height: 380px;
            background: var(--bg-color);
            border: 2px solid var(--main-color);
            box-shadow: 4px 4px var(--main-color);
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding: 20px;
            gap: 10px;
            margin: 4px 15px;
            transition: all 300ms;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .card:hover {
            transform: translateY(-7px);
        }

        .card:last-child {
            justify-content: flex-end;
        }

        .card-img {
            /* clear and add new css */
            transition: all 0.5s;
            display: flex;
            justify-content: center;
            max-height: 53%;
            background-color: transparent;
            cursor: pointer;
        }

        .card-img img {
            width: 100%;
            height: 100%;

        }

        .card-title {
            font-size: 20px;
            font-weight: 500;
            text-align: center;
            color: var(--font-color);
        }

        .card-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: var(--font-color-sub);
            /* overflow: auto; */
        }

        .card-divider {
            width: 100%;
            border: 1px solid var(--main-color);
            border-radius: 50px;
        }

        .card-footer {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .card-price {
            font-size: 20px;
            font-weight: 500;
            color: var(--font-color);
        }

        .card-price span {
            font-size: 20px;
            font-weight: 500;
            color: var(--font-color-sub);
        }

        .card-btn {
            height: 35px;
            background: var(--bg-color);
            border: 2px solid var(--main-color);
            border-radius: 5px;
            padding: 0px 12px;
            font-size: 1rem;
            padding: 0 28px;
            transition: all 0.3s;
            cursor: pointer;
        }


        .card-img:hover {
            transform: translateY(-3px);
        }

        .card-btn:hover {
            border: 2px solid var(--main-focus);
        }

        .card-btn:hover svg {
            fill: var(--main-focus);
        }

        .card-btn:active {
            transform: translateY(3px);
        }

        .addCard {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eff2fb;
            height: 80px;
            width: 80px;
            border-radius: 12px;
            margin: 12px 12px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .addCard:hover {
            /* background: #AEAEAE; */
            border-radius: 50%;


        }

        .addCard img {
            height: 50%;
            width: 60%;
        }

        .duration {
            display: block;
            padding: 14px;
        }

        form {
            margin: auto;
        }
    </style>
</head>

<body>
    </div>
    <div class="cardSec">
        <?php
        $sel = "SELECT * FROM `courses`";
        if ($result = mysqli_query($conn, $sel)) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row["duration"] == "3_mt") {
                    $dur = "3 months";
                }
                if ($row["duration"] == "6_mt") {
                    $dur = "6 months";
                }
                if ($row["duration"] == "12_mt") {
                    $dur = "12 months";
                }
                echo
                    ' <div class="card">
                <div class="card-img">
                    <img src="img/' . $row["image"] . '" alt="no image available" srcset="">
                </div>
                <div class="card-title">' . $row["title"] . ' </div>
                <div class="card-subtitle">Admin Course
                </div>
                <hr class="card-divider">
                <div class="card-footer">
                    <div class="card-price">
                    <form method="POST">
                    <button class="card-btn" id=' . $row["course_id"] . ' name="edit" value=' . $row["course_id"] . '>
                        Edit
                    </button>
                    </form>
                    </div>
                    <div class="duration card-subtitle">' . $dur . '</div>
                </div>
            </div>';
            }
        }
        ?>
        <form method="POST">
            <button class="addCard" name="add" value="add">
                <img src="imgs/plus.png" alt="">
            </button>
        </form>
    </div>
</body>

</html>
<?php include '../foot.php'; ?>