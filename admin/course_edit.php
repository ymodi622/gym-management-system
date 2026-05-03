<?php
include 'admin_nav.php';
include '../conn.php';
if (isset($_SESSION['admin_name'])) {
    $crId = $_SESSION['course_id'];
    $selCrs = "SELECT * FROM `courses` WHERE course_id = '$crId'";
    if ($result = mysqli_query($conn, $selCrs)) {
        if (!isset($result)) {
            echo "hii";
            echo "<script>alert('No data found!')</script> ";
            header("location:admin:db.php");
        }
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $title = $row['title'];
                $desc = $row['description'];
                $img = $row['image'];
                $price = $row['price'];
                $dur = $row['duration'];
                $tmp_dur = $dur;
                $fileName = $img;
            }
            // 
            if ($dur == "3_mt") {
                $dur = "3 months";
            } else if ($dur == "6_mt") {
                $dur = "6 months";
            } else if ($dur == "12_mt") {
                $dur = "12 months";
            }
        }
    }
    if (isset($_POST['submit'])) {

        $titleUp = $_POST['title'];
        $descUp = $_POST['description'];
        $priceUp = $_POST['pricing'];
        if (isset($_POST['duration'])) {
            $durationUp = $_POST['duration'];
        } else {
            $durationUp = $tmp_dur;
        }
        if (isset($_FILES["spanImg"]) && !empty($_FILES["spanImg"]["name"])) {
            if (is_uploaded_file($_FILES["spanImg"]["tmp_name"]) && $_FILES["spanImg"]["error"] === 0) {
                // everything okay, do process
                $fileName = $_FILES['spanImg']['name'];
            } else {
                $fileName = $img;
            }
        } else {
            $fileName = $img;
        }
        $tempName = $_FILES["spanImg"]["tmp_name"];
        $uploadDir = "img/";
        // Move the temporary file to the desired location 
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($tempName, $uploadPath) || 1) {
            $upSql = "UPDATE `courses` SET `title`='$titleUp',`image`='$fileName',`description`='$descUp',`price`=$priceUp,`duration`='$durationUp' WHERE course_id='$crId'";
            if ($result = $conn->query($upSql)) {
                echo "<script>alert('Updated sucessfully!')</script> ";
                header("location:admin_db.php");
            } else {
                echo "<script>alert('An error occured!')</script> ";
            }
        }
    } elseif (isset($_POST['yes'])) {
        $del = "DELETE FROM courses WHERE course_id = '$crId'";
        if ($result = $conn->query($del)) {
            $status = unlink('img/' . $fileName);
            if ($status) {
                echo "<script>alert('Deleted sucessfully!')</script> ";
                header("location:admin_db.php");
            } else {
                echo "<script>alert('Something went wrong, please try again!')</script> ";
            }
        } else {
            echo "<script>alert('Something went wrong, please try again!')</script> ";
        }
    } elseif (isset($_POST['no'])) {
        echo '<script>
            div.innerHTML = ""
            </script>';
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
    <title>Willty Fitness - Admin Course Edit</title>
</head>

<body>
    <style>
        /* ... (previous styles) ... */

        .course-form-section {
            background-color: #fff;
            padding: 50px 0;
        }

        .course-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
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
        .course-form-input input[type="file"],
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
            /* display: none; */
            margin-top: 5px;
            cursor: pointer;
        }

        .course-form-button {
            text-align: center;
        }

        .course-form-button.twice {
            text-align: left;
            margin: 0;
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

        .course-form-button button:hover {
            background-color: #00aeff;
        }

        .dur {
            margin-left: 12px;
        }

        #deleteBtn {
            margin: 0;
            background: red;
        }

        #deleteBtn:hover {
            background: #FF5C5C;
        }

        .confirmSec {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px;
            width: 50%;
            margin: 8px auto;
            background: #EE4B2B;
            border-radius: 8px;
            color: #fff;
        }

        .confirmSec button {
            background: transparent;
            font-size: 1.4rem;
            padding: 0px 10px;
            margin: 0 6px;
            font-weight: 900;
        }

        .confirmSec button:hover {
            background: #D10000;
        }
    </style>
    </head>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <body>
        <!-- ... (previous content) ... -->

        </div>
        <div class="course-form-section">
            <div class="course-form-container">
                <?php
                echo '<h2>Edit ' . $title . '</h2>
                    <form method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="course-form-input">
                            <label for="course-title">Title</label>
                            <input type="text" id="course-title" value="' . $title . '" name="title" required>
                        </div>
                        <div class="course-form-input">
                    <label for="course-image">Image</label>
                    <input type="file" id="course-image" name="spanImg" value="' . $img . '">
                </div>
                        <div class="course-form-input">
                            <label for="course-description">Description</label>
                            <textarea id="description" name="description" rows="14" required>' . $desc . '</textarea>
                        </div>
                        <div class="course-form-input">
                            <label for="course-pricing">Pricing</label>
                            <input type="number" id="pricing" name="pricing" value="' . $price . '" required>
                        </div>
                        <div class="course-form-input">
                            <label for="course-pricing">Duration</label>
                            <div class="dur" id="curDur">' . $dur . '</div>
                            <div class="course-form-button twice" id="durChange">
                            <button name="durChange"  onclick="display()">Update</button>
                        </div>
                        </div>
                        <div class="course-form-button">
                            <button type="submit" name="submit">Save Changes</button>
                        </div>
                        <div class="course-form-button" id="deleteBtnDiv">
                            <button onclick="display2()" id="deleteBtn" name="delete">Delete Course</button>
                        </div>
                        <script>
                function display() {
                    let btn =document.getElementById("durChange");
                    let crr =document.getElementById("curDur");
                    let HTML = `<input type="radio" id="3m" name="duration" value="3_mt">
                                <label for="html" class="rd_lab">3 months</label><br>
                                <input type="radio" id="6m" name="duration" value="6_mt">
                                <label for="css" class="rd_lab">6 months</label><br>
                                <input type="radio" id="12m" name="duration" value="12_mt">
                                <label for="javascript" class="rd_lab">12 months</label>`;
                    btn.remove()
                    crr.innerHTML = HTML
                }
                function display2() {
                    let div = document.getElementById("deleteBtnDiv");
                    div.innerHTML += `<div class="confirmSec">
                    Are you sure?
                    <form method="POST">
                    <button name="yes"><ion-icon name="checkmark-outline"></ion-icon></button>
                    <button name="no"><ion-icon name="close-outline"></ion-icon></button>
                    </form>
                    </div>`
                }
            </script>
                    </form>';
                ?>
            </div>
        </div>
    </body>

</html>
<?php include '../foot.php'; ?>