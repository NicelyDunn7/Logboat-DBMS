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
	header('Location: https://logboat1.cloudapp.net/login/index.php');
}

//Connect to the MySQL Account on Azure Server
$hostname = "";
$username = "";
$password = "";
$dbname = "";
$link = new mysqli($hostname, $username, $password, $dbname);
if ($link->connect_error) {
	die("Connection failed: " . $link->connect_error);
}

?>
<html>
	<head>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	</head>
	<?php include('../navbar.php'); ?>
	<style>
                .insertForm{
                        text-align: center;
                        padding: 20px;
                        margin: 40px;
                        border-radius:15px;
                        border: 2px solid lightblue;
                        background-color: #e6fee6;
                }
                #insertTA{
                        width: 200px;
                        margin: 0px auto 0px auto;
                }
        </style>
	<div class="container">

	<h2>Insert Record Into Fermentation Table</h2>
	<form action = 'perform_insert.php' method = 'post' class = 'insertForm'>
	 	Please enter a Fermentation ID:&nbsp;
                <input type = 'number' placeholder = '0' name = 'fid' id = 'insertTA'><br><br>

                Please enter a Fermentation value:&nbsp;
                <input type = 'number' step = '0.01' placeholder = '0.00' name = 'f_value' id = 'insertTA'><br><br>

                Please enter Unit:&nbsp;
		<select id = 'spinner2' name = 'f_unit_name'>
			<?php
				$sql = "SELECT * FROM unit";
				$result = mysqli_query($link, $sql) or die("Query Error: " . mysqli_error($link));
				while($row = mysqli_fetch_assoc($result)){
					echo "<option value ='".$row["name"]."'>".$row["name"]."</option>";
				}
			?>
		</select>
               	<br><br>

                Please enter a Fermentation Type:&nbsp;
		<select id = 'spinner' name = 'f_type'>
                <?php
                        $sql = "SELECT name FROM fermentation_type";
                        $result = mysqli_query($link, $sql) or die ("Query Error: " . mysqli_error($link));
                        while($row = mysqli_fetch_assoc($result)){
                              echo "<option value ='".$row["name"]."'>".$row["name"]."</option>";
                        }
                ?>
        	</select>
		<br><br>
		Please enter a Brew ID:&nbsp;
		<select id = 'spinner4' name = 'f_brew_id'>
			<?php
				$sql = "SELECT fermentation_id FROM batch";
                        	$result = mysqli_query($link, $sql) or die ("Query Error: " . mysqli_error($link));
                        	while($row = mysqli_fetch_assoc($result)){
                        	      echo "<option value ='".$row["fermentation_id"]."'>".$row["fermentation_id"]."</option>";
                        	}
			?>
		</select><br><br>

                Please enter a Date and Time:&nbsp;
                <input type = 'text' value = 'YYYY-MM-DD HH:00:00' name = 'f_timedate'><br><br>
		<input type = 'hidden' name = 'table' value = 'fermentation'>
                <input type = 'submit' name = 'submit' value = 'Insert Fermentation Data'><br><br>
          </form>
	</div>
</html>
