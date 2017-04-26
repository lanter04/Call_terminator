<?php
session_start();
if($_SESSION['access'] == "Successful"){

$username_mysql = "root";
$pass_mysql = "scripts_listentrust";
$dbname = "channel_terminator";
$servername_mysql = "localhost";

$username = $_POST['username'];
$password = $_POST['password'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];

$_SESSION['username'] = $username;
$_SESSION['password'] = $password;
$_SESSION['firstname'] = $firstname;
$_SESSION['lastname'] = $lastname;
$_SESSION['email'] = $email;

// Signing up a new users

// Create connection
$conn = new mysqli($servername_mysql, $username_mysql, $pass_mysql, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO users (username,password,firstname,lastname,email) ".
		"values(\"$username\",\"$password\",\"$firstname\",\"$lastname\",\"$email\")";

$result = $conn->query($sql);

echo "Your user has been created, go to main page and login please";
		
?>		

<form action="login_page.php">
	<input type="submit" value="Go to Login page">
</form>

<?php 
}else{
	header("Location: http://localhost/test/logout.php");
}?>