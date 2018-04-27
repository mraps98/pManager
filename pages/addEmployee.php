<?php
session_start();
require('../dbconfig.php');


$fname = $_POST['fname'];
$lname = $_POST['lname'];
$ssn = $_POST['ssn'];
$bdate = $_POST['bdate'];
$sex = $_POST['sex'];
$salary = $_POST['salary'];
$superssn = $_POST['superssn'];
$dno = $_POST['dno'];


if ($_SESSION['loggedin'] )
{

  echo "Welcome ".$_SESSION['username'];

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if ($conn->connect_error) {
	    echo "Connection failed<br/>";
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "insert into employee values('$fname', '','$lname', '$ssn', '$bdate', ' ', '$sex', '$salary','$superssn', '$dno', ' ')";
	$result = $conn->query($sql);
	$return_arr = array();
  header("Location: ../index.php");

$conn->close();


}
else
{
    echo "Not authenticated";
}
?>
