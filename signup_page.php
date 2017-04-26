<?php 
//session_start();
//if($_SESSION['access'] == "Successful"){
?>
<html>
	<head>Sign up</head>
	<body>
		<form action="signup.php" method="post">
			<br>
			<div>
				<input type="text" placeholder="Username" name="username">
			</div>
			<br>
			<div>	
				<input type="password" placeholder="Password" name="password">
			</div>
			<br>
			<div>	
				<input type= "text" placeholder="Firstname" name="firstname">
			</div>
			<br>
			<div>	
				<input type="text" placeholder="Lastname" name="lastname">
			</div>
			<br>
			<div>	
				<input type="email" placeholder="Email" name="email">
			</div>
			<br>
			<div>	
				<input type="submit" value="Register">
			</div>
		</form>
		<form action="logout.php">
			<input type="submit" value="Logout">
		</form>
	</body>
</html> 
<?php 
//}else{
//	header("Location: http://localhost/test/logout.php");
//}
?>