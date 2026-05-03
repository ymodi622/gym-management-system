<?php
include 'admin_nav.php';
include '../conn.php';

if (isset($_SESSION['admin_name'])) {

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
                <h1>Members</h1><ion-icon onclick="refresh();" name="refresh-circle-outline"></ion-icon>
            </div>

            <table>
                <tr>
                    <th>Name</th>
                    <th>Member ID</th>
                    <th>Courses Enrolled</th>
                </tr>
                <?php
                    $selMem = "SELECT * FROM `members` WHERE 1";
                    $memIdArr = array();
                    $memNameArr = array();
                    if ($result = mysqli_query($conn, $selMem)) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $memId = $row['member_id'];
                            $name = $row['name'];
                            array_push($memIdArr,$memId);
                            array_push($memNameArr,$name);
                        }
                    }
                    $round = count($memIdArr);
                    for($i = 0; $i < $round; $i++){
                        $crArr = array();
                        $tmpId = $memIdArr[$i];
                        $crSel = "SELECT course_id FROM `memberships` WHERE member_id = '$tmpId'";
                        if ($result = mysqli_query($conn, $crSel)) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                array_push($crArr,$row['course_id']);
                            }
                        }
                        $cr = implode(", ", $crArr);
                        echo '<tr>
                        <td>'.$memNameArr[$i].'</td>
                        <td>'.$memIdArr[$i].'</td>
                        <td>'.$cr.'</td>
                    </tr>';
                    }
                    ?>
                <!-- Add more rows here -->
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