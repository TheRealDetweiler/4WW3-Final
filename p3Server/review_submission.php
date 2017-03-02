

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css" />
<?php 
	session_start(); //session start
	if(isset($_SESSION['user'])){
		echo "<div id='loginnotif'>";
		print_r($_SESSION['user']);
		echo "</div>";
	}
	$name = $_GET["name"];
	$lat = $_GET['lat'];
	$long = $_GET['long'];
	$id = $_GET['id']; 	
?>
	<head> <!--Title place holder for future developed Title and Logo -->
		<script type="text/javascript" src="jscript\script.js"></script>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	</head>
	<body> <!--beginning of the body, use wrapper to conatian everything -->
		<div id="wrapper">
			<?php include 'includes/banner.inc' ?>
			<?php include 'includes/menu.inc' ?>
			<?php include 'includes/hbar.inc' ?>
			<div class="content"> <!--content tag, every thing between the head nad footer go into content -->
				<div class="submission"><!-- form for sumitting a bathroom location -->
					<h1>
					Provide information below to submit a Review:
					</h1>
					<?php 
					echo "<form id='regForm' action='obj_sample.php?id=$id' method='POST' > <!--refFrom is the form for registration, all fields are required or radio are prefilled in -->"; ?>
						Name:
	 		 			<input type="text" id="subname" name="name_review" pattern="[A-Za-z]{1,20}" value= <?php echo " \"$name\" "; ?>required readonly/><br> <!-- name pattern matched for a 2 length string-->
	  					Location Latitude:
	  					<input type="text" id="lat" name="lat_review" pattern="[-+]?[0-9]*[.,]?[0-9]+" value= <?php echo"'$lat'"; ?> required readonly><br> <!-- name pattern matched for a int or float numbers for lat-->
	  					Location Longitude:
	  					<input type="text" id="lon" name="lon_review" pattern ="[-+]?[0-9]*[.,]?[0-9]+" value= <?php echo"'$long'"; ?> required readonly><br> <!-- name pattern matched for a int or float numbers for lon-->
	  					
	  					Cleanliness:
	 		 			<input type="radio" name="cleanRate_review" id="c1" value="1" checked>1
	  					<input type="radio" name="cleanRate_review" id="c2" value="2">2
	  					<input type="radio" name="cleanRate_review" id="c3" value="3">3
	  					<input type="radio" name="cleanRate_review" id="c4" value="4">4
	  					<input type="radio" name="cleanRate_review" id="c5" value="5">5<br>
	 		 			Comfort:
	 		 			<input type="radio" name="comfRate_review" id="comf1" value="1" checked>1
	  					<input type="radio" name="comfRate_review" id="comf2" value="2">2
	  					<input type="radio" name="comfRate_review" id="comf3" value="3">3
	  					<input type="radio" name="comfRate_review" id="comf4" value="4">4
	  					<input type="radio" name="comfRate_review" id="comf5" value="5">5<br>
	 		 			Paper:
	 		 			<input type="radio" name="paperRate_review" id="tp1" value="1" checked>1
	  					<input type="radio" name="paperRate_review" id="tp2" value="2">2
	  					<input type="radio" name="paperRate_review" id="tp3" value="3">3
	  					<input type="radio" name="paperRate_review" id="tp4" value="4">4
	  					<input type="radio" name="paperRate_review" id="tp5" value="5">5<br>
	  					Overall:
	  					<select name="OverallRate_review" >
						    <option value="1">1</option>
						    <option value="2">2</option>
						    <option value="3">3</option>
						    <option value="4">4</option>
						    <option value="5">5</option>
						   
					  	</select><br>
					  	<?php echo "<input type='hidden' name='id' value=$id>"; ?>
	  					Additional Description (max 500 characters):
	  					<br>
	  					<textarea id="textSub" name="text_review"></textarea><br> <!-- additional text is optional -->
	  					<input type="submit" id="picSub" name="picSub" value="Click here to Submit!" >
					</form>		
				</div>
			</div>
			<div class="footer" > <!-- footer -->
				TJ Walker 2016
			</div>

		</div>	
	</body>
</html>