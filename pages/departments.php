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
<nav class="navbar navbar-expand-lg navbar-light bg-light  sticky-top">
  <a class="navbar-brand" href="#">PManager</a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../index.php">Employees</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="#">Departments</a>
      </li>
      <li class="nav-item">
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
$sql = 'select * from department';
$result = $conn->query($sql);

echo '<br><br><div class="container"><table class="table table-bordered"><tr><th>Department Name</th><th>Department Number</th><th>Manager</th><th>Manager Start Date</th>';

while ($row = $result->fetch_assoc()){
  echo '<tr><td>'.$row['dname'].'</td><td>'.$row['dnumber'].'</td><td>'.$row['mgrssn'].'</td><td>'.$row['mgrstartdate'].'</td></tr>';
}
echo '</table>';
echo "<br><br><h1>Add Department:</h1><br>";
echo "<form action='addDepartment.php' method='post'>
  Department Name: <input type='text' name='dname' class='form-control' required='true' maxLength='15'>
  Department Number: <input type='text' name='dnumber' class='form-control' required='true' maxLength='1'>
  Department Manager: <br><select name='mgrssn' class='form-control' required='true'>";

  $sql    = "select fname, lname, ssn from employee";
  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
      echo "<option value='" . $row['ssn'] . "'>" . $row['fname'] . " " . $row['lname'] . "</option>";
  }


  echo"</select>
  Manager Start Date: <input type='text' name='mgrstartdate' class='form-control' required='true'>
  <br><button type='submit' type='button' class='btn btn-primary form-control'>Add New Department</button>
</form></div>";

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

</head>


</html>
