<?php
        //If the user heads to an unsecured page, redirect to a secured page
        if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
           $url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
           header('Location: ' . $url);
            //exit;
        }
        //Verify that the user on this page is a confirmed admin
        session_start();
        if(!isset($_SESSION['type']))
	{
		header('Location: https://logboat1.cloudapp.net/index.php');
	}

//If the user heads to an unsecured page, redirect to a secured page
if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
	$url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('Location: ' . $url);
	//exit;
}

//Connect to the MySQL Account on Azure Server
	$hostname = "";
	$username = "";
	$password = "";
	$dbname = "";
	$mysqli = new mysqli($hostname, $username, $password, $dbname);
	if ($mysqli->connect_error) {
	        die("Connection failed: " . $mysqli->connect_error);
	}
	
	//fetch table rows from mysql db
            $sql = "select bid, brewday from Batch";
            $result = mysqli_query($mysqli, $sql) or die("Error in Selecting " . mysqli_error($connection));
	
	$eventarray = array();
    while($row =mysqli_fetch_assoc($result))
    {
        $eventarray[] = $row;
    }
	
	//print_r($eventarray);
?>
	
<html>
<head>	

<style>
	#calendar{
		padding: 80px;
		
	}

</style>
<title></title>
			<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
			<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
			<script src="moment.js"></script>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.css">
			<link rel="stylesheet" href="cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.print.css">
			
		<?php include('../navbar.php'); ?>
				<script>
				
				$(document).ready(function() {

				// page is now ready, initialize the calendar...

				$('#calendar').fullCalendar({
				// put your options and callbacks here
				
					theme: true,
                    
                   events: newEvents
				
				})
					
					
					
				
				});
				
				
				
				
				</script>
				
				<script>
					var events = <?php echo json_encode( $eventarray ); ?>;
					
						
						var newEvents = $.map(events, function(item) {
						return {
							title: item.bid,
							start: item.brewday,
							
						};
					});
					
				
				</script>
	
</head>
<body>
	<div id="calendar"></div>

</body>
</html>
