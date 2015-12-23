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

//Connect to the MySQL Account on Azure Server
$hostname = "";
$username = "";
$password = "";
$dbname = "";
$link = new mysqli($hostname, $username, $password, $dbname);
if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
}

//display non-editable textbox for attribute $key
function printNonEditable($key) {
	echo "<div class='form-group'>";
	echo "<label class='inputdefault'>".$key."</label>";
	echo "<input class='form-control' type='text' name='".$key."' value='".$_POST[$key]."' readonly>";
	echo "</div>";
}

//display editable textbox for attribute $key
function printInput($key) {
	echo "<div class='form-group'>";
	echo "<label class='inputdefault'>".$key."</label>";
	echo "<input class='form-control' type='text' name='".$key."' value='".$_POST[$key]."'>";
	echo "</div>";
}

//display editable textbox for numeric attribute $key
function printNumeric($key) {
	echo "<div class='form-group'>";
	echo "<label class='inputdefault'>".$key."</label>";
	echo "<input class='form-control' type='number' name='".$key."' value='".$_POST[$key]."'>";
	echo "</div>";
}

//display editable textbox for timedate attribute $key
function printDatetime($key) {
	echo "<div class='form-group'>";
	echo "<label class='inputdefault'>".$key."</label>";
	echo "<input class='form-control' type='date' name='".$key."' value='".$_POST[$key]."'>";
	echo "</div>";
}

//editable form for records from the batch table
function displayBatch() {
	echo "<form action='update.php' method='POST' >";
	echo "<input type='hidden' name='table' value='batch'>";
	printNonEditable('bid');
	printDatetime('brewday');
	printNonEditable('fermentation_id');
	printInput('packaging');
	printInput('shipment');
	echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
	echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
	echo "</form>";
}

//editable form for records from the beer table
function displayBeer() {
        echo "<form action='update.php' method='POST' >";
        echo "<input type='hidden' name='table' value='beer'>";
        printNonEditable('beerID');
	printInput('recipe');
        echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
        echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
        echo "</form>";
}

//editable form for records from the beer_type table
function displayBeer_type() {
        echo "<form action='update.php' method='POST' >";
        echo "<input type='hidden' name='table' value='beer_type'>";
        printNonEditable('typeID');
        printInput('name');
        echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
        echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
        echo "</form>";
}

//editable form for records from the fermentation table
function displayFermentation() {
        echo "<form action='update.php' method='POST' >";
        echo "<input type='hidden' name='table' value='fermentation'>";
        printNonEditable('FID');
        printNumeric('value');
	printInput('unit_name');
	printInput('fermentation_type');
	printNumeric('brew_id');
	printDatetime('timedate');
        echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
        echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
        echo "</form>";
}

//editable form for records from the fermentation_type table
function displayFermentation_type() {
        echo "<form action='update.php' method='POST' >";
        echo "<input type='hidden' name='table' value='fermentation_type'>";
        printNonEditable('ftypeID');
        printInput('name');
        echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
        echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
        echo "</form>";
}

//editable form for records from the ingredient table
function displayIngredient() {
        echo "<form action='update.php' method='POST' >";
        echo "<input type='hidden' name='table' value='ingredient'>";
        printNonEditable('IngID');
	printNonEditable('name');
	printInput('supplier');
	printInput('brand');
	printNumeric('amount');
	printInput('unit');
	printNumeric('price_per_unit');
	printInput('ingredient_type');
	printDatetime('best_by');
	printNumeric('lot_num');
	printNumeric('low_amount');
        echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
        echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
        echo "</form>";
}

//editable form for records from the keg table
function displayKeg() {
	echo "<form action='update.php' method='POST' >";
        echo "<input type='hidden' name='table' value='keg'>";
        printNonEditable('kid');
        printDatetime('fill_date');
	printDatetime('distribution_date');
	printDatetime('return_date');
        echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
        echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
        echo "</form>";
}

