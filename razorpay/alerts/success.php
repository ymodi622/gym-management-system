<?php 
  session_start();
  include '../../conn.php';
  $date = date("Y-m-d H:i:s");
  $curDay = new DateTime();
  $crId = $_SESSION['course_id'];
  $mbsId = uniqid("mbs_");
  $payId = uniqid("pay_");
  $name = $_SESSION['user_name'];
  $usId = $_SESSION['user_id'];
  $flag = 0;
  $sel = "SELECT * FROM COURSES WHERE course_id = '$crId'";
    if ($result = $conn->query($sel)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['title'];
            $desc = $row['description'];
            $price = $row['price'];
            $dur = $row['duration'];
        }
    }
    if ($dur == "3_mt") {
      $curDay->modify('+3 months');
      $endDay = $curDay->format('Y-m-d H:i:s');
    }
    elseif ($dur == "6_mt") {
      $curDay->modify('+6 months');
      $endDay = $curDay->format('Y-m-d H:i:s');
    }
    elseif ($dur == "12_mt") {
      $curDay->modify('+12 months');
      $endDay = $curDay->format('Y-m-d H:i:s');
    }
  $payIns = "INSERT INTO payments VALUES ('$payId','$usId','$crId',$price,'$date','success')";
  $memSel = "SELECT `member_id` from members WHERE user_id = '$usId'";
  $usUp = "UPDATE users SET is_member = 1 WHERE user_id = '$usId'";
  // $crSel = "SELECT `course_id` from members WHERE user_id = '$usId'";
  if ($res = $conn->query($memSel)) {
    if ($row = $res->fetch_assoc()) {
      $memId = $row['member_id'];
    }else{
      $flag = 1;
    }
  }
  if ($flag == 1) {
    $memId = uniqid("mem_");
    $memIns = "INSERT INTO members VALUES('$memId','$usId','$name')";
    $conn->query($memIns);
  }
    $membrIns = "INSERT INTO memberships VALUES ('$mbsId','$memId','$crId','$usId','$name','$date','$endDay',$price)";
  if (($result = $conn->query($payIns))  && $conn->query($usUp) && $conn->query($membrIns)) {
    echo "<script>alert('Course purchased successfully!')
    window.location.assign('/GYMAPP/us_profile.php') </script>";
  }else{
    echo "<script>alert('Something went wrong!')</script>";
  }
?>