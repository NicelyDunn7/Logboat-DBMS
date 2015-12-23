
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
	$mysqli = new mysqli($hostname, $username, $password, $dbname);
	if ($mysqli->connect_error) {
	        die("Connection failed: " . $mysqli->connect_error);
	}
	/*
	if(!empty($_POST)){
		header("Location: https://logboat1.cloudapp.net/login/index.php");
	}
	*/

function fail(){
	global $mysqli;
	$mysqli->close();
	header("Location: https://logboat1.cloudapp.net/home.php");
	exit();
}

function insertBatch(){
	global $mysqli;
	$batch_id = $_POST["bid"];
        $brew_day = $_POST["batch_brew_day"];
        $ferm_id = $_POST["batch_f_id"];
        $pack_type = $_POST["batch_packaging"];
        $ship_type = $_POST["batch_shipment"];	
	
	//check to see if batch id already exists
	if($batch_check = $mysqli->prepare("SELECT bid FROM batch WHERE bid = ?")){
		$batch_check->bind_param("s", htmlspecialchars($batch_id));
		$batch_check->execute();
		$batch_check->bind_result($checker6);
		$batch_check->fetch();
		$batch_check->close();

		if($checker6 == $batch_id){
			$_SESSION['insert_fail'] = 7;
			fail();
		}
	}
		
	if($batch_stmt = $mysqli->prepare("INSERT INTO batch VALUES(?,?,?,?,?)")){
		$batch_stmt->bind_param("sssss", htmlspecialchars($batch_id), htmlspecialchars($brew_day), htmlspecialchars($ferm_id), htmlspecialchars($pack_type), htmlspecialchars($ship_type));
		$batch_stmt->execute();
		$batch_stmt->close();
	}else{
		$_SESSION['insert_fail'] = 1;
		fail();
	}
}

function insertBeer(){
	global $mysqli;
	$beer_id = $_POST["beer_id"];
	$recipe = $_POST["beer_recipe"];

	//determine if beer_id is already taken
	if($beer_check = $mysqli->prepare("SELECT beerID FROM beer WHERE beerID = ?")){
		$beer_check->bind_param("s", htmlspecialchars($beer_id));
		$beer_check->execute();
		$beer_check->bind_result($checker);
		$beer_check->fetch();
		$beer_check->close();
		
		if($checker == $beer_id){
			$_SESSION['insert_fail'] = 2;
			fail();
		}
		
	}		
	//insertion of new Beer ID and Recipe	
	if($beer_stmt = $mysqli->prepare("INSERT INTO beer VALUES(?,?)")){
		$beer_stmt->bind_param("ss", htmlspecialchars($beer_id), htmlspecialchars($recipe));
		$beer_stmt->execute();
		$beer_stmt->close();
	}else{
		$_SESSION['insert_fail'] = 1;
		fail();
	}
}
	
	



function insertBeerType(){
	global $mysqli;
	
	$beer_type = $_POST["beer_type"];
	$beer_name = $_POST["beer_name"];
	
	if($beerType_stmt = $mysqli->prepare("INSERT INTO beer_type VALUES(?,?)")){
		$beerType_stmt->bind_param('ss', htmlspecialchars($beer_type), htmlspecialchars($beer_name));
		$beerType_stmt->execute();
		$beerType_stmt->close();
	}else{
		$_SESSION['insert_fail'] = 1;
                fail();
	}
}
//NEEEDS WORK
function insertFermentation(){
	global $mysqli;
	$FID = $_POST['fid'];			//fid
	$value = $_POST['f_value'];		//float
	$unit_name = $_POST['f_unit_name'];	//varchar
	$f_type = $_POST['f_type'];		//varchar
	$brew_id = $_POST['f_brew_id'];		//int
	$timedate = $_POST['f_timedate'];	//timestamp
	
	//check to see if FID is already taken
	if($ferm_check = $mysqli->prepare("SELECT FID FROM fermentation WHERE FID = ?")){
		$ferm_check->bind_param("s", htmlspecialchars($FID));
		$ferm_check->execute();
		$ferm_check->bind_result($checker3);
		$ferm_check->fetch();
		$ferm_check->close();

		if($FID == $checker3){
			$_SESSION['insert_fail'] = 4;
			fail();
		}
	}

	//insert new info
	if($ferm_stmt = $mysqli->prepare("INSERT INTO fermentation VALUES(?,?,?,?,?,?)")){
		$ferm_stmt->bind_param("ssssss", htmlspecialchars($FID), htmlspecialchars($value), htmlspecialchars($unit_name), htmlspecialchars($f_type), htmlspecialchars($brew_id), htmlspecialchars($timedate));
		$ferm_stmt->execute();
		$ferm_stmt->close();
	}else{
		$_SESSION['insert_fail'] = 1;
		fail();
	}
}

