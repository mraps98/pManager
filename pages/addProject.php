<?php
session_start();
require('../dbconfig.php');


$pname = $_POST['pname'];
$pnumber = $_POST['pnumber'];
$plocation = $_POST['plocation'];
$dnum = $_POST['dnum'];


if ($_SESSION['loggedin'] )
{

  echo "Welcome ".$_SESSION['username'];

	$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if ($conn->connect_error) {
	    echo "Connection failed<br/>";
	    die("Connection failed: " . $conn->connect_error);
	}
	$sql = "insert into project values('$pname','$pnumber', '$plocation', '$dnum')";
	$result = $conn->query($sql);
	$return_arr = array();
  header("Location: projects.php");

$conn->close();


}
else
{
    echo "Not authenticated";
}
?>
