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

?>
<html>
<head>
        <style>
                #insertButton{
                        margin: 40px;
                }
                .table{
                        margin: 20px 20px 20px 20px;
                }
                .rowNumber{
                        margin: 40px;
                }
        </style>
        <title>Main</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
<?php include('forms/navbar.php'); ?>
<?php
	switch($_SESSION['insert_fail']){
			case 1:
				echo "<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color = 'red'> Insertion Query Failed...Contact Admin.</font></h5>";
				break;
			case 2:
				echo "<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color = 'red'> Insertion Failed...Beer ID already exists.</font></h5>";
				break;
        		case 4: 
        			echo "<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color = 'red'> Insertion Failed...Fermentation ID already exists.</font></h5>";
                		break;
        		case 5:
				echo "<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color = 'red'> Insertion Failed...Unit already exists.</font></h5>";
                		break;
			case 6: 
				echo "<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color = 'red'> Insertion Failed...Keg ID already exists.</font></h5>";
        			break;
			case 7:
				echo "<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color = 'red'> Insertion Failed...Batch ID already exists.</font></h5>";
				break;
	}	
	$_SESSION['insert_fail'] = 0;
?>

<!--
<form action="insert.php" id="insertButton" class="col-md-1">
  	<input type="submit" value="Insert City" class="btn btn-info">
</form> 
-->
<form name="tables" action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4 ">
	<br> <!--added for cleanliness--!>
	<select name = "action" class="form-control">
 		<option value="update">Update</option>
  		<option value="insert">Insert</option>
	</select>
	<br> <!--added for cleanliness--!>	
	<select name = "table" class="form-control">
  		<option value="ingredient">Ingredients</option>
  		<option value="batch">Batch</option>
  		<option value="beer">Beer</option>
		<option value="beer_type">Beer Type</option>
		<option value="fermentation">Fermentation</option>
		<option value="fermentation_type">Fermentation Type</option>	
		<option value="keg">Keg</option>
		<option value="unit">Unit</option>
	</select>
	<br> <!--added for cleanliness--!>
	<input type="submit" name ="go" value="Go" class="btn btn-info"/>
</form>
<?php
if(isset($_POST['go']) && $_POST['action'] == 'insert') {
	$_SESSION['table'] = $_POST['table'];
        header("Location: https://logboat1.cloudapp.net/forms/DARIUS_PAGES/insert.php");

}

