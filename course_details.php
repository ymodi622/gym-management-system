<?php
include 'nav.php';
include 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <?php
    if (isset($_SESSION['course_id'])) {
        $crId = $_SESSION['course_id'];
        $usId = $_SESSION['user_id'];
        $flag = 0;
        $sel = "SELECT * FROM courses WHERE course_id = '$crId'";
        if ($result = $conn->query($sel)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $title = $row['title'];
                $desc = $row['description'];
                $price = $row['price'];
                $dur = $row['duration'];
                $cr = $row['creator'];
            }
            if ($dur == "3_mt") {
                $dur = "3 months";
            }
            if ($dur == "6_mt") {
                $dur = "6 months";
            }
            if ($dur == "12_mt") {
                $dur = "12 months";
            }
        }
    }
  $memSel = "SELECT `course_id` from memberships WHERE user_id = '$usId'";
  if ($res = $conn->query($memSel)) {
    while ($row = $res->fetch_assoc()) {
      if ($row['course_id'] == $crId) {
        $flag = 1;
      }
    }
  }

    if (isset($_POST['enroll'])) {
        if ($flag == 1) {
        echo "<script>alert('You already enrolled in $title!')</script>";
        }else {
            echo '<script>location.replace("razorpay/razorpay-php/");</script>';
        }
    }
    ?>
    <style>
        .course-form-section {
            background-color: #fff;
            padding: 50px 0;
        }

        .course-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 30px;
            /* border: 1px solid #444; */
            border-radius: 5px;
            background: #eff2fb;
        }

        .course-form-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            font-weight: 900;
        }

        .crValue {
            margin: 12px 14px;
            width: 85%;
            font-weight: 500;
        }

        .line {
            width: 90%;
            height: 2px;
            background: #808080;
            margin-bottom: 14px;
        }

        .course-form-button {
            display: flex;
            justify-content: center;
            align-items: center;
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
            margin: auto;
        }

        .course-form-button button:hover {
            background-color: #00aeff;
        }
    </style>
</head>

<body>
    <div class="course-form-section">
        <div class="course-form-container">
            <?php echo "<h2>" . $title . "</h2>";
            echo '<div class="crDetails">
                <form method="POST">
                    <label for="description">Description </label>
                    <div class="crValue">' . $desc . '</div>
                        <div class="line"></div>
                    <label for="Price">Price </label>
                    <div class="crValue">' . $price . '</div>  
                    <div class="line"></div>
                    <label for="Price">Duration </label>
                    <div class="crValue">' . $dur . '</div>
                    <div class="course-form-button">
                            <button type="submit" name="enroll">Enroll now</button>
                        </div>   

                </form>
            </div>';
            ?>
        </div>
    </div>
</body>

</html>