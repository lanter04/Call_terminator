<?php
// Script para colgar llamadas por medio de la seleccion de las llamadas activas

session_start(); //Inicio de sesion necesitado para utilizar variables de sesion

// Se le asigna un valor por default a $opcion para cuando evitar el error de variable indefinida
$opcion = (isset($_POST['opcion']) !== FALSE ? $_POST['opcion'] : "");
//echo "variable opcion: ".$opcion;

if($_SESSION['access'] == "Success"){ //Verifica si el usuario tiene una sesion de acceso activa
	
	//Abre socket en Asterisk para ejecutar comando "core show channels"
	$socket = fsockopen("172.16.10.54","5038", $errno, $errstr, 300);  
	
	//Verifica si la conexion a Asterisk fue exitosa
	if (!$socket) {
		echo "$errstr ($errno)\n";
	} else {
		fputs($socket, "Action: Login\r\n"); 
		fputs($socket, "UserName: admin\r\n");
		fputs($socket, "Secret: 9791e7f64a728f633a28df5d75035e3f\r\n\r\n");
		fputs($socket, "Action: Command\r\n");
		fputs($socket, "Command: core show channels verbose\r\n\r\n"); //Ejecucion del comando que muestra las llamadas activas
		fputs($socket, "Action: Logoff\r\n\r\n");
		
		$i = 0; //Variable necesaria para el array "extension_array" 
		$call_detector = FALSE; //Variable que notifica cuando hay y no hay llamadas, valor por default FALSE
		
		while (!feof($socket)) {
			$output = fgets($socket); // Aqui se guarda la salida del comando en Asterisk (por renglon)
			//echo $output.'<br>';
			$array_output = str_split($output);
			$string = substr($output,0,3); // Variable para verificar si el renglon empieza con "SIP"
			if($string == "SIP"){
				$call_detector = TRUE; // En este momento se sabe que si hay llamadas activas
				$split = explode(":", $output);
				$hour = substr($split[0], -2);
				$min = $split[1];
				$seg = substr($split[2], 0, 2);
				$asterisk_channel = explode(" ", $output); //Se guarda el canal de cada llamada en el array $asterisk_channel
				//echo "asterisk channel: ".$asterisk_channel[0];  
				$extension_number = explode("-", $asterisk_channel[0]); //Se guarda la extension de cada llamada
				//echo $extension_number[0];
				$extension_array[$extension_number[0]] = $hour.":".$min.":".$seg;
				//$extension_array[$i] = $extension_number[0]; // Se hace arreglo con todas las extensiones que tienen llamada activa
				$i++; // Indice del array que va incrementando
				if($opcion == $extension_number[0]){ //Cuando se selecciona una extension del dropdown menu se crea la variable $selected_channel
					//echo $asterisk_channel[0];
					if($opcion !== "0"){ //Se checa que se haya seleccionado una opcion valida 
						$selected_channel = $asterisk_channel[0]; // Canal seleccionado 
						//echo $selected_channel;
					}
							
					
				}
			}
		}
		//echo "Selected channel: ".$selected_channel;
	
		fclose($socket);
	}
	
	/*echo "<p>";
	var_dump($extension_array);
	echo "<p>";*/
	
}else{
	header("Location: http://172.16.10.31/test/login_page.php"); // Redireccion a login page cuando el usuario no tiene una sesion de acceso exitosa
}

?>

<html>
	<head>
		<link rel="stylesheet" href="css/command.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>
		<body>
		<h2 class="center">CALL TERMINATOR</h2>
			<form action="page.php" method="post">
				<table>	
					<tr>
						<th>
								<select name="opcion">
								<?php 
									if($call_detector !== FALSE){ //Cuando hay llamadas activas
										echo "<option value=\"0\">Select an active call</option>";
										foreach ($extension_array as $extensions => $current_time){
												echo "<option value=\"$extensions\">$extensions $current_time</option>"; //Opciones del dropdown menu con las llamadas activas
										}
									}else{ //Cuando no hay llamadas activas 
										echo "<option value=\"noopcion\">No active calls at the moment</option>"; //Unica opcion del dropdown menu cuando no hay llamadas
									}
								?>
							</select>
						</th>
						<th>
							<input type="submit" class="btn btn-success" value="Hangup"> <!-- Boton terminador de llamadas -->
						</th>
					</tr>
				</table>		
			</form>
		</body>
</html>

<?php

if($call_detector !== FALSE && $opcion != "noopcion" && $opcion != "0" && $opcion != NULL){ //Verifica si hay llamadas y si la variable $opcion tiene un valor adecuado

	$socket2 = fsockopen("172.16.10.54","5038", $errno, $errstr, 300); //Segundo socket en Asterisk para ejecutar el comando terminador de llamadas
	if (!$socket2) {
		echo "$errstr ($errno)\n";
	} else {
		fputs($socket2, "Action: Login\r\n");
		fputs($socket2, "UserName: admin\r\n");
		fputs($socket2, "Secret: 9791e7f64a728f633a28df5d75035e3f\r\n\r\n");
		fputs($socket2, "Action: Command\r\n");
		fputs($socket2, "Command: channel request hangup ".$selected_channel."\r\n\r\n"); //Comando terminador de llamadas
		fputs($socket2, "Action: Logoff\r\n\r\n");
		
		//Borrando el ultimo valor de la variable $opcion, forzando al usuario a hacer una nueva seleccion en el dropdown menu 
		$_POST['opcion'] == "";
		
		while (!feof($socket2)) {
			$output = fgets($socket2); //Se guarda la salida del comando ejecutado
		}
			
		fclose($socket2);
		
		header("Location: http://172.16.10.31/test/page.php");		
		}
}else{ //Cuando no hay llamadas activas
	
	if($call_detector !== FALSE || $opcion == NULL){ //Caso cuando hay llamadas activas y no se ha seleccionado alguna opcion del dropdown menu
		//echo "Seleccione una extension";
	}
	if($call_detector !== TRUE && $opcion == "noopcion"){ //Caso cuando no hay llamadas activas y se presiona el boton Hangout
		//echo "No hay llamadas activas";
	}
	
}
		
/*
echo "<p>";
var_dump($extension_array);
echo "<p>";
*/
?>

<br><br>
<form action="logout.php">
				<input type="submit" value="Logout" id="Logout1"> <!-- Boton de Logout -->
</form>
