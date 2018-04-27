<?php
session_start();
require('../dbconfig.php');

if ($_SESSION['loggedin'] ){


$conn   = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    echo "Connection failed<br/>";
    die("Connection failed: " . $conn->connect_error);
}

echo '
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
  <a class="navbar-brand" href="#">PManager</a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../index.php">Employees</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="departments.php">Departments</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="projects.php">Projects</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://natto.mooo.com/~db4133414/doc">Documentation</a>
      </li>
    </ul>
  </div>
</nav>
';

$conn = new mysqli($dbhost, $dbuser, $dbname, $dbpass);

$querySSN = $_GET['ssn'];
$sql = "select * from employee where SSN = '$querySSN'";
$result = $conn->query($sql);

echo "<div class='container'>";

while ($row = $result->fetch_assoc()){

echo "<form style='display:none' id='assignform'><input type='text' id='essninput' name='essn' required='true' value='".$querySSN."'><input type='text' id='pnoinput' name='pno' required='true'><input type='text' name='status' id='assigninput' required='true'></form>";
echo "<br><h1>Projects for ".$row['fname']." ".$row['lname']."</h1>";

}

$sql = "SELECT p.pnumber, p.pname, w.essn FROM works_on w RIGHT JOIN project p ON essn='$querySSN' AND p.pnumber=w.pno;";

echo '<br><table class="table table-bordered"><tr><th>Project Number</th><th>Project Name</th><th>Assign/Unassign</th>';

$result = $conn->query($sql) or die("error in sql query");
while ($row = $result->fetch_assoc()){
  echo '<tr><td>'.$row['pnumber'].'</td><td>'.$row['pname'].'</td>';
  echo '<td><button class="assignbtn" value='.$row['pnumber'].'>';

if ($row['essn']){
echo 'Unassign';
}else { echo 'Assign';}

echo '</button></td></tr>';
}

echo'</table>';


echo'</div>';

}else{
  echo 'You havent logged in';
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>PManager</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/styles.css">

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <script type="text/javascript" src="../js/assignSQL.js"></script>
</head>


</html>
