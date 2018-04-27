<?php
session_start();
require('../dbconfig.php');


$dname = $_POST['dname'];
$dnumber = $_POST['dnumber'];
$mgrssn = $_POST['mgrssn'];
$mgrstartdate = $_POST['mgrstartdate'];


if ($_SESSION['loggedin'] )
{

  echo "Welcome ".$_SESSION['username'];

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if ($conn->connect_error) {
	    echo "Connection failed<br/>";
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "insert into department values('$dname','$dnumber', '$mgrssn', '$mgrstartdate')";
	$result = $conn->query($sql);
	$return_arr = array();
  header("Location: departments.php");

$conn->close();


}
else
{
    echo "Not authenticated";
}
?>
