<?php
	//If the user is not connected through HTTPS, redirect into it
	if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
	   $url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	   header('Location: ' . $url);
	    //exit;
	}
	//If already logged in, redirect to the home page	
	session_start();
	if($_SESSION['type'] == 'regular'){
                header('Location: https://logboat1.cloudapp.net/tableprint.php');
        }
	if($_SESSION['type'] == 'administrator'){
                header('Location: https://logboat1.cloudapp.net/tableprint.php');
        }
?>
<html>
	<head>
		<title>Logboat Brewery</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-sm-4 col-xs-3"></div>
				<div class="col-md-4 col-sm-4 col-xs-6">
					<h2>Create A Profile</h2>
					<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
						<div class="row form-group">
							<input class='form-control' type="text" name="username" placeholder="username">
						</div>
						<div class="row form-group">
							<input class='form-control' type="password" name="password" placeholder="password">
						</div>
						<div class="row form-group">
							<input class=" btn btn-info" type="submit" name="submit" value="Register"/>
                                			<a href="index.php" class="btn btn-danger">Back To Login</a>		
						</div>
					</form>
				</div>
			</div>
			<?php
				if(isset($_POST['submit'])) { // Was the form submitted?
			                //Connect to the MySQL Account on Azure Server
			                $hostname = "";
                                        $username = "";
                                        $password = "";
                                        $dbname = "";
			                $link = new mysqli($hostname, $username, $password, $dbname);
			                if ($link->connect_error) {
			                        die("Connection failed: " . $link->connect_error);
			                }

					//Take user input for username and password, salt and hash the password,
					//and store the new account in the database
					$sql = "INSERT INTO user(username,hashed_password,account) VALUES (?,?,'regular')";
					if ($stmt = mysqli_prepare($link, $sql)) {
						$user = htmlspecialchars($_POST['username']);
						$hpass = password_hash(htmlspecialchars($_POST['password']), PASSWORD_BCRYPT)  or die("bind param");
						mysqli_stmt_bind_param($stmt, "ss", $user, $hpass) or die("bind param");
						if(mysqli_stmt_execute($stmt)) {
							echo "<h4>Success</h4>";
						} else {
							echo "<h4>Failed</h4>";
						}
						$result = mysqli_stmt_get_result($stmt);
					} else {
						die("prepare failed");
					}
				}
			?>
		</div>
	</body>
</html>
