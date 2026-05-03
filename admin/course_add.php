<?php
include 'admin_nav.php';
include '../conn.php';
if (isset($_SESSION['admin_name'])) {
    # code...
    if (isset($_POST["submit"])) {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $price = $_POST['pricing'];
        $duration = $_POST['duration'];
        $id = uniqid("crs_");
        $uploadDir = "img/";
        $admin = $_SESSION['admin_name'];
        $fileName = $_FILES["spanImg"]["name"];
        $tempName = $_FILES["spanImg"]["tmp_name"];
        $fileSize = $_FILES["spanImg"]["size"];
        $fileType = $_FILES["spanImg"]["type"];
        if ($_FILES["spanImg"]["error"] === UPLOAD_ERR_OK) {
            // Move the temporary file to the desired location 
            $uploadPath = $uploadDir . $fileName;
            if (file_exists($uploadPath)) {
                echo "<script>alert(File Alredy Exist...)</script>"; //aLERT
            } else {
                if (move_uploaded_file($tempName, $uploadPath)) {
                    $ins = "INSERT INTO `courses`(`course_id`, `title`, `image`, `description`, `price`, `duration`, `creator`) VALUES 
                    ('$id',
                    '$title',
                    '$fileName',
                    '$desc',
                    '$price',
                    '$duration',
                    '$admin')";
                    if ($result = $conn->query($ins)) {
                        header("Location: admin_db.php");
                    }

                }
            }
        }
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
    <title>Willty Fitness - Admin Course Creation</title>
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
            display: block;
            margin-top: 5px;
        }

        .course-form-button {
            text-align: center;
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
    </style>
</head>

<body>
    <!-- ... (previous content) ... -->

    </div>
    <div class="course-form-section">
        <div class="course-form-container">
            <h2>Add a New Course</h2>
            <form method="POST" autocomplete="off" enctype="multipart/form-data">
                <div class="course-form-input">
                    <label for="course-title">Title</label>
                    <input type="text" id="course-title" name="title" required>
                </div>
                <div class="course-form-input">
                    <label for="course-image">Image</label>
                    <input type="file" id="course-image" name="spanImg">
                </div>
                <div class="course-form-input">
                    <label for="course-description">Description</label>
                    <textarea id="description" name="description" rows="14" required></textarea>
                </div>
                <div class="course-form-input">
                    <label for="course-pricing">Pricing</label>
                    <input type="number" id="pricing" name="pricing" required>
                </div>
                <div class="course-form-input">
                    <label for="course-pricing">Duration</label>

                    <input type="radio" id="3m" name="duration" value="3_mt">
                    <label for="html" class="rd_lab">3 months</label><br>
                    <input type="radio" id="6m" name="duration" value="6_mt">
                    <label for="css" class="rd_lab">6 months</label><br>
                    <input type="radio" id="12m" name="duration" value="12_mt">
                    <label for="javascript" class="rd_lab">12 months</label>
                </div>
                <div class="course-form-button">
                    <button type="submit" name="submit">Add Course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ... (remaining content) ... -->
</body>

</html>
<?php include '../foot.php'; ?>