//editable form for records from the unit table
function displayUnit() {
	echo "<form action='update.php' method='POST' >";
        echo "<input type='hidden' name='table' value='unit'>";
	echo "<input type='hidden' name='oldName' value='".$_POST['name']."'>";
        printInput('name');
        echo "<input class='btn btn-info' type='submit' name='save' value='Save'>";
        echo "<a class='btn btn-danger' href='home.php'>Cancel</a>";
        echo "</form>";
}

function saveBatch() {
	global $link;
	$sql = "UPDATE batch SET brewday=?, packaging=?, shipment=? WHERE bid=?";
	if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
		mysqli_stmt_bind_param($stmt, "ssss", htmlspecialchars($_POST['brewday']), htmlspecialchars($_POST['packaging']), htmlspecialchars($_POST['shipment']), htmlspecialchars($_POST['bid'])) or die("bind param");
		if(mysqli_stmt_execute($stmt)) {//execute successful
			echo "<h2>Successfully Saved Record</h2>";
		} else { 
			fail(); 
		}
	} else { //prepare failed
		fail(); 
	}
}

function saveBeer() {
        global $link;
        $sql = "UPDATE beer SET recipe=? WHERE beerID=?";
        if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
                mysqli_stmt_bind_param($stmt, "ss", htmlspecialchars($_POST['recipe']), htmlspecialchars($_POST['beerID'])) or die("bind param");
                if(mysqli_stmt_execute($stmt)) {//execute successful
                        echo "<h2>Successfully Saved Record</h2>";
                } else {
                        fail();
                }
        } else { //prepare failed
                fail();
        }
}

function saveBeer_type() {
        global $link;
        $sql = "UPDATE beer_type SET name=? WHERE typeID=?";
        if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
                mysqli_stmt_bind_param($stmt, "ss", htmlspecialchars($_POST['name']), htmlspecialchars($_POST['typeID'])) or die("bind param");
                if(mysqli_stmt_execute($stmt)) {//execute successful
                        echo "<h2>Successfully Saved Record</h2>";
                } else {
                        fail();
                }
        } else { //prepare failed
                fail();
        }
}

function saveFermentation() {
        global $link;
        $sql = "UPDATE fermentation SET value=?, unit_name=?, fermentation_type=?, brew_id=?, timedate=? WHERE FID=?";
        if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
                mysqli_stmt_bind_param($stmt, "ssssss", htmlspecialchars($_POST['value']), htmlspecialchars($_POST['unit_name']), htmlspecialchars($_POST['fermentation_type']), htmlspecialchars($_POST['brew_id']), htmlspecialchars($_POST['timedate']), htmlspecialchars($_POST['FID'])) or die("bind param");
                if(mysqli_stmt_execute($stmt)) {//execute successful
                        echo "<h2>Successfully Saved Record</h2>";
                } else {
                        fail();
                }
        } else { //prepare failed
                fail();
        }
}

function saveFermentation_type() {
        global $link;
        $sql = "UPDATE fermentation_type SET name=? WHERE ftypeID=?";
        if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
                mysqli_stmt_bind_param($stmt, "ss", htmlspecialchars($_POST['name']), htmlspecialchars($_POST['ftypeID'])) or die("bind param");
                if(mysqli_stmt_execute($stmt)) {//execute successful
                        echo "<h2>Successfully Saved Record</h2>";
                } else {
                        fail();
                }
        } else { //prepare failed
                fail();
        }
}

function saveIngredient() {
        global $link;
        $sql = "UPDATE ingredient SET supplier=?, brand=?, amount=?, unit=?, price_per_unit=?, ingredient_type=?, best_by=?, lot_num=?, low_amount=? WHERE IngID=?";
        if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
                mysqli_stmt_bind_param($stmt, "ssssssssss", htmlspecialchars($_POST['supplier']), htmlspecialchars($_POST['brand']), htmlspecialchars($_POST['amount']), htmlspecialchars($_POST['unit']), htmlspecialchars($_POST['price_per_unit']), htmlspecialchars($_POST['ingredient_type']), htmlspecialchars($_POST['best_by']), htmlspecialchars($_POST['lot_num']), htmlspecialchars($_POST['low_amount']), htmlspecialchars($_POST['IngID'])) or die("bind param");
                if(mysqli_stmt_execute($stmt)) {//execute successful
			echo "<h2>Successfully Saved Record</h2>";
                } else {
                        fail();
                }
        } else { //prepare failed
                fail();
        }
}

