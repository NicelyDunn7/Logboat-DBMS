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

//display insert batch form
function insertBatch() {
	global $link;
				
	
	
	
        echo "<form class='insertForm' action='perform_insert.php' method='post' >
		Please enter a Batch ID:&nbsp;
		<input type = 'number' name = 'bid' class='form-control' id='insertTA'><br><br>

		Please enter a Brew Day:&nbsp;
		<input type = 'text' name = 'batch_brew_day' value = 'YYYY-MM-DD HH:00:00' class='form-control' id='insertTA'><br><br>

		Please enter a Fermentation ID:&nbsp;
		<select id = 'spinner7' name = 'batch_f_id'>";
			$sql = "SELECT FID FROM fermentation";
			$result = mysqli_query($link, $sql) or die("Query Error: " . mysqli_error($link));
                        while($row = mysqli_fetch_assoc($result)){
                                echo "<option value ='".$row["FID"]."'>".$row["FID"]."</option>";
                        }	 

	echo "</select><br><br>
		Please enter Packaging Type:&nbsp;
		<input type = 'text' name = 'batch_packaging' class='form-control' id='insertTA'><br><br>

		Please enter Shipment Type:&nbsp;
		<input type = 'text' name = 'batch_shipment' class='form-control' id='insertTA'><br><br>
		<input type = 'hidden' name = 'table' value = 'batch'>
		<input class='btn btn-info' type = 'submit' name = 'submit' value = 'Insert Batch' id='insertTA' ><br><br>      
	      </form>";
}

//display insert beer form
function insertBeer() {
        echo "<form class='insertForm' action='perform_insert.php' method='post' >
		Please enter a Beer ID:&nbsp;
		<input type = 'number' name = 'beer_id'><br><br>

		Please enter the Recipe:&nbsp;
		<input type = 'text' name = 'beer_recipe' id='insertTA'><br><br>
		<input type = 'hidden' name = 'table' value = 'beer' id='insertTA'>
		<input type = 'submit' name = 'submit' value = 'Insert Beer' id='insertTA'><br><br>
	      </form>";
}

//display insert beer type form
function insertBeerType() {
	echo "<form class='insertForm' action='perform_insert.php' method='post' >
		Please enter a Beer Type ID:&nbsp;
		<input type = 'number' name = 'beer_type'  placeholder = '0' id='insertTA'><br><br>

		Please enter a Beer Name:&nbsp;
		<input type = 'text' name = 'beer_name' id='insertTA'><br><br>
		<input type = 'hidden' name = 'table' value = 'beertype' id='insertTA'>
		<input type = 'submit' name = 'submit' value = 'Insert Beer Type' id='insertTA'><br><br>
              </form>";        
}

//dispay insert fermentation type form
function insertFermentationType() {
        echo "<form class='insertForm' action='perform_insert.php' method='post' >
		Please enter a Fermentation Name:&nbsp;
		<input type = 'text' name = 'f_name' id='insertTA'><br><br>
		<input type = 'hidden' name = 'table' value = 'fermentationtype' id='insertTA'>
		<input type = 'submit' name = 'submit' value = 'Insert Fermentation Type' id='insertTA'><br><br>
              </form>";
}


//display insert ingredient form
function insertIngredient() {
        global $link;
	echo "<form class='insertForm' action='perform_insert.php' method='post' >
		Please enter an Ingredient ID:&nbsp;
		<input type = 'number' name = 'ingID' id='insertTA' placeholder = '0'><br><br>

		Please enter an Ingredient Name:&nbsp;
		<input type = 'text' name = 'ing_name' id='insertTA'><br><br>

		Please enter an Ingredient Supplier:&nbsp;
		<input type = 'text' name = 'ing_supplier' id='insertTA'><br><br>

		Please enter an Ingredient Brand:&nbsp;
		<input type = 'text' name = 'ing_brand' id='insertTA'><br><br>

		Please enter an Ingredient Amount:&nbsp;
		<input type = 'number' name = 'ing_amount'  placeholder = '0' id='insertTA'><br><br>

		Please enter an Ingredient Unit:&nbsp;
		<select id = 'spinner3' name = 'ing_unit'>";
		
			
                                $sql = "SELECT * FROM unit";
                                $result = mysqli_query($link, $sql) or die("Query Error: " . mysqli_error($link));
                                while($row = mysqli_fetch_assoc($result)){
                                        echo "<option value ='".$row["name"]."'>".$row["name"]."</option>";
                                }

		echo "</select><br><br>
                        

		
		Please enter the Ingredient Price Per Unit:&nbsp;
		$<input type = 'number' name ='ing_ppu' placeholder = '0.00' step = '0.01' id='insertTA'><br><br>

		Please enter an Ingredient Type:&nbsp;
		<select id = 'it_spinner' name = 'i_type'>
			<option value = 'hops'>Hops</option>
			<option value = 'oats'>Oats</option>
			<option value = 'beans'>Beans</option>
		</select><br><br>

		Please enter the Ingredient's 'Best-by' Date:&nbsp
		<input type = 'date' name = 'ing_bb' id='insertTA'><br><br>

		Please enter the Ingredient's Lot Number:&nbsp;
		<input type = 'number' name = 'ing_lot_num' placeholder = '0' id='insertTA'><br><br>
	
		Please enter the Ingredient's Low Amount:&nbsp
		<input type = 'number' placeholder = '0' name = 'ing_low_amount' id = 'insertTA'><br><br>
		<input type = 'hidden' name = 'table' value = 'ingredient' id='insertTA'>
		<input type = 'submit' name = 'submit' value = 'Insert Ingredient' id='insertTA'><br><br>
              </form>";
}