function insertFermentationType(){
	global $mysqli;
	$name = $_POST['f_name'];

	if($ftype_stmt = $mysqli->prepare("INSERT INTO fermentation_type VALUES(NULL,?)")){
		$ftype_stmt->bind_param("s", htmlspecialchars($name));
		$ftype_stmt->execute();
		$ftype_stmt->close();
	}else{
		$_SESSION['insert_fail'] = 1;
		fail();
	}

}


function insertIngredient(){
	global $mysqli;
	$IngID = $_POST['ingID'];//int
	$name = $_POST['ing_name'];///varchar
	$supplier = $_POST['ing_supplier'];///varchar
	$brand = $_POST['ing_brand'];//varchar
	$amount = $_POST['ing_amount'];//float
	$unit = $_POST['ing_unit'];//varchar
	$price_per_unit = $_POST['ing_ppu'];//float
	$ingredient_type = $_POST['i_type'];//varchar
	$best_by = $_POST['ing_bb'];//date
	$lot_num = $_POST['ing_lot_num'];//double
	$low_amount = $_POST['ing_low_amount'];//float	

	if($ing_stmt = $mysqli->prepare("INSERT INTO ingredient VALUES(?,?,?,?,?,?,?,?,?,?,?)")){
		$ing_stmt->bind_param('sssssssssss', htmlspecialchars($IngID), htmlspecialchars($name), htmlspecialchars($supplier), htmlspecialchars($brand), htmlspecialchars($amount), htmlspecialchars($unit), htmlspecialchars($price_per_unit), htmlspecialchars($ingredient_type), htmlspecialchars($best_by), htmlspecialchars($lot_num), htmlspecialchars($low_amount));
                $ing_stmt->execute();
                $ing_stmt->close();
        }else{
                $_SESSION['insert_fail'] = 1;
                header("Location: tableprint.php");
        }
}

function insertKeg(){
	global $mysqli;
	$kid = $_POST['kid'];
	$fill_date = $_POST['k_fill_date'];
	$dist_date = $_POST['k_dist_date'];
	$ret_date = $_POST['k_return_date'];

	 //check to see if kid currently exists
        if($keg_check = $mysqli->prepare("SELECT kid FROM keg WHERE kid = ?")){
                $keg_check->bind_param("s", htmlspecialchars($kid));
                $keg_check->execute();
                $keg_check->bind_result($checker5);
                $keg_check->fetch();
                $keg_check->close();

                if($checker5 == $kid){
                        $_SESSION['insert_fail'] = 6;
                        fail();
                }
        }

	//insert if kid does not exist
	if($keg_stmt = $mysqli->prepare("INSERT INTO keg VALUES (?,?,?,?)")){
                $keg_stmt->bind_param("ssss", htmlspecialchars($kid), htmlspecialchars($fill_date), htmlspecialchars($dist_date), htmlspecialchars($ret_date));
                $keg_stmt->execute();
                $keg_stmt->close();
        }else{
                $_SESSION['insert_fail'] = 1;
                fail();
        }
}

function insertUnit(){
	global $mysqli;
	$unit_name = $_POST['unit_name'];
	
	//check to see if unit currently exists
	if($unit_check = $mysqli->prepare("SELECT name FROM unit WHERE name = ?")){
		$unit_check->bind_param("s", htmlspecialchars($unit_name));
		$unit_check->execute();
		$unit_check->bind_result($checker4);
		$unit_check->fetch();
		$unit_check->close();
		
		if($checker4 == $unit_name){
			$_SESSION['insert_fail'] = 5;
			fail();
		}
	}
	
	//insert new unit if it does not already exist	
	if($unit_stmt = $mysqli->prepare("INSERT INTO unit VALUES (?)")){
		$unit_stmt->bind_param("s", htmlspecialchars($unit_name));
		$unit_stmt->execute();
		$unit_stmt->close();
	}else{
		$_SESSION['insert_fail'] = 1;
		fail();
	}
}

?>

<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"><!-- Latest compiled and minified CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"><!-- Optional theme -->
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>		
	</head>
	<body>
		<div class = "container">
			<?php   
				switch($_POST['table']){
					case 'batch'://done
						insertBatch();
						break;
					case 'beer'://done
						insertBeer();
						break;
					case 'beertype'://done
						insertBeerType();
						break;
					case 'fermentationtype'://done
						insertFermentationType();
						break;
					case 'fermentation'://done
						insertFermentation();
						break;
					case 'ingredient'://done
						insertIngredient();
						break;
					case 'keg'://done
						insertKeg();
						break;
					case 'unit'://done
						insertUnit();
						break;
					default:
						header("Location: https://logboat1.cloudapp.net/forms/fail.php");
				}
				header("Location: https://logboat1.cloudapp.net/home.php");
				$mysqli->close();
			?>
		</div>
	</body>
</html>
