<?php

require_once "Twilio/autoload.php"; 
 
use Twilio\TwiML\MessagingResponse;

$response = new MessagingResponse();

// MySQL Server Connection

$conn = mysqli_connect('[SERVER_NAME]', '[USER_NAME]', '[PASSWORD]', ['DATABASE_NAME']);

/* Save and Insert Test Variables without making any HTTP Request to this page by uncommenting these lines below on the first attempt to execute this code.
   After inserting variables, switch lines commented again and make HTTP Requests to executing following code.

$sql_insert = "INSERT INTO `[TABLE_NAME]` (`Data_1`, `Data_2`, `Data_3`, `Data_4`, `Data_5`, `Data_6`, `Command`) VALUES ('TEST', 'TEST', 'TEST', 'TEST', 'TEST', 'TEST', 'TEST')";
mysqli_query($conn, $sql_insert);

*/

// Get Variables from the database and proceed.
$sql_get_data = "SELECT * FROM `[TABLE_NAME] WHERE ...`";
if($rows = mysqli_fetch_assoc(mysqli_query($conn, $sql_get_data))){
	$data = array(
		"DATA_1" => $rows['Data_1'],
		"DATA_2" => $rows['Data_2'],
		"DATA_3" => $rows['Data_3'],
		"DATA_4" => $rows['Data_4'],
		"DATA_5" => $rows['Data_5'],
		"DATA_6" => $rows['Data_6'],
		"COMMAND" => $rows['Command']
		);


if(isset($_GET['Data_1']) && isset($_GET['Data_2']) && isset($_GET['Data_3']) && isset($_GET['Data_4']) && isset($_GET['Data_5']) && isset($_GET['Data_6'])){
	// If all variables are sent by NodeMCU, save them to the MySQL Database and print the pre-defined command on the database as response to send the command to NodeMCU.
	
	$new_data = array(
		"DATA_1" => strip_tags(mysqli_real_escape_string($this->conn, $_GET['Data_1'])),
		"DATA_2" => strip_tags(mysqli_real_escape_string($this->conn, $_GET['Data_2']),
		"DATA_3" => strip_tags(mysqli_real_escape_string($this->conn, $_GET['Data_3'])),
		"DATA_4" => strip_tags(mysqli_real_escape_string($this->conn, $_GET['Data_4'])),
		"DATA_5" => strip_tags(mysqli_real_escape_string($this->conn, $_GET['Data_5'])),
		"DATA_6" => strip_tags(mysqli_real_escape_string($this->conn, $_GET['Data_6']))
	);
	
	// UPDATE Variables in the database.
	$sql_update = "UPDATE `[TABLE_NAME]` SET `Data_1` = '".$new_data["DATA_1"]."', `Data_2` = '".$new_data["DATA_2"]."', `Data_3` = '".$new_data["DATA_3"]."', `Data_4` = '".$new_data["DATA_4"]."', `Data_5` = '".$new_data["DATA_5"]."', `Data_6` = '".$new_data["DATA_6"]."' WHERE ...";
	mysqli_query($conn, $sql_update);
	
	echo $data['COMMAND'];
	exit();
}else{
	/* If a message is transferred by a verified phone to this application, get the body. 
	 
	   Edit the following keywords depending on the requirements of your project...
	
	*/
	if(isset($_POST['Body'])){
		switch($_POST['Body']){
		case "Temperature":
		$response->message("Temperature -> ".$data['DATA_1']);
		break;
		case 'Pressure':
		$response->message('Pressure -> '.$data['DATA_2']);
		break;
		case "Altitude":
		$response->message('Altitude -> '.$data['DATA_3']);
		break;
		case 'Date':
		$response->message('Date -> '.$data['DATA_4']);
		break;
		case 'Time':
		$response->message('Time -> '.$data['DATA_5']);
		break;
		case 'Latitude and Longitude':
		$response->message('Latitude and Longitude -> '.$data['DATA_6']);
		break;
		case 'Map Location':
		$response->message('https://www.google.com/maps/search/?api=1&query='.$data['DATA_6']);
		break;
		case 'Go Straight':
		/* Save Command to the database. */
	    saveCommand('[...]');
		$response->message("Command is transferred to the device.");
		break;
		case 'Go Back':
		/* Save Command to the database. */ 
		saveCommand('[...]');
		$response->message("Command is transferred to the device.");
		break;
		case 'Go Right':
		/* Save Command to the database. */ 
		saveCommand('[...]');
		$response->message("Command is transferred to the device.");
		break;
		case 'Go Left':
		/* Save Command to the database. */ 
		saveCommand('[...]');
		$response->message("Command is transferred to the device.");
		break;
		case 'Halt':
		/* Save Command to the database. */ 
		saveCommand('[...]');
		$response->message("Command is transferred to the device.");
		break;
		case 'Programmed by':
		$response->message('This application is programmed by Kutluhan Aktar and provided by TheAmplituhedron. For more information, please click the link:'."\n".' https://www.theamplituhedron.com/projects/WhatsApp-Mapping-and-Weather-Forecast-Chat-Bot/');
		break;
		case 'About':
		$response->message('https://www.theamplituhedron.com/about/');
		break;
		case 'How r u?':
		$response->message("Thanks for asking."."\n"."Data processing is running just fine 😃");
		break;
		case 'Further information':
		$response->message('https://www.theamplituhedron.com/dashboard/WhatsApp-Two-Way-Connection-Hub/');
		break;		
		case 'Contact':
		$response->message('You can contact me directly at info@theamplituhedron.com');
		break;
		case 'Spidey':
		$message = $response->message('');
        $message->body('Media Feature Testing.'."\n".'Spider-Man');
        $message->media('https://www.theamplituhedron.com/dashboard/WhatsApp-Two-Way-Connection-Hub/Spider.jpg');
		break;	
		case 'Batman':
		$message = $response->message('');
        $message->body('Media Feature Testing.'."\n".'Batman');
        $message->media('https://www.theamplituhedron.com/dashboard/WhatsApp-Two-Way-Connection-Hub/Batman.jpg');
		break;
		case 'Help':
		$response->message('Integrated Keywords:'."\n\n".' Temperature '."\n".' Pressure '."\n".' Altitude '."\n".' Date '."\n".' Time '."\n".' Latitude and Longitude '."\n".' Map Location '."\n".' Go Straight '."\n".' Go Back '."\n".' Go Right '."\n".' Go Left '."\n".' Halt '."\n".' Programmed by '."\n".' About '."\n".' How r u? '."\n".' Further information '."\n".' Contact '."\n".' Spidey '."\n".' Batman '."\n".' Help');
		break;
		default:
		$response->message('Not defined!'."\n".'You have entered: '.$_POST['Body']."\n".'Please enter Help to view all keywords.');
		
	}
		print $response;
		
	}else{
		echo 'Nothing Detected! Check the connection!';
		exit();
	}
}

}

/* Save Commands to the database to control NodeMCU. */
function saveCommand($var){
	 $sql_save_command = "UPDATE `[TABLE_NAME]` SET `Command` = '$var' WHERE ...";	
     mysql_query($conn, $sql_save_command);		
}

?>