//display insert keg form
function insertKeg() {
        echo "<form class='insertForm' action='perform_insert.php' method='post' >
		Please enter Keg ID:&nbsp;
		<input type = 'number' placeholder = '0' name = 'kid' id='insertTA'><br><br>

		Please enter Keg Fill Date:&nbsp;
		<input type = 'text' value = 'YYYY-MM-DD HH:00:00' name = 'k_fill_date' id='insertTA'><br><br>
		Please enter Keg Distribute Date:&nbsp;
		<input type = 'text' value = 'YYYY-MM-DD HH:00:00' name = 'k_dist_date' id='insertTA'><br><br>

		Please enter Keg Return Date:&nbsp;
		<input type = 'text' value = 'YYYY-MM-DD HH:00:00' name = 'k_return_date' id='insertTA'><br><br>
		<input type = 'hidden' name = 'table' value = 'keg' id='insertTA'>
		<input type = 'submit' name = 'submit' value = 'Insert Keg' id='insertTA'><br><br>
              </form>";
}

//display insert unit form
function insertUnit() {
        echo "<form class='insertForm' action='perform_insert.php' method='post' >
		Please enter a Unit Name:&nbsp;
		<input type = 'text' name ='unit_name' id='insertTA'><br><br>
		<input type = 'hidden' name = 'table' value = 'unit' id='insertTA'>
		<input class='btn btn-info' type = 'submit' name = 'submit' value = 'Insert Unit' id='insertTA'><br><br>
              </form>"; 
}


//no table was provided, display error message
function fail() {
        header("Location: https://logboat1.cloudapp.net/forms/fail.php");
}

function success() {
        header("Location: https://logboat1.cloudapp.net/forms/success.php");
}

?>


<html>
        <head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
		<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
		<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="moment.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.js"></script>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.min.css">
		<link rel="stylesheet" href="cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.5.0/fullcalendar.print.css">
			
		<?php include('../navbar.php'); ?>
	
        </head>
		
		<style>
		h2{
			text-align: center;
		}
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
		#calendar{
			padding: 80px;
		}
		</style>
        <body>
                <div class="container">
<?php
	if($_SESSION['table'] == 'beer_type'){
		$table = "Beer Type";
	}elseif($_SESSION['table'] == 'fermentation_type'){
		$table = "Fermentation Type";
	}else{
		$table = ucfirst($_SESSION['table']);
        }
	echo "<h2>Insert Record Into $table Table</h2>";
	switch($table) {//what table are we updating
        	case "Batch":
                	insertBatch();
                        break;
              	case "Beer":
                	//header("Location: insert_beer.php");
			insertBeer();
                        break;
               case "Beer Type":
                        insertBeerType();
                        break;
               case "Fermentation":
              	        header("Location: insert_fermentation.php");
                        break;
               case "Fermentation Type":
               		insertFermentationType();
                        break;
               case "Ingredient":
			//header("Location: insert_ingredient.php");
			insertIngredient();
                        break;
               case "Keg":
                        insertKeg();
                        break;
               case "Unit":
                        insertUnit();
                        break;
               default:
                        fail();
                        break;
	}	
?>
                </div>
				<div id="calendar"></div>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        </body>
</html>
