<?php
session_start();
require('../dbconfig.php');

if ($_SESSION['loggedin'] ){

  $conn   = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
  if ($conn->connect_error) {
      echo "Connection failed<br/>";
      die("Connection failed: " . $conn->connect_error);
  }

  $essn = mysqli_escape_string($conn, $_POST['essn']);
  $pno = mysqli_escape_string($conn, $_POST['pno']);
  $status = mysqli_escape_string($conn, $_POST['status']);

  if ($status == "Assign"){
    $sql = "insert into works_on values('$essn','$pno',0)";
    $result = $conn->query($sql);
  }else{
    $sql = "delete from works_on where essn = '$essn' and pno='$pno'";
    $result = $conn->query($sql);
  }

}else{
  echo "User not logged in";
}
$conn->close();
header("location: projectsByEmp.php?ssn=$essn");
?>
