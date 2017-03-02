

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css" /><!-- css -->
<?php 
		session_start(); 
		if(isset($_SESSION['user'])){
			echo "<div id='loginnotif'>";
			print_r($_SESSION['user']);
			echo "</div>";
		}
?>
	<head>
	<script type="text/javascript" src="jscript\script.js"></script> <!-- likn to javescript -->	
	</head>
	<body> <!--beginning of the body, use wrapper to conatian everything -->
		<div id="wrapper">
			<?php include 'includes/banner.inc' ?>

			<?php include 'includes/menu.inc' ?>
			<?php include 'includes/hbar.inc' ?>

			<div class="content"> <!--current default search has no fields, might restucture-->
			<!-- 
			<form> 
				<input type="button" id="geoSearch" value="Search by Location!"> 
				Latitude:<input type="text" id="lat" value="" >
				Longitude:<input type="text" id="lon" value="" >
				<input type="button" id="geoGrab" value="Get my Location!" onclick="getLocation();"> 
			</form>
			-->
			<h2>
				Hello and welcome to Bombs Away!
			</h2>
			<p>
				This is your premier website for rating and finding toilets around you during your time of need.
			</p>

				
			</div>

			
			<div class="footer" > <!-- footer -->
				TJ Walker 2016
			</div>


		</div>	
	</body>
</html>