function saveKeg() {
        global $link;
        $sql = "UPDATE keg SET fill_date=?, distribute_date=?, return_date=? WHERE kid=?";
        if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
                mysqli_stmt_bind_param($stmt, "ssss", htmlspecialchars($_POST['fill_date']), htmlspecialchars($_POST['distribution_date']), htmlspecialchars($_POST['return_date']), htmlspecialchars($_POST['kid'])) or die("bind param");
                if(mysqli_stmt_execute($stmt)) {//execute successful
                        echo "<h2>Successfully Saved Record</h2>";
                } else {
                        fail();
                }
        } else { //prepare failed
                fail();
        }
}

function saveUnit() {
        global $link;
        $sql = "UPDATE unit SET name=? WHERE name=?";
        if ($stmt = mysqli_prepare($link, $sql)) {//prepare successful
                mysqli_stmt_bind_param($stmt, "ss", htmlspecialchars($_POST['name']), htmlspecialchars($_POST['oldName'])) or die("bind param");
                if(mysqli_stmt_execute($stmt)) {//execute successful
                        echo "<h2>Successfully Saved Record</h2>";
                } else {
                        fail();
                }
        } else { //prepare failed
                fail();
        }
}

//no table was provided, display error message
function fail() {
	header("Location: fail.php");
}

function success() {
	header("Location: success.php");
}

?>
<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"><!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"><!-- Optional theme -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</head>
	<body>
	<?php include('navbar.php'); ?>
		<div class="container">
<?php
if(isset($_POST['update'])) {//submit came from index.php to update
	if(isset($_POST['table'])) {//do we have table information?
		switch($_POST['table']) {//what table are we updating
			case "batch":
				echo "<h2>Edit Record</h2>";
				displayBatch();
				break;
			case "beer":
				echo "<h2>Edit Record</h2>";
				displayBeer();
				break;
                        case "beer_type":
                                echo "<h2>Edit Record</h2>";
                                displayBeer_type();
                                break;
                        case "fermentation":
                                echo "<h2>Edit Record</h2>";
                                displayFermentation();
                                break;
                        case "fermentation_type":
                                echo "<h2>Edit Record</h2>";
                                displayFermentation_type();
                                break;
                        case "Ingredient":
                                echo "<h2>Edit Record</h2>";
                                displayIngredient();
                                break;
                        case "keg":
                                echo "<h2>Edit Record</h2>";
                                displayKeg();
                                break;
                        case "unit":
                                echo "<h2>Edit Record</h2>";
                                displayUnit();
                                break;
			default:
				//echo $_POST['table'];
				fail();
				break;
		}	
	} else {//no table info found
		noTable();
	}
} else if(isset($_POST['save'])) {//submit came from request to save form data
	if(isset($_POST['table'])) {//do we have table information?
		switch($_POST['table']) {//what table are we saving
                        case "batch":
                                echo "<h2>Edit Record</h2>";
                                displayBatch();
				saveBatch();
                                break;
                        case "beer":
                                echo "<h2>Edit Record</h2>";
                                displayBeer();
				saveBeer();
                                break;
                        case "beer_type":
                                echo "<h2>Edit Record</h2>";
                                displayBeer_type();
				saveBeer_type();
                                break;
                        case "fermentation":
                                echo "<h2>Edit Record</h2>";
                                displayFermentation();
				saveFermentation();
                                break;
                        case "fermentation_type":
                                echo "<h2>Edit Record</h2>";
                                displayFermentation_type();
				saveFermentation_type();
                                break;
                        case "ingredient":
                                echo "<h2>Edit Record</h2>";
                                displayIngredient();
				saveIngredient();
                                break;
                        case "keg":
                                echo "<h2>Edit Record</h2>";
                                displayKeg();
				saveKeg();
                                break;
                        case "unit":
                                echo "<h2>Edit Record</h2>";
                                displayUnit();
				saveUnit();
                                break;
                        default:
                                fail();
                                break;

		}	
	}
}
?>
		</div>
	</body>
</html>
