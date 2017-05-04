<html>
	<head></head>
		<body>
			<?php
			
			
			$post_extension = $_POST['extension'];
			$sip = "SIP/".$post_extension; // SIP/3000
			$selected_channel = "";
			
			$socket = fsockopen("172.16.10.54","5038", $errno, $errstr, 300); 
			$socket2 = fsockopen("172.16.10.54","5038", $errno, $errstr, 300); 
			if (!$socket) { 
				echo "$errstr ($errno)\n"; 
			} else { 
				fputs($socket, "Action: Login\r\n"); 
				fputs($socket, "UserName: admin\r\n"); 
				fputs($socket, "Secret: 9791e7f64a728f633a28df5d75035e3f\r\n\r\n"); 
				fputs($socket, "Action: Command\r\n"); 
				fputs($socket, "Command: core show channels\r\n\r\n");
				//fputs($socket, "Command: channel request hangup SIP/3001-00000002\r\n\r\n");
				fputs($socket, "Action: Logoff\r\n\r\n"); 				
			$i = 0;	
			$call_detector = FALSE;
			while (!feof($socket)) { 
				$output = fgets($socket);
				//echo $output.'<br>'; 
				$string = substr($output,0,3);
				if($string == "SIP"){
					$call_detector = TRUE;
					$asterisk_channel = explode(" ", $output);
					//echo $asterisk_channel[0];
					$extension_number = explode("-", $asterisk_channel[0]);
					echo $extension_number[0];
					$extension_array[$i] = $extension_number[0];
					$i++;
					if($extension_number[0] == $sip){
						//echo $asterisk_channel[0];
						$selected_channel = $asterisk_channel[0];
						//echo $selected_channel;
					}
				}
			} 
			//echo "Selected channel: ".$selected_channel;
			
			fclose($socket); 
			}
			if($call_detector == TRUE){	
				if(!$socket2) {
					echo "$errstr ($errno)\n";
				} else {
					fputs($socket2, "Action: Login\r\n");
					fputs($socket2, "UserName: admin\r\n");
					fputs($socket2, "Secret: 9791e7f64a728f633a28df5d75035e3f\r\n\r\n");
					fputs($socket2, "Action: Command\r\n");
					//fputs($socket, "Command: core show channels\r\n\r\n");
					fputs($socket2, "Command: channel request hangup ".$selected_channel."\r\n\r\n");
					echo "The call has been successfully terminated";
					fputs($socket2, "Action: Logoff\r\n\r\n");
				
					while (!feof($socket2)) {
						$output = fgets($socket2);
					}
					
					fclose($socket2);
				}
			}else{
				echo "No calls right now";
			}
			
			$_SESSION['channel'] = $selected_channel;
			
			echo "<p>";
			var_dump($extension_array);
			echo "<p>";
			// test
			?> 
		</body> 
</html>