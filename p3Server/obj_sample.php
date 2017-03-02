

<!DOCTYPE html>
<link rel="stylesheet" type="text/css" href="style.css" />
<?php 
	session_start();  //session start for logged in user.
	if(isset($_SESSION['user'])){
		echo "<div id='loginnotif'>";
		print_r($_SESSION['user']);
		echo "</div>";
	}

if (isset($_POST['name_review'])){ //collecting the gets for review submission from review_submission.php
	$r_obj_id = $_POST['id'];
	$r_name = $_POST['name_review'];
	$r_lat = $_POST['lat_review'];
	$r_long = $_POST['lon_review'];
	$r_clean = $_POST['cleanRate_review'];
	$r_comf = $_POST['comfRate_review'];
	$r_paper = $_POST['paperRate_review'];
	$r_overall = $_POST['OverallRate_review'];
	$r_text = $_POST['text_review'];
	create_review($r_obj_id, $r_clean, $r_comf, $r_paper, $r_overall, $r_text); //creates the review
}	
if (isset($_GET["id"])){ //get requests for loading hte information to load the reviews
		$OBJID = $_GET["id"];
		$obj = retrieve_obj_data($OBJID);
		$name_review = $obj['name'];
		$lat_review = $obj['latitude'];
		$long_review = $obj['longitude'];
		$reviews = retrieve_obj_reviews($OBJID); //calls a function that gets all the reviews for the object by the objid
	}
	function create_review($obj_id, $clean, $comf, $paper, $overall, $text ){ //creates the review and is the function that inserts it into the db table 'reviews'
		$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', ''); // pdo connection
		if (isset($_SESSION['user'])){
			try {
				$statement_insert = $pdo->prepare("INSERT INTO reviews (id, obj_id, cleanRating,comfRating,paperRating,overallRating, `text`) VALUES (?,?,?,?,?,?,?)"); // the insert and prepare statement
				$statement_insert->execute(array(NULL, $obj_id, $clean, $comf, $paper, $overall, $text)); //execute
				$pdo = NULL;
				echo '<script>; alert("Submission Successfully Accepted! Review Created"); </script>'; //notify user
			} catch (PDOException $e) {
				echo $e->getMessage();
				$error = $statement_insert->errorInfo();
				print_r($error);
				$pdo = NULL;
			}
		}else{
			echo '<script>; alert("You need to be logged in to complete this action."); </script>';
		}
	}
	function retrieve_obj_data($id){ //function that that retrieves the object information to be loaded into the page 
		$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', ''); //pdo connection
		try {
			$result = $pdo->prepare(" 	
				SELECT name, longitude,latitude, description FROM objects WHERE objects.id = ? "); //the prepare select taht gets all the object info
			$result->execute(array($id)); // execute statement 
			$pdo = NULL;
			return $result->fetch(); //fetch to make the data usable
		} catch (PDOException $e) {
			echo $e->getMessage();
			$error = $statement_insert->errorInfo();
			print_r($error);
			$pdo = NULL;
		}
	}
	function retrieve_obj_reviews($id){ //retrieves all the reviews for the obj by review
		$pdo = new PDO('mysql:host=localhost;dbname=bombsAway', 'root', '');
		try {
			$result = $pdo->prepare(" 	
					SELECT name, longitude,latitude, reviews.cleanRating as clean, reviews.comfRating as comf,reviews.paperRating as paper, reviews.overallRating as rating, objects.id, reviews.text as descrip 
					FROM `objects` 
					LEFT JOIN `reviews` on `objects`.`id` = `reviews`.`obj_id` 
					WHERE objects.id = ? AND objects.id = reviews.obj_id
				"); // prepare statemten for getting the reviews
			
			$result->execute(array($id));// execute
			$pdo = NULL;
			return $result->fetchAll();
		} catch (PDOException $e) {
			echo $e->getMessage();
			$error = $statement_insert->errorInfo();
			print_r($error);
			$pdo = NULL;
		}	
	} 
	function load_review($name,$long,$lat,$clean,$comf,$paper,$rating,$description){ //the fucntion for createing each of hte review blocks in html
		echo "<div class='searchrow'>";
		echo	"<div class='rowwrapper'>";
		echo		"<img src='pic\ava1.png' alt='map2' style='width:100px;height:100px;'' />";
		echo		"<div id='res1' class='searchres'>";
		echo			"Name: $name<br>";
		echo			"Location: <br>Longitude: $long <br>Latitude: $lat";
		echo 		"</div>";	
		echo		"<div class='res_rating'>";
		echo			"Cleanliness: $clean/5<br>";
		echo			"Comfort: $comf/5<br>";
		echo			"Toilet Paper: $paper/5<br>";
		echo			"Overall: $rating/5<br>";
		echo		"</div>";
		echo		"<div class='search res'>";
		echo			"<p>";
		echo			"$description";
		echo			"<p>";
		echo		"</div>";
		echo	"</div>";	
		echo "</div>";
		
	}	
?>
<html>
	<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<!-- in line sstyling for the map for hte meantime, to make hard coding simpler-->
		<style> 
			#map { 
				width: 100%;
				height: 340px;
			}
		</style>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqQ4rz8maBFND1rzKwp0Dp5TrNWtgg0kc"></script> <!--the new scirpts added for loading the map -->
		<script type="text/javascript" src="jscript\script.js"></script> <!-- the old link to css page -->
		
		</head>

	<body> <!--beginning of the body, use wrapper to conatian everything -->
		<div id="wrapper">
			<?php include 'includes/banner.inc' ?> <!-- includes for condensing the code -->
			<?php include 'includes/menu.inc' ?>
			<?php include 'includes/hbar.inc' ?>
				<div class="content">
				<?php //displays for the code
					echo "<h2>";
					echo $obj['name'];
					echo "</h2>";
					echo "<p> Description:"; echo $obj['description'];
					echo "<br> </p>";
				?>
					
					
					<div class="submit-review-wrap">
					<?php
						//the button for creating reviews, sending hidden values to the review_sub page
						echo "<form action='review_submission.php' method='GET'>";
						echo "<input type='hidden' name=id value=$OBJID>";
						echo "<input type='hidden' name=lat value=$lat_review>";
						echo "<input type='hidden' name=long value=$long_review>";
						echo "<input type='hidden' name=name value=\"$name_review\">";
						echo	"<input type='submit' id='existing-obj-review' value='Click here to add a review to this Object!'>";
						echo "</form>";
					?>	
					</div>
					<div class="multicontent">
						<div id="contentleft">
							<div id="map"></div><!-- image has been removed with imbedded location, for hte time being hte map is being loaded inline to make seperation and hard coding easier-->

							<script>
								 // key: IzaSyDAGgA52jfF2gKmSGsXQIThLHrwOSMzUmo
							      // In the following, markers appear where the onbj page is located.
							      //init created the hardcoded marker and info box associated with it.
							      var labels = '123456789';
							      var labelIndex = 0;
							      var markerArray = [];
							      var markerInfoBx = [];
							      var lat = 43.263;
							      var long = -79.919;

							      function initialize(lat,long, contentStr) {
							        var mac = { lat: lat, lng: long }; //hard coded marker 1
							        //the below locations are for testing purposes
							        var map = new google.maps.Map(document.getElementById('map'), { //creates the map and centers on the marker
							          zoom: 15,
							          center: mac
							        });
							        var string = contentStr
							        addMarker(mac, map, string);
							      }// end of initialize
							      // Adds a marker to the map.
							      function addMarker(location, map, string) {
							        var marker = new google.maps.Marker({ //creating the marker variable at the locaion, on the map, with the information for the marker
							          position: location,
							          //label: labels[labelIndex++ % labels.length],
							          map: map
							        });
							        var infowindow = new google.maps.InfoWindow({ //creates the infowindow assocaited with the marker
					          			content: string
					        		});
							        marker.addListener('click', function(){ //adds the infobox listener to know when to have it appear
							        	infowindow.open(map, marker);
							        });   
							    }
							     //google.maps.event.addDomListener(window, 'load', initialize(lat,long,"test")); // google map api to laod the map
						    </script>
						</div>
						<div id="contentright"> <!-- user submitted picture of the obj goes here-->
							<?php // the code for retrieving the object picture from the amazon S3 server
							$path = "'https://s3-us-west-2.amazonaws.com/bastore/uploads/$OBJID.jpg'";
							echo "<div class='img_container_obj'  style=\"background-image: url($path);\"></div>";
							?>
						</div>
					</div>	
					<?php 
					function load_map_marker($name,$long,$lat,$clean,$comf,$paper,$rating){ //function that laods the map marker in javaript, 
						echo "<script>";
						
						echo "var contentStr = '<h1>$name</h1> <br> Cleanliness: $clean/5 <br> Comfort: $comf/5 <br> Toilet Paper: $paper/5 <br> Overall: $rating/5<br>';";
						echo "var lat = $lat;";
						echo "var long = $long;";
						echo "google.maps.event.addDomListener(window, 'load', initialize(lat,long,contentStr));";
						echo "</script>";
					}
					//varaibles used for calulating averages
					$numOfReviews=0;
					$cleanRate=0;
					$comfRate=0;
					$paperRate=0;
					$overallRate=0;
					if (isset($reviews)){ //if review is set its safe to compute
						foreach ($reviews as $rev){
							load_review($rev['name'],$rev['longitude'],$rev['latitude'],$rev['clean'],$rev['comf'],$rev['paper'],$rev['rating'],$rev['descrip']);
							$numOfReviews = $numOfReviews + 1;
							$cleanRate = $cleanRate +  $rev['clean'];
							$comfRate = $comfRate + $rev['comf'];
							$paperRate = $paperRate + $rev['comf'] ;
							$overallRate = $overallRate + $rev['rating'];
						}	
					}
					if ($numOfReviews == 0){ //also sets the information for the map marker tags
						load_map_marker($obj['name'],$obj['longitude'],$obj['latitude'],"n/a","n/a","n/a","n/a");
					} else {
						$cleanRate = $cleanRate/$numOfReviews;
						$comfRate = $comfRate/$numOfReviews;
						$paperRate = $paperRate/$numOfReviews;
						$overallRate = $overallRate/$numOfReviews;
						load_map_marker($rev['name'],$rev['longitude'],$rev['latitude'],$cleanRate,$comfRate,$paperRate,$overallRate);
					}
					?>
				</div>

				<div class="footer" > <!--footer -->
					TJ Walker 2016
				</div>


		</div>	
	</body>
</html>