<?php 

session_start();

session_destroy(); //Se termina la sesion para borrar variables 

header("Location: http://172.16.10.31/test/login_page.php"); // Se envia al usuario al Login Page 

?>