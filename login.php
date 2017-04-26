<?php
session_start();

$username_mysql = "root";
$pass_mysql = "scripts_listentrust";
$dbname = "channel_terminator";
$servername_mysql = "localhost";

// Create connection
$conn = new mysqli($servername_mysql, $username_mysql, $pass_mysql, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$_SESSION['username'] = $_POST['username'];
$username = $_SESSION['username'];
$_SESSION['password'] = $_POST['password'];
$password = $_SESSION['password'];

// Consult in database if username and password are correct
$sql = "SELECT username, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
		if($username == $row['username'] && $password == $row['password']){
			$access_status = "Successfull";
			$_SESSION['access'] = "Success";
			echo "You have access to the page";
			break;
		}else {
			$access_status = "Denied"; 
		}
	}
	if($access_status == "Successfull"){
		header('Location: http://172.16.10.31/test/page.php');
		echo "You are logged";
	}else{
		echo "Access Denied <br> Username or Password incorrect";
	}
} else {
	echo "0 results";
}
$conn->close();


?>

<html>

<form action="login_page.php">

<input type="submit" value="Log out">

</form>

</html>
