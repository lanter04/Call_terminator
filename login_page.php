<?php 
session_start();
?>

<html>
	<head>
		<title>Login Page</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<form action="login.php" method="post" class="login">
			<h2>Call Terminator</h2>
			<br><br>
			<input type="text" placeholder="&#128272; Username" name="username">
			<input type="password" placeholder="&#128272; Password" name="password">
			<input type="submit" value="Sign in" name="button">
		</form>
	</body>
</html> 