<?php
session_start();
if($_SESSION['access'] == "Success"){
?>
<html>
	<head><h2>Call terminator</h2></head>
		<body>
		Extension para colgar: 
			<form action="command_asterisk.php" method="post">
				<input type="text" placeholder="Asterisk extension to hangup" name="extension">	
				<input type="submit" value="Hangup">		
			</form>	
			
			<form action="logout.php">
				<input type="submit" value="Logout">
			</form>
	
		</body>	
</html>
<?php 
}else{
	header("Location: http://172.16.10.31/test/login_page.php");
}
?>