if(isset($_POST['go']) && $_POST['action'] == 'update') { // Was the form submitted?
	$link = mysqli_connect("us-cdbr-azure-central-a.cloudapp.net", "bf163b4e961420", "d052e7d8", "cs3380-logboat") or die ("Connection Error " . mysqli_error($link));
switch($_POST['table']){
	case "ingredient":	
	if($stmt = mysqli_prepare($link, "SELECT * FROM ingredient")){
		mysqli_stmt_execute($stmt); ## execute query 
		mysqli_stmt_bind_result($stmt, $IngID, $name, $supplier, $brand, $amount, $unit, $price_per_unit, $ingredient_type, $best_by, $lot_num, $low_amount); ## bind result variables 
		$count = 0;
		echo '<table class="table table-hover"><thead><tr>';
		echo "<th>                      </th>";
		echo "<th>IngID </th>";
		echo "<th>Name </th>";
		echo "<th>Supplier </th>";
		echo "<th>Brand </th>";
		echo "<th>Amount </th>";
		echo "<th>Unit </th>";
		echo "<th>Price/Unit </th>";
		echo "<th>Type </th>";
		echo "<th>Best By </th>";
		echo "<th>Lot #</th>";
		echo "<th>Threshold</th>";
		echo '</tr>    </thead>         <tbody>';
		while(mysqli_stmt_fetch($stmt)){
			printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
						'//<input type="submit" name="Delete" value = "Delete"/>
					.'	<input type = "hidden" name ="table" value = "Ingredient">
						<input type = "hidden" name = "IngID" value = "'. $IngID .'">
					</form>
					<form action="update.php" method="POST">
						<input type="submit" class = "btn btn-primary" name="update" value = "update"/>
						<input type = "hidden" name ="table" value = "Ingredient">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID.'">
						<input type = "hidden" name = "name" value = "'. $name.'">
						<input type = "hidden" name = "supplier" value = "'. $supplier.'">
                                                <input type = "hidden" name = "brand" value = "'. $brand.'">
                                                <input type = "hidden" name = "amount" value = "'. $amount.'">
                                                <input type = "hidden" name = "unit" value = "'. $unit.'">
                                                <input type = "hidden" name = "price_per_unit" value = "'. $price_per_unit.'">
                                                <input type = "hidden" name = "ingredient_type" value = "'. $ingredient_type.'">
                                                <input type = "hidden" name = "best_by" value = "'. $best_by.'">
                                                <input type = "hidden" name = "lot_num" value = "'. $lot_num.'">                          				<input type = "hidden" name = "low_amount" value = "'. $low_amount.'">
					<td>'.$IngID.'</td><td>'.$name.'</td><td>'.$supplier.'</td><td>'.$brand.'</td><td>'.$amount.'</td><td>'.$unit.'</td><td>'.$price_per_unit.'</td><td>'.$ingredient_type.'</td><td>'.$best_by.'</td><td>'.$lot_num.'</td><td>'.$low_amount);
			echo '</td></form></tr>';//</tb></table>';
			$count = $count +1;
		} ## fetch value 
		echo '</tb></table>';
	mysqli_stmt_close($stmt); ## close statement 
	}
	echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";
	break;

	case "batch":
        if($stmt = mysqli_prepare($link, "SELECT * FROM batch")){
                mysqli_stmt_execute($stmt); ## execute query
                mysqli_stmt_bind_result($stmt, $bid, $brewday, $fermentation_id, $packaging, $shipment); ## bind result variables
                $count = 0;
                echo '<table class="table table-hover"><thead><tr>';
                echo "<th>                      </th>";
                echo "<th>BID </th>";
                echo "<th>Brew Day</th>";
                echo "<th>Fermentation ID </th>";
                echo "<th>Packaging </th>";
                echo "<th>Shipment </th>";
                echo '</tr>    </thead>         <tbody>';
                while(mysqli_stmt_fetch($stmt)){
                        printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
                                                '//<input type="submit" name="Delete" value = "Delete"/>
                                        .'      <input type = "hidden" name ="table" value = "batch">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID .'">
                                        </form>
                                        <form action="update.php" method="POST">
                                                <input type="submit" class = "btn btn-primary" name="update" value = "update"/>
                                                <input type = "hidden" name ="table" value = "batch">
                                                <input type = "hidden" name = "bid" value = "'. $bid.'">
                                                <input type = "hidden" name = "brewday" value = "'. $brewday.'">
                                                <input type = "hidden" name = "fermentation_id" value = "'. $fermentation_id.'">
                                                <input type = "hidden" name = "packaging" value = "'. $packaging.'">
                                                <input type = "hidden" name = "shipment" value = "'. $shipment.'">
											<td>'.$bid.'</td><td>'.$brewday.'</td><td>'.$fermentation_id.'</td><td>'.$packaging.'</td><td>'.$shipment);
                        echo '</td></form></tr>';//</tb></table>';
                        $count = $count +1;
                } ## fetch value
                echo '</tb></table>';
        mysqli_stmt_close($stmt); ## close statement
        }
        echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";
        break;
	case "beer":
	        if($stmt = mysqli_prepare($link, "SELECT * FROM beer")){
                mysqli_stmt_execute($stmt); ## execute query
                mysqli_stmt_bind_result($stmt, $beerID, $recipe); ## bind result variables
                $count = 0;
                echo '<table class="table table-hover"><thead><tr>';
                echo "<th>                      </th>";
                echo "<th>Beer ID </th>";
                echo "<th>Recipe </th>";
                echo '</tr>    </thead>         <tbody>';
                while(mysqli_stmt_fetch($stmt)){
                        printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
                                                '//<input type="submit" name="Delete" value = "Delete"/>
                                        .'      <input type = "hidden" name ="table" value = "beer">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID .'">
                                        </form>
                                        <form action="update.php" method="POST">
                                                <input type="submit" class = "btn btn-primary" name="update" value = "update"/>
                                                <input type = "hidden" name ="table" value = "beer">
                                                <input type = "hidden" name = "beerID" value = "'. $beerID.'">
                                                <input type = "hidden" name = "recipe" value = "'. $recipe.'">
                                        <td>'.$beerID.'</td><td>'.$recipe);
                        echo '</td></form></tr>';//</tb></table>';
                        $count = $count +1;
                } ## fetch value
                echo '</tb></table>';
        mysqli_stmt_close($stmt); ## close statement
        }
        echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";
	break;
	case "beer_type":
        if($stmt = mysqli_prepare($link, "SELECT * FROM beer_type")){
                mysqli_stmt_execute($stmt); ## execute query
                mysqli_stmt_bind_result($stmt, $typeID, $name); ## bind result variables
                $count = 0;
                echo '<table class="table table-hover"><thead><tr>';
                echo "<th>                      </th>";
                echo "<th>Type ID </th>";
                echo "<th>Name </th>";
                echo '</tr>    </thead>         <tbody>';
                while(mysqli_stmt_fetch($stmt)){
                        printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
                                                '//<input type="submit" name="Delete" value = "Delete"/>
                                        .'      <input type = "hidden" name ="table" value = "Ingredient">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID .'">
                                        </form>
                                        <form action="update.php" method="POST">
                                                <input type="submit" class = "btn btn-primary" name="update" value = "update"/>
                                                <input type = "hidden" name ="table" value = "beer_type">
                                                <input type = "hidden" name = "typeID" value = "'. $typeID.'">
                                                <input type = "hidden" name = "name" value = "'. $name.'">
                                        <td>'.$typeID.'</td><td>'.$name);
                        echo '</td></form></tr>';//</tb></table>';
                        $count = $count +1;
                } ## fetch value
                echo '</tb></table>';
        mysqli_stmt_close($stmt); ## close statement
        }
        echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";
        break;
	case "fermentation":
        if($stmt = mysqli_prepare($link, "SELECT * FROM fermentation")){
                mysqli_stmt_execute($stmt); ## execute query
                mysqli_stmt_bind_result($stmt, $FID, $value, $unit_name, $fermentation_type, $brew_id, $timedate); ## bind result variables
                $count = 0;
                echo '<table class="table table-hover"><thead><tr>';
                echo "<th>                      </th>";
                echo "<th>FID </th>";
                echo "<th>Value </th>";
                echo "<th>Units </th>";
                echo "<th>Fermentation Type </th>";
                echo "<th>Brew ID </th>";
                echo "<th>Timestamp </th>";
                echo '</tr>    </thead>         <tbody>';
                while(mysqli_stmt_fetch($stmt)){
                        printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
                                                '//<input type="submit" name="Delete" value = "Delete"/>
                                        .'      <input type = "hidden" name ="table" value = "Ingredient">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID .'">
                                        </form>
                                        <form action="update.php" method="POST">
                                                <input type="submit" class = "btn btn-primary" name="update" value = "update"/>
                                                <input type = "hidden" name ="table" value = "fermentation">
                                                <input type = "hidden" name = "FID" value = "'. $FID.'">
                                                <input type = "hidden" name = "value" value = "'. $value.'">
                                                <input type = "hidden" name = "unit_name" value = "'. $unit_name.'">
                                                <input type = "hidden" name = "fermentation_type" value = "'. $fermentation_type.'">
                                                <input type = "hidden" name = "brew_id" value = "'. $brew_id.'">
                                                <input type = "hidden" name = "timedate" value = "'. $timedate.'">
                                                <td>'.$FID.'</td><td>'.$value.'</td><td>'.$unit_name.'</td><td>'.$fermentation_type.'</td><td>'.$brew_id.'</td><td>'.$timedate);
                        echo '</td></form></tr>';//</tb></table>';
                        $count = $count +1;
                } ## fetch value
                echo '</tb></table>';
        mysqli_stmt_close($stmt); ## close statement
        }
        echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";
	break;
	case "fermentation_type":
        if($stmt = mysqli_prepare($link, "SELECT * FROM fermentation_type")){
                mysqli_stmt_execute($stmt); ## execute query
                mysqli_stmt_bind_result($stmt, $ftypeID, $name); ## bind result variables
                $count = 0;
                echo '<table class="table table-hover"><thead><tr>';
                echo "<th>                      </th>";
                echo "<th>Fermentation Type ID </th>";
                echo "<th>Name </th>";
                echo '</tr>    </thead>         <tbody>';
                while(mysqli_stmt_fetch($stmt)){
                        printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
                                                '//<input type="submit" name="Delete" value = "Delete"/>
                                        .'      <input type = "hidden" name ="table" value = "Ingredient">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID .'">
                                        </form>
                                        <form action="update.php" method="POST">
                                                <input type="submit" class = "btn btn-primary" name="update" value = "update"/>
                                                <input type = "hidden" name ="table" value = "fermentation_type">
                                                <input type = "hidden" name = "ftypeID" value = "'. $ftypeID.'">
                                                <input type = "hidden" name = "name" value = "'. $name.'">
                                                <td>'.$ftypeID.'</td><td>'.$name);
                        echo '</td></form></tr>';//</tb></table>';
                        $count = $count +1;
                } ## fetch value
                echo '</tb></table>';
        mysqli_stmt_close($stmt); ## close statement
        }
        echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";
	break;
	case "unit":
        if($stmt = mysqli_prepare($link, "SELECT * FROM unit")){
                mysqli_stmt_execute($stmt); ## execute query
                mysqli_stmt_bind_result($stmt, $name); ## bind result variables
                $count = 0;
                echo '<table class="table table-hover"><thead><tr>';
                echo "<th>                      </th>";
                echo "<th>Name </th>";
                echo '</tr>    </thead>         <tbody>';
                while(mysqli_stmt_fetch($stmt)){
                        printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
                                                '//<input type="submit" name="Delete" value = "Delete"/>
                                        .'      <input type = "hidden" name ="table" value = "Ingredient">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID .'">
                                        </form>
                                        <form action="update.php" method="POST">
                                                <input type="submit" class = "btn btn-primary" name="update" value = "update"/>
                                                <input type = "hidden" name ="table" value = "unit">
                                                <input type = "hidden" name = "name" value = "'. $name.'">
                                                <td>'.$name);
                        echo '</td></form></tr>';//</tb></table>';
                        $count = $count +1;
                } ## fetch value
                echo '</tb></table>';
        mysqli_stmt_close($stmt); ## close statement
        }
        echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";        
	break;
	case "keg":
        if($stmt = mysqli_prepare($link, "SELECT * FROM keg")){
                mysqli_stmt_execute($stmt); ## execute query
                mysqli_stmt_bind_result($stmt, $kid, $fill_date, $distribution_date, $return_date); ## bind result variables
                $count = 0;
                echo '<table class="table table-hover"><thead><tr>';
                echo "<th>                      </th>";
                echo "<th>Keg ID </th>";
                echo "<th>Fill Date </th>";
                echo "<th>Distribution Date </th>";
                echo "<th>Return Date </th>";
                echo '</tr>    </thead>         <tbody>';
                while(mysqli_stmt_fetch($stmt)){
                        printf("%s",'<br><tr><td><form name="XC" action="delete.php" method="POST">
                                                '//<input type="submit" name="Delete" value = "Delete"/>
                                        .'      <input type = "hidden" name ="table" value = "Ingredient">
                                                <input type = "hidden" name = "IngID" value = "'. $IngID .'">
                                        </form>
                                        <form action="update.php" method="POST">
                                                <input type="submit" class = "btn btn-primary" name="update" value = "update"/>
                                                <input type = "hidden" name ="table" value = "keg">
                                                <input type = "hidden" name = "kid" value = "'. $kid.'">
                                                <input type = "hidden" name = "fill_date" value = "'. $fill_date.'">
                                                <input type = "hidden" name = "distribution_date" value = "'. $distribution_date.'">
                                                <input type = "hidden" name = "return_date" value = "'. $return_date.'">
                                            <td>'.$kid.'</td><td>'.$fill_date.'</td><td>'.$distribution_date.'</td><td>'.$return_date);
                        echo '</td></form></tr>';//</tb></table>';
                        $count = $count +1;
                } ## fetch value
                echo '</tb></table>';
        mysqli_stmt_close($stmt); ## close statement
        }
        echo "<h4 class='rowNumber'>Number of rows: ".$count."</h4>";        
	break;

	}
##$result = mysqli_query($link, $sql) or die ("Query Error: " .$sql . mysqli_errno($link));
				    // echo "<h4>Number of rows: ".mysqli_num_rows($result)."</h4>";
					 //echo 'Total number of rows in result:' . mysqli_num_rows($result);

			?>

<form name="T" action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="col-md-4 col-md-offset-5">

			<table class="table table-hover">
				<thead>
					<tr>
			<?php/*
			        while ($fieldinfo=mysqli_fetch_field($result)) {
			           
			        }
			?>
					</tr>
				</thead>
				<tbody>
			<?php
		                while($row = mysqli_fetch_assoc($result)) {
			            echo '<tr><td><input type="submit" name="Delete" value = "Delete"/></td> <td><input type="submit" name="Update" value = "Update"/></td>';
			            foreach($row as $field) {
			                echo '<td>'. $field .'</td>';
			            }//end foreach
			            echo '</tr>';
			        }//end while
			    }//end if
			/*/?>
				</tb>
</form>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</body>
</html>
