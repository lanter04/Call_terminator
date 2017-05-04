<?php
session_start(); //Se inicia la sesion 

/*
$username_mysql = "root"; //Username de la base de datos 
$pass_mysql = "scripts_listentrust"; 
$dbname = "channel_terminator"; //Nombre de la base de datos
$servername_mysql = "localhost"; //Direccion del servidor de la base de datos 
*/
$username_mysql = "helpdesk";
//$username_mysql = "helpdesk";
$pass_mysql = "P84ssword";
$dbname = "asterisk";
$servername_mysql = "172.16.10.54";

// Creacion de la coneccion con la base de datos 
$conn = new mysqli($servername_mysql, $username_mysql, $pass_mysql, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$_SESSION['username'] = $_POST['username']; //Se recibe username desde el Login Page y se guarda en variable de sesion
$username = $_SESSION['username']; //Se guarda variable de sesion username  
$_SESSION['password'] = $_POST['password']; //Se recibe password desde el Login Page y se guarda en variable de sesion 
$password = $_SESSION['password']; //Se guarda variable de sesion 
$password_sha1 = sha1($password);

// Consult in database if username and password are correct
//$sql = "SELECT username, password FROM users"; 
// Consult in database of FreePBX
$sql = "SELECT username, password_sha1 FROM ampusers";

$result = $conn->query($sql);

if ($result->num_rows > 0) { //Cuando se encuentran resultados de la query en la base de datos
	while($row = $result->fetch_assoc()) { // result data by each row
		if($username == $row['username'] && $password_sha1 == $row['password_sha1']){ //Se verifica si es correcta la credencial de acceso 
			$access_status = "Successfull"; //Variable que notifica si el usuario tiene una sesion de acceso exitosa
			$_SESSION['access'] = "Success"; //Variable en session que notifica que el usuario ya ha iniciado sesion exitosamente
			break; //Cuando se encuentra la credencial se detiene la busqueda en la base de datos
		}else {
			$access_status = "Denied"; //Variable que indica cuando no se encuentra la credencial en la base de datos 
		}
	}
	if($access_status == "Successfull"){ //Si se ha iniciado session correctamente se envia page.php
		header('Location: http://172.16.10.31/test/page.php'); 
		echo "You are logged";
	}else{ //Cuando no se ha iniciado session correctamente
		echo "Access Denied <br> Username or Password incorrect";
	}
} else { //Cuando no se encuentran resultados en la base de datos
	echo "0 results";
}
$conn->close();


?>

<html>

<form action="login_page.php">

<input type="submit" value="Log out">

</form>

</html>
