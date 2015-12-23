<?php
	session_start();
        if (!isset($_SERVER['HTTPS']) || !$_SERVER['HTTPS']) { // if request is not secure, redirect to secure url
           $url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
           header('Location: ' . $url);
            //exit;
        }
	session_start();
        if($_SESSION['type'] == 'regular'){
                header('Location: https://logboat1.cloudapp.net/home.php');
        }
        if($_SESSION['type'] == 'administrator'){
                header('Location: https://logboat1.cloudapp.net/home.php');
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
                                <h2>Sign In</h2>
                                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                                        <div class="row form-group">
                                                <input class='form-control' type="text" name="username" placeholder="username">
                                        </div>
                                        <div class="row form-group">
                                                <input class='form-control' type="password" name="password" placeholder="password">
                                        </div>
                                        <div class="row form-group">
                                                <input class=" btn btn-info" type="submit" name="submit" value="Login"/>
                                                <a href="register.php" class="btn btn-danger">Register</a>
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

		//Run the prepared statement to get user data from the table if it matches
		$user = $_POST['username'];
		if($stmt = $link->prepare("SELECT hashed_password, account FROM user WHERE username = ?")){
                	$stmt->bind_param('s', htmlspecialchars($user));
                	$stmt->execute();
                	$stmt->bind_result($db_hash, $accType);
                	$stmt->fetch();
                	$stmt->close();
			
			if(password_verify($_POST['password'], $db_hash)){
				$_SESSION['username'] = $_POST['username'];
				if($accType == "administrator"){
					$_SESSION['type'] = "administrator";
					header('Location: https://logboat1.cloudapp.net/home.php');
				}else if($accType == "regular"){
					$_SESSION['type'] = "regular";
					header('Location: https://logboat1.cloudapp.net/home.php');
				}
			}else{
				echo "Authentication Failure";
			}
		}else{
			echo "Authentication Failure";
		}
	}
	?>
	</div>
</body>
</html>
