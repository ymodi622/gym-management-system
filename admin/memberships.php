<?php
include 'admin_nav.php';
include '../conn.php';

if (isset($_SESSION['admin_name'])) {

    if (isset($_POST['cancel'])) {
        $mbrToDelete = $_POST['cancel'];
        // echo "$mbrToDelete";
        $flag = 0;
        $delFlag = 0;
        $count = 1;
        echo '
    <script>if (confirm("Cancel membership?") == true) {
        text = "You pressed OK!";
        ' . $flag++ . '
      }</script>';
        if ($flag != 0) {

            $selMbr = "SELECT member_id FROM memberships where mbs_id = '$mbrToDelete'";
            $del = "DELETE FROM memberships where mbs_id = '$mbrToDelete'";

            if ($result = mysqli_query($conn, $selMbr)) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $memId = $row['member_id'];
                }
            }
            $selMbr = "SELECT * FROM memberships where member_id = '$memId'";
            if ($result = mysqli_query($conn, $selMbr)) {

                // Return the number of rows in result set
                $rowcount = mysqli_num_rows($result);
            }
            if ($result = $conn->query($del)) {
                echo "<script>alert('Membership deleted successfully!');</script>";
            }

            if ($rowcount == 1) {
                $delMem = "DELETE FROM members where member_id = '$memId'";
                $conn->query($delMem);
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
    <title>User Details</title>

    <style>
        .course-form-section {
            background-color: #fff;
            padding: 50px 0;
            margin-bottom: 150px;
        }

        .course-form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 37px;
            /* border: 1px solid #444; */
            border-radius: 5px;
            background: #eff2fb;
        }

        .headerDiv {
            text-align: center;
        }

        .headerDiv h1 {
            display: inline-block;
        }

        .headerDiv ion-icon {
            display: inline-block;
            font-size: 1.5rem;
            cursor: pointer;
            margin: 0px 0px -3px 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        td {
            font-weight: 500;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #fff;
            font-weight: 900;
            text-transform: uppercase;

        }

        td:last-child {
            text-align: center;
        }

        .cancel-btn {
            padding: 5px 10px;
            background-color: #ff6666;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="course-form-section">
        <div class="course-form-container">
            <div class="headerDiv">
                <h1>Current Memberships</h1><ion-icon onclick="refresh();" name="refresh-circle-outline"></ion-icon>
            </div>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Course ID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
                <!-- Add more rows here -->
                <?php
                $memSel = "SELECT * FROM  memberships WHERE 1";
                if ($result = $conn->query($memSel)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $name = $row['name'];
                        $crId = $row['course_id'];
                        $startDate = $row['start_date'];
                        $endDate = $row['end_date'];
                        $mbsId = $row['mbs_id'];
                        $crSel = "SELECT title FROM  courses WHERE course_id = '$crId'";
                        echo '<tr>
                        <td>' . $name . '</td>
                        <td>' . $crId . '</td>
                        <td>' . substr($startDate, 0, 10) . '</td>
                        <td>' . substr($endDate, 0, 10) . '</td>
                        <td>
                        <form method="POST">
                        <button class="cancel-btn" value="' . $mbsId . '" name="cancel">Cancel Membership</button></td>
                        </form>
                    </tr>';
                    }
                }
                ?>
            </table>
        </div>
    </div>
    <script>
        function refresh() {
            location.reload();
        }
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</body>

</html>