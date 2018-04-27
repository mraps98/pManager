<?php

$realm = 'Restricted area';

//user => password
$users = array(
                'admin' => 'mypass',
                'guest' => 'guest'
);

if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
                header('HTTP/1.1 401 Unauthorized');
                header('WWW-Authenticate: Digest realm="' . $realm . '",qop="auth",nonce="' . uniqid() . '",opaque="' . md5($realm) . '"');

                die('Text to send if user hits Cancel button');
}


// analyze the PHP_AUTH_DIGEST variable
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']]))
                die('Wrong Credentials!');


// generate the valid response
$A1             = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A2             = md5($_SERVER['REQUEST_METHOD'] . ':' . $data['uri']);
$valid_response = md5($A1 . ':' . $data['nonce'] . ':' . $data['nc'] . ':' . $data['cnonce'] . ':' . $data['qop'] . ':' . $A2);

if ($data['response'] != $valid_response)
                die('Wrong Credentials!');

// ok, valid username & password
session_start();
require('dbconfig.php');
$_SESSION['loggedin'] = true;
$_SESSION['username'] = $data['username'];

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
                echo "Connection failed<br/>";
                die("Connection failed: " . $conn->connect_error);
}

echo '
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
  <a class="navbar-brand" href="#">PManager</a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Employees</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="pages/departments.php">Departments</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="pages/projects.php">Projects</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="http://natto.mooo.com/~db4133414/doc">Documentation</a>
      </li>
    </ul>
  </div>
</nav>
';

$sql = 'SELECT e.fname, e.ssn, e.lname, e.bdate, e.salary, e.sex, d.dname, concat(f.fname," ", f.lname) AS supervisor FROM employee e LEFT JOIN employee f ON (e.superssn = f.ssn), department d WHERE (e.dno = d.dnumber)';

echo "<br><br><div class='container'><table class='table table-bordered'><tr><th>SSN</th><th>First Name</th><th>Surname</th><th>Date of B</th><th>Salary</th><th>Sex</th><th>Department</th><th>Supervisor</th><th>Projects</th></tr>";


$result     = $conn->query($sql);
$return_arr = array();

while ($row = $result->fetch_assoc()) {
                echo "<tr><td>";
                echo $row['ssn'] . "</td><td>";
                echo $row['fname'] . "</td><td>";
                echo $row['lname'] . "</td><td>";
                echo $row['bdate'] . "</td><td>";
                echo $row['salary'] . "</td><td>";
                echo $row['sex'] . "</td><td>";
                echo $row['dname'] . "</td><td>";
                echo $row['supervisor'] . "</td><td>
    <button class='assignbtn' value='" . $row['ssn'] . "'>Assign</button>";
                echo "</td></tr>";
}

echo "</table><br><br>";

echo "<form id='assign'>
<input style='display:none' type=text id='ssninput' name='ssn'>

</form>";

echo "<h1>Add Employee:</h1><br>";
echo "<form action='pages/addEmployee.php' method='post'>
  SSN: <input type='number' value='111111111' min='111111111' max='999999999' name='ssn' class='form-control' required='true'>
  First Name: <input type='text' name='fname' class='form-control' required='true'>
  Surname: <input type='text' name='lname' class='form-control' required='true'>
  Date of Birth: <input type='text' name='bdate' class='form-control' required='true'>
  Salary : <input type='number' name='salary' class='form-control' required='true'>
  Sex: <input type='text' name='sex' class='form-control' required='true' maxLength='4'>
  Department:<select class='form-control' required='true' name='dno'>";

$sql    = "select dname, dnumber from department";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['dnumber'] . "'>" . $row['dname'] . "</option>";
}

echo " </select>
Supervisor: <select class='form-control' name='superssn'><option value=''></option>";

$sql    = "select fname, lname, ssn from employee";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['ssn'] . "'>" . $row['fname'] . " " . $row['lname'] . "</option>";
}


echo "</select><br>

  <button type='submit' type='button' class='btn btn-primary form-control'>Add New Employee</button>
</form></div>";

$conn->close();

// function to parse the http auth header

/**
*
*This function parses the http auth header
*
*This function gets whats in the header and parses it in order to get the message digest.
*
*
*@param string $txt  this is the text string the this function receives
*
*
*@return void
*
*/
function http_digest_parse($txt)
{
                // protect against missing data
                $needed_parts = array(
                                'nonce' => 1,
                                'nc' => 1,
                                'cnonce' => 1,
                                'qop' => 1,
                                'username' => 1,
                                'uri' => 1,
                                'response' => 1
                );
                $data         = array();
                $keys         = implode('|', array_keys($needed_parts));

                preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

                foreach ($matches as $m) {
                                $data[$m[1]] = $m[3] ? $m[3] : $m[4];
                                unset($needed_parts[$m[1]]);
                }

                return $needed_parts ? false : $data;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>PManager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="css/styles.css">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script type="text/javascript" src="js/assign.js"></script>
</head>


</html>
