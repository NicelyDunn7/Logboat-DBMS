<?php 

	


        //If the user heads to an unsecured page, redirect to a secured page
        if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
           $url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
           header('Location: ' . $url);
            //exit;
        }
		/*
        //Verify that the user on this page is a confirmed admin
        session_start();
        if(!isset($_SESSION['type']))
	{
		header('Location: https://logboat1.cloudapp.net/index.php');
	}
	*/

?>
<!DOCTYPE html>
<html>

<head>

<title></title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

</head>
<body>

<?php 

	include('navbar.php');

?>

<form method="GET">
	<input type="date" name="startDate">
	<input type="date" name="endDate">
	<select name="beer">
		<option value="shiphead">Shiphead</option>
		<option value="mamoot">Mamoot</option>
		<option value="snapper">Snapper</option>
		<option value="lookout">Lookout</option>
		<option value="bearhair">Bear Hair</option>
		<option value="mocha">Mocha</option>
		<option value="jupitersmoons">Jupiter's Moons</option>
	</select>

	<input type="submit" name="schedule">

</form>



<?php 
	if(isset($_GET["schedule"])){
			echo "<h1> we have a get array </h1>";
		
	}

?>




</body>
</html>
