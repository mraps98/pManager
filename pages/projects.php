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
$sql = 'select pname, pnumber, plocation, dname from project, department where dnum=dnumber;';
$result = $conn->query($sql);

echo '<br><br><div class="container"><table class="table table-bordered"><tr><th>Project Name</th><th>Project Number</th><th>Project Location</th><th>Department Name</th>';

while ($row = $result->fetch_assoc()){
  echo '<tr><td>'.$row['pname'].'</td><td>'.$row['pnumber'].'</td><td>'.$row['plocation'].'</td><td>'.$row['dname'].'</td></tr>';
}

echo'</table>';

echo "<br><br><h1>Add Project:</h1><br>";
echo "<form action='addProject.php' method='post'>
  Project Name: <input type='text' name='pname' class='form-control' required='true' maxLength='15'>
  Project Number: <input type='text' name='pnumber' class='form-control' required='true' maxLength='5'>
  Project Location: <input type='text' name='plocation' class='form-control' required='true' maxLength='10'>
  Department: <select class='form-control' name='dnum' required='true'>";

  $sql    = "select dname, dnumber from department";
  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
      echo "<option value='" . $row['dnumber'] . "'>" . $row['dname'] . "</option>";
  }

  echo"</select>
  <br><button type='submit' type='button' class='btn btn-primary form-control'>Add New Project</button>
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
