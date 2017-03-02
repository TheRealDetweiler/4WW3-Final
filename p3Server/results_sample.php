

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="style.css" />
<?php 
	session_start(); //session start
	if(isset($_SESSION['user'])){
		echo "<div id='loginnotif'>";
		print_r($_SESSION['user']);
		echo "</div>";
	}
	function submit_search($option, $value, $value2) //submit search funtcion. 
	{
		if ($option == "Text"){ //if option is by text matches the string 
			$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', '');
			try {//grabs all the information to be displayed in the each query
				$result = $pdo->prepare(" 
					SELECT name, longitude,latitude, avg(reviews.cleanRating) as avgclean,avg(reviews.comfRating) as avgcomf,avg(reviews.paperRating) as avgpaper, avg(reviews.overallRating) as avgrating, objects.id
					FROM `objects` 
					LEFT JOIN `reviews` on `objects`.`id` = `reviews`.`obj_id` 
					GROUP BY objects.id
					HAVING objects.name = ?
 				"); //prepared statement
				$result->execute(array($value)); //execute
				$pdo = NULL;
				return $result;
			} catch (PDOException $e) {
				echo $e->getMessage();
				$error = $statement_insert->errorInfo();
				print_r($error);
				$pdo = NULL;
			}
		} else if ($option == "Rating"){ //if option is rating looks for all results with a rating greater or equal too
			$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', '');
			try {
				$result = $pdo->prepare(" 	
					SELECT name, longitude,latitude, avg(reviews.cleanRating) as avgclean,avg(reviews.comfRating) as avgcomf,avg(reviews.paperRating) as avgpaper, avg(reviews.overallRating) as avgrating, objects.id
					FROM `objects` 
					LEFT JOIN `reviews` on `objects`.`id` = `reviews`.`obj_id` 
					GROUP BY objects.id
					HAVING avgrating >= ?
 				");//prepare
				$result->execute(array($value2));//execute
				
				$pdo = NULL;
				return $result;
			} catch (PDOException $e) {
				echo $e->getMessage();
				$error = $statement_insert->errorInfo();
				print_r($error);
				$pdo = NULL;
			}
		}
	}
	function new_search_res($name, $lon, $lat, $clean, $comf, $tp, $overall,$num,$id){ //php fucnction to replicate the html for each result
		echo "<div class='searchrow'>";
			echo "<div class='rowwrapper'>" ;
				$path = "'https://s3-us-west-2.amazonaws.com/bastore/uploads/$id.jpg'";
				echo "<div class='img_container_res'  style=\"background-image: url($path);\"></div>";
				echo "<div class='searchres'>";
					echo "<a href='obj_sample.php?id=$id' action='GET' >"; echo "$num. $name ($id)"; echo "</a>";
					echo "<br>Long: "; echo $lon;  
					echo "<br>Lat: "; echo $lat;
					echo "<br>";
				echo "</div>" ;
				echo "<div class='res_rating'>";
					echo "Clean: "; echo $clean; echo "/5 <br>";
					echo "Comfort: "; echo $comf; echo "/5 <br>";
					echo "Paper: "; echo $tp ; echo "/5 <br>";
					echo "Overall: "; echo $overall; echo "/5 <br>";
				echo "</div>" ;
			echo "</div>";
		echo "</div>";
	}

	
	if (isset($_GET["searchOption"])){
		$op = $_GET["searchOption"];
		$text = $_GET["textSub"];
		$rate = $_GET["rateDrop"];
		$searchResult = submit_search($op,$text,$rate);
	} 
?>
<html>
	<head>
		<script type="text/javascript" src="jscript\script.js"></script>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<!-- in line sstyling for the map for hte meantime, to make hard coding simpler -->
		<style> 
			#map { 
				width: 100%;
				height: 250px;			
			}
		</style>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqQ4rz8maBFND1rzKwp0Dp5TrNWtgg0kc"></script> <!-- script for loading the google map api-->
	</head>
	<body><!--beginning of the body, use wrapper to conatian everything -->
		<div id="wrapper">
			<?php include 'includes/banner.inc' ?>
			<?php include 'includes/menu.inc' ?>
			<?php include 'includes/hbar.inc' ?>
			<div class="content">
				<h2>Search Results</h2>
				<div id="map"></div><!-- map tag location-->
				<script>
						 // key: IzaSyDAGgA52jfF2gKmSGsXQIThLHrwOSMzUmo
					      // In the following example, markers appear where the reults are on the map.
					      // Each marker is labeled with a single numeric character.
					      var labels = '123456789';
					      var labelIndex = 0;
					      //var markerArray = [];
					      var markerInfoBx = [];
					      var map;
					      function initialize() {
					        var mac = { lat: 43.263, lng: -79.919 };
					        //test
					        var mac2 = {lat: 43.26, lng: -79.92};
					        map = new google.maps.Map(document.getElementById('map'), {
					          zoom: 15,
					          center: mac
					        });
					        

					      }
					      // end of initialize
					      // Adds a marker to the map.
					      function addMarker(location, map, contentStr) {
					        var marker = new google.maps.Marker({ //creating the marker variable at the locaion, on the map, with the information for the marker
					          position: location,
					          label: labels[labelIndex++ % labels.length],
					          map: map
					        });
					        var infowindow = new google.maps.InfoWindow({ //creates the infowindow assocaited with the marker
			          			content: contentStr
			        		});
					        marker.addListener('click', function(){ //adds the infobox listener to know when to have it appear
					        	infowindow.open(map, marker);
					        });   
					    }
					    
					      //google.maps.event.addDomListener(window, 'load', initialize); 
					      initialize();
					      
					      // google map api to laod the map
					      
			    	</script>
					<br>
				<?php
				
					$num = 0;
					if(isset($searchResult)){
						$searchResult2 = $searchResult->fetchAll();
						foreach ($searchResult2 as $return){
							$num = $num+1;
							new_search_res($return['name'],$return['longitude'],$return['latitude'],$return['avgclean'],$return['avgcomf'],$return['avgpaper'],$return['avgrating'],$num, $return['id']);
							$name = $return['name'];
							$clean = $return['avgclean'];
							$comf = $return['avgcomf'];
							$paper = $return['avgpaper'];
							$rating = $return['avgrating'];
							$locLong = $return['longitude'];
							$locLat = $return['latitude'];

							$contentString = "<b>$num.  $name</b><br> Location: $locLat, $locLong<br> Clean: $clean/5 <br> Comf: $comf/5 <br> Toilet Paper: $paper/5 <br> Overall: $rating/5<br>  <br>";

							echo "<script> addMarker({ lat: $locLat, lng: $locLong}, map, '<b>$num.  $name</b><br> Location: $locLat, $locLong<br> Clean: $clean/5 <br> Comf: $comf/5 <br> Toilet Paper: $paper/5 <br> Overall: $rating/5<br>  <br>'); </script> ";
						}
						$num = 0;	
					}
				?>
			</div>
			<div class="footer" > <!-- footer -->
				TJ Walker 2016
			</div>

		</div>	
	</body>
</html>


